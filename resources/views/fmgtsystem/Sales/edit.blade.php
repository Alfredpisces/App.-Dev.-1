<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Invoice #{{ $sale->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Update Sale / Invoice Details</h3>
                    <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-900/50 border border-yellow-400 text-yellow-700 dark:text-yellow-300 rounded">
                        <p class="text-sm font-medium">Outstanding Balance: **PHP {{ number_format($sale->outstanding_amount, 2) }}**</p>
                        @if($sale->status !== 'paid')
                            <p class="text-xs mt-1">Changing the 'Amount' will recalculate the Outstanding Balance based on previous payments (if any).</p>
                        @endif
                    </div>
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 text-red-700 dark:text-red-300 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('sales.update', $sale) }}" class="space-y-4">
                        @csrf
                        @method('PUT') {{-- Use PUT method for updating --}}
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name</label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $sale->customer_name) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('customer_name') border-red-500 @enderror" required>
                            </div>
                            <div>
                                <label for="sale_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sale Date</label>
                                <input type="date" name="sale_date" id="sale_date" value="{{ old('sale_date', $sale->sale_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('sale_date') border-red-500 @enderror" required>
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $sale->due_date ? $sale->due_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('due_date') border-red-500 @enderror">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $sale->amount) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('amount') border-red-500 @enderror" required>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror" required>
                                    <option value="draft" {{ old('status', $sale->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ old('status', $sale->status) == 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="paid" {{ old('status', $sale->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_taxable" id="is_taxable" {{ old('is_taxable', $sale->is_taxable) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded">
                            <label for="is_taxable" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Is Taxable</label>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 @error('notes') border-red-500 @enderror">{{ old('notes', $sale->notes) }}</textarea>
                        </div>
                        
                        @if($sale->payment_history)
                        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                            <h4 class="font-semibold text-sm text-gray-800 dark:text-gray-200 mb-1">Payment History</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 break-words">{{ $sale->payment_history }}</p>
                        </div>
                        @endif

                        <div class="flex justify-end space-x-4">
                             <a href="{{ route('sales.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transform hover:scale-105 transition-transform duration-300">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>