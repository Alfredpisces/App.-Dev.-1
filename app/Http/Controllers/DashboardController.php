<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
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
        $cashFlowData = $this->getCashFlowForecast($user);
        $taxEstimate = $this->getTaxEstimate($user);
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

    private function getActionItems($user, $startDate, $endDate): array
    {
        $items = [];
        $overdueCount = $user->sales()->where('status', 'sent')->where('due_date', '<', now())->count();
        if ($overdueCount > 0) {
            $items[] = [
                'title' => "Overdue Invoices",
                'description' => "You have {$overdueCount} invoice(s) past their due date.",
                'link' => route('sales.index', ['status' => 'overdue']),
                'color' => 'red',
            ];
        }

        $upcomingBills = $user->expenses()->where('status', 'due')->where('expense_date', '<=', now()->addDays(7))->count();
        if ($upcomingBills > 0) {
            $items[] = [
                'title' => "Upcoming Bills",
                'description' => "You have {$upcomingBills} bill(s) due within the next 7 days.",
                'link' => route('expenses.index', ['status' => 'due']),
                'color' => 'yellow',
            ];
        }

        $budgets = $user->budgets()->get();
        $currentMonthExpenses = $user->expenses()->where('status', 'paid')->whereBetween('expense_date', [$startDate, $endDate])->groupBy('category')->selectRaw('category, SUM(amount) as total')->pluck('total', 'category');
        $daysInPeriod = $startDate->diffInDays($endDate) + 1;
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
                break;
            }
        }

        return $items;
    }

    private function getFinancialSnapshot($user, $startDate, $endDate): array
    {
        $revenue = $user->sales()->where('status', 'paid')->whereBetween('sale_date', [$startDate, $endDate])->sum('amount');
        $expenses = $user->expenses()->where('status', 'paid')->whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        return [
            'revenue' => (float) $revenue,
            'expenses' => (float) $expenses,
            'net_profit' => (float) ($revenue - $expenses),
        ];
    }

    private function getAccountsReceivable($user): array
    {
        return [
            'total_due' => (float) $user->sales()->whereIn('status', ['sent', 'draft'])->sum('amount'),
            'overdue_count' => $user->sales()->where('status', 'sent')->where('due_date', '<', now())->count(),
        ];
    }

    private function getCashBalance($user): float
    {
        $totalPaidSales = $user->sales()->where('status', 'paid')->sum('amount');
        $totalPaidExpenses = $user->expenses()->where('status', 'paid')->sum('amount');
        return (float) ($totalPaidSales - $totalPaidExpenses);
    }

    private function getCashFlowForecast($user): array
    {
        $daysToForecast = 30;
        $historyDays = 90;
        $startHistory = now()->subDays($historyDays);

        $pastIncome = $user->sales()->where('status', 'paid')->where('sale_date', '>=', $startHistory)->sum('amount');
        $pastExpenses = $user->expenses()->where('status', 'paid')->where('expense_date', '>=', $startHistory)->sum('amount');
        $avgDailyNet = ($pastIncome - $pastExpenses) / $historyDays;

        $currentBalance = $this->getCashBalance($user);

        $labels = [];
        $data = [];
        for ($i = 0; $i < $daysToForecast; $i++) {
            $labels[] = now()->addDays($i)->format('M d');
            $data[] = $currentBalance + ($avgDailyNet * ($i + 1));
        }
        return ['labels' => $labels, 'data' => $data];
    }

    private function getTaxEstimate($user): float
    {
        $startOfQuarter = now()->startOfQuarter();
        $taxableRevenue = $user->sales()->where('is_taxable', true)->where('sale_date', '>=', $startOfQuarter)->sum('amount');
        $deductibleExpenses = $user->expenses()->where('is_tax_deductible', true)->where('expense_date', '>=', $startOfQuarter)->sum('amount');
        $estimatedProfit = $taxableRevenue - $deductibleExpenses;

        return $estimatedProfit > 0 ? (float) ($estimatedProfit * 0.08) : 0.0; // Simplified 8% tax rate
    }

    private function getMonthlyTrend($user): array
    {
        $labels = [];
        $incomeData = [];
        $expenseData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $range = [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()];
            $incomeData[] = (float) $user->sales()->where('status', 'paid')->whereBetween('sale_date', $range)->sum('amount');
            $expenseData[] = (float) $user->expenses()->where('status', 'paid')->whereBetween('expense_date', $range)->sum('amount');
        }
        return ['labels' => $labels, 'income' => $incomeData, 'expenses' => $expenseData];
    }

    private function getExpenseBreakdown($user, $startDate, $endDate): array
    {
        $data = $user->expenses()
            ->where('status', 'paid')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category');

        return [
            'labels' => $data->keys()->map(fn($cat) => ucfirst($cat))->toArray(),
            'data' => $data->values()->toArray(),
        ];
    }
}
