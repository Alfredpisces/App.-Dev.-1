<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;

class ReportsController extends Controller
{
    public function index()
    {
        // --- TIMEZONE-AWARE START/END DATES ---
        $userTimezone = Auth::user()->timezone ?? config('app.timezone') ?? 'UTC';

        // FIX: Point to the view inside the 'fmgtsystem' folder.
        return view('fmgtsystem.report', [
            'report_type' => 'pnl',
            'start_date' => Carbon::now($userTimezone)->startOfMonth()->format('Y-m-d'),
            'end_date' => Carbon::now($userTimezone)->endOfMonth()->format('Y-m-d'),
        ]);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:pnl,cashflow,tax',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);
        $reportData = null;

        // --- TIMEZONE-AWARE REPORTING ---
        // Get user's timezone to ensure report boundaries match their day
        $userTimezone = Auth::user()->timezone ?? config('app.timezone') ?? 'UTC';
        // Parse the provided dates *in the user's timezone* and get the full day
        $startCarbon = Carbon::parse($validated['start_date'], $userTimezone)->startOfDay();
        $endCarbon = Carbon::parse($validated['end_date'], $userTimezone)->endOfDay();
        // --- END TIMEZONE-AWARE ---
        
        switch ($validated['report_type']) {
            // Pass the timezone-aware Carbon objects to the report generators
            case 'pnl': $reportData = $this->generatePnlReport($startCarbon, $endCarbon); break;
            case 'cashflow': $reportData = $this->generateCashflowReport($startCarbon, $endCarbon, $userTimezone); break;
            case 'tax': $reportData = $this->generateTaxReport($startCarbon, $endCarbon); break;
        }

        // --- ADDED: CSV Export Logic ---
        if ($request->query('export') === 'csv') {
            return $this->exportCsv($reportData, $validated['report_type']);
        }
        // -------------------------------

        // FIX: Point to the view inside the 'fmgtsystem' folder.
        return view('fmgtsystem.report', [
            'reportData' => $reportData,
            'report_type' => $validated['report_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);
    }

    // --- UPDATED to accept Carbon objects ---
    private function generatePnlReport(Carbon $start, Carbon $end): array 
    {
        $user = Auth::user();
        // Use timezone-aware $start and $end
        $revenue = $user->sales()
            ->where('status', 'paid')
            ->whereBetween('sale_date', [$start, $end])
            ->sum('amount');
        
        $expensesByCat = $user->expenses()
            ->where('status', 'paid')
            ->whereBetween('expense_date', [$start, $end])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
        
        $totalExpenses = $expensesByCat->sum('total');

        return [
            'title' => 'Profit & Loss Statement',
            'period' => $start->format('M d, Y') . ' - ' . $end->format('M d, Y'), // Format from Carbon
            'revenue' => ['total' => $revenue],
            'expenses' => ['total' => $totalExpenses, 'categories' => $expensesByCat->map(fn($e) => ['name' => $e->category, 'amount' => $e->total])],
            'net_profit' => ['amount' => $revenue - $totalExpenses]
        ];
    }

    // --- UPDATED to accept Carbon objects and timezone string ---
    private function generateCashflowReport(Carbon $start, Carbon $end, string $userTimezone): array 
    {
        $user = Auth::user();
        
        // Use $start for the "before" query, which is already timezone-aware
        $revenueBefore = $user->sales()->where('status', 'paid')->where('sale_date', '<', $start)->sum('amount');
        $expensesBefore = $user->expenses()->where('status', 'paid')->where('expense_date', '<', $start)->sum('amount');
        
        $openingBalance = $revenueBefore - $expensesBefore;
        
        // Use timezone-aware $start and $end
        $inflows = $user->sales()
            ->where('status', 'paid')
            ->whereBetween('sale_date', [$start, $end])
            ->sum('amount');
        $outflows = $user->expenses()
            ->where('status', 'paid')
            ->whereBetween('expense_date', [$start, $end])
            ->sum('amount');

        $netCashFlow = $inflows - $outflows;

        return [
            'title' => 'Cash Flow Statement',
            'period' => $start->format('M d, Y') . ' - ' . $end->format('M d, Y'),
            'opening_balance' => $openingBalance,
            'inflows' => $inflows,
            'outflows' => $outflows,
            'net_cash_flow' => $netCashFlow,
            'closing_balance' => $openingBalance + $netCashFlow,
        ];
    }

    // --- UPDATED to accept Carbon objects ---
    private function generateTaxReport(Carbon $start, Carbon $end): array 
    {
        $user = Auth::user();

        // Use timezone-aware $start and $end
        $taxableRevenue = $user->sales()
            ->where('status', 'paid')
            ->where('is_taxable', true)
            ->whereBetween('sale_date', [$start, $end])
            ->sum('amount');
            
        $deductibleExpenses = $user->expenses()
            ->where('status', 'paid')
            ->where('is_tax_deductible', true)
            ->whereBetween('expense_date', [$start, $end])
            ->sum('amount');

        return [
            'title' => 'Tax Summary Report',
            'period' => $start->format('M d, Y') . ' - ' . $end->format('M d, Y'),
            'taxable_revenue' => $taxableRevenue,
            'deductible_expenses' => $deductibleExpenses,
            'estimated_taxable_income' => $taxableRevenue - $deductibleExpenses,
        ];
    }

    // --- NEW: CSV Export Method ---
    private function exportCsv(array $reportData, string $reportType): StreamedResponse
    {
        // FIX: Replaced str_slug() with Str::slug()
        $filename = Str::slug($reportData['title']) . '-' . str_replace([' ', ','], ['_', ''], $reportData['period']) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reportData, $reportType) {
            $file = fopen('php://output', 'w');

            // Header/Metadata
            fputcsv($file, [Auth::user()->business_name ?? Auth::user()->name]);
            fputcsv($file, [$reportData['title']]);
            fputcsv($file, ['Period', $reportData['period']]);
            fputcsv($file, []); // Blank line

            switch ($reportType) {
                case 'pnl':
                    fputcsv($file, ['Account', 'Amount']);
                    fputcsv($file, ['Revenue', $reportData['revenue']['total']]);
                    fputcsv($file, ['Total Expenses', $reportData['expenses']['total']]);
                    // Detailed expenses
                    if (count($reportData['expenses']['categories']) > 0) {
                        fputcsv($file, ['--- Detailed Expenses Breakdown ---', '']);
                        foreach ($reportData['expenses']['categories'] as $category) {
                            fputcsv($file, [$category['name'], $category['amount'] * -1]); // Expenses as negative for clarity
                        }
                    }
                    fputcsv($file, []);
                    fputcsv($file, ['NET PROFIT', $reportData['net_profit']['amount']]);
                    break;

                case 'cashflow':
                    fputcsv($file, ['Account', 'Amount']);
                    fputcsv($file, ['Opening Cash Balance', $reportData['opening_balance']]);
                    fputcsv($file, ['Cash Inflows (from Sales)', $reportData['inflows']]);
                    fputcsv($file, ['Cash Outflows (from Expenses)', $reportData['outflows'] * -1]); // Represent as negative
                    fputcsv($file, ['Net Cash Flow', $reportData['net_cash_flow']]);
                    fputcsv($file, ['CLOSING CASH BALANCE', $reportData['closing_balance']]);
                    break;

                case 'tax':
                    fputcsv($file, ['Account', 'Amount']);
                    fputcsv($file, ['Total Taxable Revenue', $reportData['taxable_revenue']]);
                    fputcsv($file, ['Total Deductible Expenses', $reportData['deductible_expenses'] * -1]); // Represent as negative
                    fputcsv($file, ['ESTIMATED TAXABLE INCOME', $reportData['estimated_taxable_income']]);
                    fputcsv($file, ['Note', '*This is an estimate for informational purposes only.']);
                    break;
            }

            fputcsv($file, []);
            // Use the user's timezone for the "generated on" timestamp
            $userTimezone = Auth::user()->timezone ?? config('app.timezone') ?? 'UTC';
            fputcsv($file, ['Report generated on', Carbon::now($userTimezone)->format('F j, Y, g:i a T')]);
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}