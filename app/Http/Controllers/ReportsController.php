<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        // FIX: Point to the view inside the 'fmgtsystem' folder.
        return view('fmgtsystem.report', [
            'report_type' => 'pnl',
            'start_date' => Carbon::now()->startOfMonth()->format('Y-m-d'),
            'end_date' => Carbon::now()->endOfMonth()->format('Y-m-d'),
        ]);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate(['report_type' => 'required|in:pnl,cashflow,tax', 'start_date' => 'required|date', 'end_date' => 'required|date|after_or_equal:start_date']);
        $reportData = null;
        switch ($validated['report_type']) {
            case 'pnl': $reportData = $this->generatePnlReport($validated['start_date'], $validated['end_date']); break;
            case 'cashflow': $reportData = $this->generateCashflowReport($validated['start_date'], $validated['end_date']); break;
            case 'tax': $reportData = $this->generateTaxReport($validated['start_date'], $validated['end_date']); break;
        }

        // FIX: Point to the view inside the 'fmgtsystem' folder.
        return view('fmgtsystem.report', [
            'reportData' => $reportData,
            'report_type' => $validated['report_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);
    }

    private function generatePnlReport($start, $end): array { $user = Auth::user(); $revenue = $user->sales()->where('status', 'paid')->whereBetween('sale_date', [$start, $end])->sum('amount'); $expensesByCat = $user->expenses()->where('status', 'paid')->whereBetween('expense_date', [$start, $end])->selectRaw('category, SUM(amount) as total')->groupBy('category')->get(); $totalExpenses = $expensesByCat->sum('total'); return [ 'title' => 'Profit & Loss Statement', 'period' => Carbon::parse($start)->format('M d, Y') . ' - ' . Carbon::parse($end)->format('M d, Y'), 'revenue' => ['total' => $revenue], 'expenses' => ['total' => $totalExpenses, 'categories' => $expensesByCat->map(fn($e) => ['name' => $e->category, 'amount' => $e->total])], 'net_profit' => ['amount' => $revenue - $totalExpenses]]; }
    private function generateCashflowReport($start, $end): array { $user = Auth::user(); $revenueBefore = $user->sales()->where('status', 'paid')->where('sale_date', '<', $start)->sum('amount'); $expensesBefore = $user->expenses()->where('status', 'paid')->where('expense_date', '<', $start)->sum('amount'); $openingBalance = $revenueBefore - $expensesBefore; $inflows = $user->sales()->where('status', 'paid')->whereBetween('sale_date', [$start, $end])->sum('amount'); $outflows = $user->expenses()->where('status', 'paid')->whereBetween('expense_date', [$start, $end])->sum('amount'); $netCashFlow = $inflows - $outflows; return [ 'title' => 'Cash Flow Statement', 'period' => Carbon::parse($start)->format('M d, Y') . ' - ' . Carbon::parse($end)->format('M d, Y'), 'opening_balance' => $openingBalance, 'inflows' => $inflows, 'outflows' => $outflows, 'net_cash_flow' => $netCashFlow, 'closing_balance' => $openingBalance + $netCashFlow, ]; }
    private function generateTaxReport($start, $end): array { $user = Auth::user(); $taxableRevenue = $user->sales()->where('status', 'paid')->where('is_taxable', true)->whereBetween('sale_date', [$start, $end])->sum('amount'); $deductibleExpenses = $user->expenses()->where('status', 'paid')->where('is_tax_deductible', true)->whereBetween('expense_date', [$start, $end])->sum('amount'); return [ 'title' => 'Tax Summary Report', 'period' => Carbon::parse($start)->format('M d, Y') . ' - ' . Carbon::parse($end)->format('M d, Y'), 'taxable_revenue' => $taxableRevenue, 'deductible_expenses' => $deductibleExpenses, 'estimated_taxable_income' => $taxableRevenue - $deductibleExpenses, ]; }
}
