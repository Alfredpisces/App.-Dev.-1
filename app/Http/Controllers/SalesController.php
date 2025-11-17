<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->sales();
        if ($request->filled('status') && $request->input('status') !== 'all') {
            if ($request->input('status') === 'overdue') {
                $query->overdue();
            } else {
                $query->where('status', $request->input('status'));
            }
        }
        $sales = $query->latest('sale_date')->get();

        return view('fmgtsystem.sales', [
            'sales' => $sales,
            'outstandingAmount' => Auth::user()->sales()->whereIn('status', ['sent', 'draft'])->sum('outstanding_amount'),
            'overdueAmount' => Auth::user()->sales()->overdue()->sum('outstanding_amount'),
        ]);
    }

    public function store(Request $request)
    {
        // FIX: Prepare the boolean value *before* validation.
        // This converts the "on" from the checkbox into true/false.
        $request->merge([
            'is_taxable' => $request->has('is_taxable')
        ]);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'sale_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:sale_date',
            'status' => 'required|in:draft,sent,paid',
            'is_taxable' => 'nullable|boolean', // This validation rule will now pass
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        // Set initial outstanding amount and payment history
        $validated['outstanding_amount'] = $validated['status'] === 'paid' ? 0 : $validated['amount'];
        $validated['payment_history'] = $validated['status'] === 'paid' ? "Paid in full on " . Carbon::now()->format('Y-m-d') : null;

        Sale::create($validated);

        return redirect()->route('sales.index')->with('success', 'Sale / Invoice created successfully.');
    }

    public function show(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403);
        }
        return view('fmgtsystem.sales.show', compact('sale'));
    }
    
    // FIX: Changed to render the dedicated edit view
    public function edit(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return view('fmgtsystem.sales.edit', compact('sale'));
    }

    public function update(Request $request, Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Prepare the boolean value *before* validation.
        $request->merge([
            'is_taxable' => $request->has('is_taxable')
        ]);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'sale_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:sale_date',
            'status' => 'required|in:draft,sent,paid',
            'is_taxable' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $validated['is_taxable'] = $request->has('is_taxable');

        // Logic to manage outstanding_amount based on new status and amount
        if ($validated['status'] === 'paid') {
            $validated['outstanding_amount'] = 0;
            // Update history only if it was not paid before
            if ($sale->status !== 'paid') {
                $history = "Paid in full via status update on " . Carbon::now()->format('Y-m-d');
                $validated['payment_history'] = ($sale->payment_history ? $sale->payment_history . '; ' : '') . $history;
            }
        } else {
            // Calculate the total amount already paid
            $paidAmount = $sale->amount - ($sale->outstanding_amount ?? $sale->amount);
            
            // If amount changes, update outstanding_amount based on amount paid
            $validated['outstanding_amount'] = max(0, $validated['amount'] - $paidAmount);

            // If status changed from paid, clear history (user is essentially un-paying it)
            if ($sale->status === 'paid' && $validated['status'] !== 'paid') {
                $validated['payment_history'] = null;
            }
        }

        $sale->update($validated);
        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    // This method shows the payment form, and its route name is used in sales.blade.php
    public function recordPaymentForm(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403);
        }
        return view('fmgtsystem.sales.payment', compact('sale'));
    }

    public function recordPayment(Request $request, Sale $sale)
    {
        if ($sale->user_id !== Auth::id() || $sale->status === 'paid') {
            abort(403, 'Unauthorized or already paid.');
        }

        $maxPayment = $sale->outstanding_amount ?? $sale->amount;

        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0.01|max:' . $maxPayment,
            'payment_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $currentOutstanding = $sale->outstanding_amount ?? $sale->amount;
        $sale->outstanding_amount = $currentOutstanding - $validated['payment_amount'];
        $sale->status = $sale->outstanding_amount <= 0.009 ? 'paid' : 'sent';
        
        $history = "Paid ₱" . number_format($validated['payment_amount'], 2) . " on " . $validated['payment_date'];
        if (!empty($validated['notes'])) {
            $history .= " (Notes: " . $validated['notes'] . ")";
        }
        // Append new payment to history
        $sale->payment_history = ($sale->payment_history ? $sale->payment_history . '; ' : '') . $history;

        $sale->save();

        return redirect()->route('sales.index')->with('success', 'Payment recorded successfully.');
    }
}