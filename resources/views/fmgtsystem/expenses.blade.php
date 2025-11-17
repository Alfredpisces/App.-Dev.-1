<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Smart Expense & Bills Hub
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Total This Month: <span class="font-bold text-orange-600 dark:text-orange-400">PHP {{ number_format($totalThisMonth, 2) }}</span>
                    Upcoming Bills: <span class="font-bold text-yellow-600 dark:text-yellow-400">PHP {{ number_format($upcomingBills, 2) }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div id="expenseFormContainer" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Log New Expense or Bill</h3>
                    <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="vendor" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vendor / Payee</label>
                                <input type="text" name="vendor" id="vendor" placeholder="e.g., Meralco, Office Supplies Inc." class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label for="expense_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expense Date</label>
                                <input type="date" name="expense_date" id="expense_date" value="{{ now()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                                <input type="number" step="0.01" min="0" name="amount" id="amount" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                                <select name="category" id="category" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($all_categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="paid" selected>Paid</option>
                                    <option value="due">Due</option>
                                </select>
                            </div>
                             <div>
                                <label for="receipt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Attach Receipt</label>
                                <input type="file" name="receipt" id="receipt" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 dark:file:bg-orange-900/50 file:text-orange-700 dark:file:text-orange-300 hover:file:bg-orange-100 dark:hover:file:bg-orange-900">
                            </div>
                        </div>

                         <div class="flex items-center space-x-6 pt-2">
                             <label for="is_tax_deductible" class="flex items-center">
                                <input type="checkbox" name="is_tax_deductible" id="is_tax_deductible" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Is this tax-deductible?</span>
                            </label>
                             <label for="is_recurring" class="flex items-center">
                                <input type="checkbox" name="is_recurring" id="is_recurring" class="rounded border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Is this a recurring expense?</span>
                            </label>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Save Expense</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Expense Records</h3>
                        <div class="flex space-x-2">
                            @foreach(['all', 'paid', 'due'] as $statusFilter)
                                <a href="{{ route('expenses.index', ['status' => $statusFilter]) }}" class="px-3 py-1 text-sm rounded-full transition-colors {{ request('status', 'all') == $statusFilter ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                                    {{ ucfirst($statusFilter) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Vendor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Category</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-10">Tax?</th>
                                    <th class="px-2 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase w-10">Recur?</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Receipt</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($expenses as $expense)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $expense->status == 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                                                {{ ucfirst($expense->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $expense->expense_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200">{{ $expense->vendor }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ ucfirst($expense->category) }}</td>
                                        <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-gray-200">PHP {{ number_format($expense->amount, 2) }}</td>

                                        <!-- Tax Deductible Status -->
                                        <td class="px-2 py-4 text-center">
                                            @if($expense->is_tax_deductible)
                                                <span class="text-green-500" title="Tax Deductible">
                                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </span>
                                            @else
                                                <span class="text-gray-400" title="Not Tax Deductible">&mdash;</span>
                                            @endif
                                        </td>

                                        <!-- Recurring Status -->
                                        <td class="px-2 py-4 text-center">
                                            @if($expense->is_recurring)
                                                <span class="text-blue-500" title="Recurring Expense">
                                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 19M17 4v5h1.582m0 0l-1.582 1.582M17 4l-1.582 1.582m-6 0h.01"></path></svg>
                                                </span>
                                            @else
                                                <span class="text-gray-400" title="One-time Expense">&mdash;</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if($expense->receipt_path)
                                                <!-- FIX: Use the secure route to serve the receipt -->
                                                <a href="{{ route('expenses.receipt', $expense) }}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-xs">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-medium space-x-3">
                                            <a href="{{ route('expenses.edit', $expense) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-8 text-gray-500 dark:text-gray-400">No expense records match your filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>