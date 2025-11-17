<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User; // It's good practice to import the models you use

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $period = $request->input('period', 'month');
        [$startDate, $endDate] = $this->getDateRange($period);

        // --- Data for Widgets ---

        // 1. Action Center (influenced by sales, expenses, budgets)
        $actionItems = $this->getActionItems($user, $startDate, $endDate);

        // 2. Financial Health Snapshot (sales + expenses)
        $snapshot = $this->getFinancialSnapshot($user, $startDate, $endDate);
        $accountsReceivable = $this->getAccountsReceivable($user);
        $cashBalance = $this->getCashBalance($user);

        // 3. Future Outlook (sales + expenses)
        $cashFlowData = $this->getCashFlowForecast($user); // <-- This now uses the new hybrid logic
        $taxEstimate = $this->getTaxEstimate($user); // This now uses the corrected function
        $savingsRate = $snapshot['revenue'] > 0 ? ($snapshot['net_profit'] / $snapshot['revenue']) * 100 : 0;

        // 4. Performance Analysis (sales + expenses)
        $monthlyTrend = $this->getMonthlyTrend($user);
        $expenseBreakdown = $this->getExpenseBreakdown($user, $startDate, $endDate);

        return view('dashboard', [
            'actionItems' => $actionItems,
            'snapshot' => $snapshot,
            'ar' => $accountsReceivable,
            'cashBalance' => $cashBalance,
            'cashFlowData' => $cashFlowData,
            'taxEstimate' => $taxEstimate,
            'savingsRate' => $savingsRate,
            'monthlyTrend' => $monthlyTrend,
            'expenseBreakdown' => $expenseBreakdown,
        ]);
    }

    private function getDateRange(string $period): array
    {
        $now = Carbon::now();
        return match ($period) {
            'quarter' => [$now->copy()->startOfQuarter(), $now],
            'year' => [$now->copy()->startOfYear(), $now],
            default => [$now->copy()->startOfMonth(), $now], // 'month' is default
        };
    }

    private function getActionItems(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $items = [];
        // Use overdue scope if it exists, otherwise filter manually
        $overdueCount = $user->sales()->where('status', 'sent')->where('due_date', '<', now())->count();
        if ($overdueCount > 0) {
            $items[] = [
                'title' => "Overdue Invoices",
                'description' => "You have {$overdueCount} invoice(s) past their due date.",
                'link' => route('sales.index', ['status' => 'overdue']),
                'color' => 'red',
            ];
        }

        $upcomingBills = $user->expenses()->where('status', 'due')->where('expense_date', '>=', now())->where('expense_date', '<=', now()->addDays(7))->count();
        if ($upcomingBills > 0) {
            $items[] = [
                'title' => "Upcoming Bills",
                'description' => "You have {$upcomingBills} bill(s) due within the next 7 days.",
                'link' => route('expenses.index', ['status' => 'due']),
                'color' => 'yellow',
            ];
        }

        // Check for budgets (if that feature exists)
        if (method_exists($user, 'budgets')) {
            $budgets = $user->budgets()->get();
            $currentMonthExpenses = $user->expenses()->where('status', 'paid')->whereBetween('expense_date', [$startDate, $endDate])->groupBy('category')->selectRaw('category, SUM(amount) as total')->pluck('total', 'category');
            
            $daysInPeriod = $startDate->diffInDays($endDate) + 1;
            // Handle division by zero if period is less than a day
            if ($daysInPeriod > 0) {
                $daysPassed = $startDate->diffInDays(now()) + 1;
                $percentOfPeriodPassed = $daysPassed / $daysInPeriod;

                foreach ($budgets as $budget) {
                    $spent = $currentMonthExpenses->get($budget->category, 0);
                    if ($budget->amount > 0 && ($spent / $budget->amount) > $percentOfPeriodPassed) {
                        $items[] = [
                            'title' => "Budget Alert: {$budget->category}",
                            'description' => "Spending is over pace for {$budget->category}.",
                            'link' => route('budgets.index'),
                            'color' => 'orange',
                        ];
                        break; // Show one budget alert at a time
                    }
                }
            }
        }

        return $items;
    }

    private function getFinancialSnapshot(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $revenue = $user->sales()->where('status', 'paid')->whereBetween('sale_date', [$startDate, $endDate])->sum('amount');
        $expenses = $user->expenses()->where('status', 'paid')->whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        return [
            'revenue' => (float) $revenue,
            'expenses' => (float) $expenses,
            'net_profit' => (float) ($revenue - $expenses),
        ];
    }

    private function getAccountsReceivable(User $user): array
    {
        return [
            'total_due' => (float) $user->sales()->whereIn('status', ['sent', 'draft'])->sum('outstanding_amount'),
            'overdue_count' => $user->sales()->where('status', 'sent')->where('due_date', '<', now())->count(),
        ];
    }

    private function getCashBalance(User $user): float
    {
        // This calculates a simple lifetime cash balance.
        // For a real-world app, you'd track this from a starting bank balance.
        $totalPaidSales = $user->sales()->where('status', 'paid')->sum('amount');
        $totalPaidExpenses = $user->expenses()->where('status', 'paid')->sum('amount');
        return (float) ($totalPaidSales - $totalPaidExpenses);
    }

    // --- THIS FUNCTION HAS BEEN UPGRADED ---
    // It now uses a hybrid approach:
    // 1. It calculates your average daily net profit from the last 90 days.
    // 2. It applies this average to every day as a "statistical trend".
    // 3. It ALSO adds any specific, scheduled invoices (inflows) and bills (outflows).
    private function getCashFlowForecast(User $user): array
    {
        $daysToForecast = 30;
        $historyDays = 90;
        
        // --- Part 1: Calculate Historical Average ---
        $historyStartDate = now()->subDays($historyDays);
        
        $pastIncome = $user->sales()
            ->where('status', 'paid')
            ->where('sale_date', '>=', $historyStartDate)
            ->sum('amount');
            
        $pastExpenses = $user->expenses()
            ->where('status', 'paid')
            ->where('expense_date', '>=', $historyStartDate)
            ->sum('amount');
        
        // Calculate the average net income per day over this period
        // This will be our "statistical trend"
        $avgDailyNet = $historyDays > 0 ? (($pastIncome - $pastExpenses) / $historyDays) : 0;

        // --- Part 2: Project Future ---
        $currentBalance = $this->getCashBalance($user); // Get the starting point
        
        $labels = [];
        $balanceData = []; // Array for the line chart (projected balance)
        $inflowData = [];  // Array for inflow bars
        $outflowData = []; // Array for outflow bars
        
        $projectedBalance = $currentBalance; // This will be our running total

        for ($i = 0; $i < $daysToForecast; $i++) {
            $date = Carbon::today()->addDays($i);

            // Find *specific* scheduled income for this day
            $scheduledIncome = $user->sales()
                                  ->whereIn('status', ['sent', 'draft'])
                                  ->whereDate('due_date', $date)
                                  ->sum('outstanding_amount');

            // Find *specific* scheduled expenses for this day
            $scheduledExpense = $user->expenses()
                                   ->where('status', 'due')
                                   ->whereDate('expense_date', $date)
                                   ->sum('amount');
            
            // Calculate the new balance:
            // Start with previous balance, add the statistical average,
            // AND add/subtract any specific transactions for today.
            $projectedBalance = $projectedBalance + $avgDailyNet + $scheduledIncome - $scheduledExpense;

            // Add to the chart arrays
            $labels[] = $date->format('M d');
            // We only want to show *bars* for *actual scheduled* items
            $inflowData[] = round($scheduledIncome, 2);
            $outflowData[] = round($scheduledExpense, 2);
            // The balance line reflects *both* the trend and the scheduled items
            $balanceData[] = round($projectedBalance, 2);
        }

        // Return all three data sets
        return [
            'labels' => $labels, 
            'inflows' => $inflowData,
            'outflows' => $outflowData,
            'balance' => $balanceData
        ];
    }

    /**
     * Get the estimated tax liability for the current quarter.
     * THIS IS THE FUNCTION WE FIXED.
     */
    private function getTaxEstimate(User $user): float
    {
        $startOfQuarter = now()->startOfQuarter();

        // FIX: Only calculate taxes on *paid* sales that are marked 'is_taxable'
        // You don't pay tax on income you haven't received yet.
        $taxableRevenue = $user->sales()
            ->where('status', 'paid') // <-- ADDED THIS LINE
            ->where('is_taxable', true)
            ->where('sale_date', '>=', $startOfQuarter)
            ->sum('amount');

        // FIX: Only deduct expenses that are *paid* and marked 'is_tax_deductible'
        // You can't deduct expenses you haven't paid yet.
        $deductibleExpenses = $user->expenses()
            ->where('status', 'paid') // <-- ADDED THIS LINE
            ->where('is_tax_deductible', true)
            ->where('expense_date', '>=', $startOfQuarter)
            ->sum('amount');
            
        $estimatedProfit = $taxableRevenue - $deductibleExpenses;

        // Apply a simplified 8% tax rate on the net taxable profit
        return $estimatedProfit > 0 ? (float) ($estimatedProfit * 0.08) : 0.0;
    }

    private function getMonthlyTrend(User $user): array
    {
        $labels = [];
        $incomeData = [];
        $expenseData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $range = [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()];
            
            $incomeData[] = (float) $user->sales()
                ->where('status', 'paid')
                ->whereBetween('sale_date', $range)
                ->sum('amount');
                
            $expenseData[] = (float) $user->expenses()
                ->where('status', 'paid')
                ->whereBetween('expense_date', $range)
                ->sum('amount');
        }
        return ['labels' => $labels, 'income' => $incomeData, 'expenses' => $expenseData];
    }

    private function getExpenseBreakdown(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $data = $user->expenses()
            ->where('status', 'paid')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderBy('total', 'desc') // Show biggest categories first
            ->get()
            ->pluck('total', 'category');

        return [
            'labels' => $data->keys()->map(fn($cat) => ucfirst($cat))->toArray(),
            'data' => $data->values()->map(fn($val) => (float) $val)->toArray(),
        ];
    }
}