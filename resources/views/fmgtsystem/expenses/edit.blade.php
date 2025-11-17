<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Edit Expense #{{ $expense->id }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Vendor: {{ $expense->vendor }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    
                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 text-red-700 dark:text-red-300 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Edit Form --}}
                    <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT') {{-- This tells Laravel it's an UPDATE --}}

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="vendor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vendor / Payee</label>
                                <input type="text" name="vendor" id="vendor" value="{{ old('vendor', $expense->vendor) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label for="expense_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expense Date</label>
                                {{-- Format the Carbon date object for the HTML date input --}}
                                <input type="date" name="expense_date" id="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                <input type="number" step="0.01" min="0" name="amount" id="amount" value="{{ old('amount', $expense->amount) }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <select name="category" id="category" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    {{-- IMPORTANT: Your controller MUST pass $all_categories to this view --}}
                                    @foreach($all_categories as $category)
                                        <option value="{{ $category }}" {{ old('category', $expense->category) == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="paid" {{ old('status', $expense->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="due" {{ old('status', $expense->status) == 'due' ? 'selected' : '' }}>Due</option>
                                </select>
                            </div>
                             <div>
                                <label for="receipt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Change Receipt (Optional)</label>
                                <input type="file" name="receipt" id="receipt" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 dark:file:bg-orange-900/50 file:text-orange-700 dark:file:text-orange-300 hover:file:bg-orange-100 dark:hover:file:bg-orange-900">
                                @if($expense->receipt_path)
                                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        Current: <a href="{{ route('expenses.receipt', $expense) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">View Receipt</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                         <div class="flex items-center space-x-6 pt-2">
                             <label for="is_tax_deductible" class="flex items-center">
                                <input type="checkbox" name="is_tax_deductible" id="is_tax_deductible" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500" {{ old('is_tax_deductible', $expense->is_tax_deductible) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Is this tax-deductible?</span>
                            </label>
                             <label for="is_recurring" class="flex items-center">
                                <input type="checkbox" name="is_recurring" id="is_recurring" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500" {{ old('is_recurring', $expense->is_recurring) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Is this a recurring expense?</span>
                            </label>
                        </div>

                        <div class="flex justify-end items-center space-x-4 pt-4">
                            <a href="{{ route('expenses.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg transform hover:scale-105 transition-transform duration-300">
                                Update Expense
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>