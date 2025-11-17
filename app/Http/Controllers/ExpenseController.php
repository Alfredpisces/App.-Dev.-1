<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->expenses();

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        $expenses = $query->latest('expense_date')->get();
        
        $totalThisMonth = Auth::user()->expenses()
            ->whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        $upcomingBills = Auth::user()->expenses()
            ->where('status', 'due')
            ->where('expense_date', '>=', now())
            ->sum('amount');

        // NOTE: The previous logic to set $expense->receipt_url is now obsolete 
        // because we are using the secure route('expenses.receipt') in the view.
        
        // Pass the centralized category list to the view
        return view('fmgtsystem.expenses', [
            'expenses' => $expenses,
            'totalThisMonth' => $totalThisMonth,
            'upcomingBills' => $upcomingBills,
            'all_categories' => Expense::CATEGORIES
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // FIX: Prepare boolean values *before* validation.
        // This converts the "on" from the checkbox into true/false.
        $request->merge([
            'is_tax_deductible' => $request->has('is_tax_deductible'),
            'is_recurring' => $request->has('is_recurring')
        ]);

        $validated = $request->validate([
            'vendor' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            // Validate that the category exists in our model's list
            'category' => ['required', 'string', Rule::in(Expense::CATEGORIES)],
            'status' => 'required|in:paid,due',
            'receipt' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'is_tax_deductible' => 'nullable|boolean', // This rule will now pass
            'is_recurring' => 'nullable|boolean',      // This rule will now pass
        ]);

        $validated['user_id'] = Auth::id();
        
        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense logged successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        // Also pass categories to the edit view
        return view('fmgtsystem.expenses.edit', [
            'expense' => $expense,
            'all_categories' => Expense::CATEGORIES
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        // FIX: Prepare boolean values *before* validation.
        $request->merge([
            'is_tax_deductible' => $request->has('is_tax_deductible'),
            'is_recurring' => $request->has('is_recurring')
        ]);

        $validated = $request->validate([
            'vendor' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'category' => ['required', 'string', Rule::in(Expense::CATEGORIES)],
            'status' => 'required|in:paid,due',
            'is_tax_deductible' => 'nullable|boolean', // This rule will now pass
            'is_recurring' => 'nullable|boolean',      // This rule will now pass
        ]);
        
        // The validated data now contains the correct boolean values.

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            // FIX: Changed '4G' to '403'
            abort(403, 'Unauthorized action.');
        }

        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    /**
     * Serve the expense receipt securely.
     */
    public function showReceipt(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Unauthorized to view this receipt.');
        }

        if (!$expense->receipt_path || !Storage::disk('public')->exists($expense->receipt_path)) {
            abort(404, 'Receipt not found.');
        }

        // Return the file for download/display in the browser
        return Storage::disk('public')->response($expense->receipt_path);
    }
}