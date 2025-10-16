<?php
namespace App\Http\Controllers;

use App\Models\Expense; // Import the Expense model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Use the single source of truth for categories from the Expense model
        $all_categories = Expense::CATEGORIES;

        $savedBudgets = $user->budgets()->pluck('amount', 'category');
        
        $actualSpending = $user->expenses()
            ->where('status', 'paid')
            ->whereBetween('expense_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $percentOfMonthPassed = Carbon::now()->day / Carbon::now()->daysInMonth;

        $budgets = collect($all_categories)->map(function ($categoryName) use ($savedBudgets, $actualSpending, $percentOfMonthPassed) {
            $budgeted = $savedBudgets->get($categoryName, 0);
            $spent = $actualSpending->get($categoryName, 0);
            $pacing_status = 'On Track';

            if ($budgeted > 0) {
                $percentSpent = $spent / $budgeted;
                if ($percentSpent > 1) {
                    $pacing_status = 'Exceeded';
                } elseif ($percentSpent > $percentOfMonthPassed) {
                    $pacing_status = 'Over Pace';
                }
            }
            return [
                'category' => $categoryName,
                'budgeted' => (float) $budgeted,
                'spent' => (float) $spent,
                'pacing_status' => $pacing_status
            ];
        });

        // Prepare category data for the budget setting form
        $categoryDataForForm = collect($all_categories)->map(function ($categoryName) use ($savedBudgets) {
            return [
                'id' => $categoryName, // Use name for ID as it's unique
                'name' => $categoryName,
                'budgeted_amount' => $savedBudgets->get($categoryName, 0)
            ];
        });

        return view('fmgtsystem.budget', [
            'budgets' => $budgets,
            'totalSpent' => $budgets->sum('spent'),
            'totalBudgeted' => $budgets->sum('budgeted'),
            'all_categories' => $categoryDataForForm,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'budgets' => 'required|array',
            // Ensure the keys of the budgets array are valid categories
            'budgets.*' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated['budgets'] as $category => $amount) {
            // Additional check to ensure category is valid before saving
            if (in_array($category, Expense::CATEGORIES)) {
                auth()->user()->budgets()->updateOrCreate(
                    ['category' => $category],
                    ['amount' => $amount ?? 0]
                );
            }
        }
        return redirect()->route('budgets.index')->with('success', 'Budgets saved successfully.');
    }
}
