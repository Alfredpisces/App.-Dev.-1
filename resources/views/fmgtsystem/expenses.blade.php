<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Smart Expense & Bills Hub
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Total This Month: <span class="font-bold text-orange-600">PHP {{ number_format($totalThisMonth, 2) }}</span>
                    Upcoming Bills: <span class="font-bold text-yellow-600">PHP {{ number_format($upcomingBills, 2) }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div id="expenseFormContainer" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Log New Expense or Bill</h3>
                    <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="vendor" class="block text-sm font-medium text-gray-700">Vendor / Payee</label>
                                <input type="text" name="vendor" id="vendor" placeholder="e.g., Meralco, Office Supplies Inc." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="expense_date" class="block text-sm font-medium text-gray-700">Expense Date</label>
                                <input type="date" name="expense_date" id="expense_date" value="{{ now()->format('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" step="0.01" min="0" name="amount" id="amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    @foreach($all_categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="paid" selected>Paid</option>
                                    <option value="due">Due</option>
                                </select>
                            </div>
                             <div>
                                <label for="receipt" class="block text-sm font-medium text-gray-700">Attach Receipt</label>
                                <input type="file" name="receipt" id="receipt" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                            </div>
                        </div>

                         <div class="flex items-center space-x-6 pt-2">
                             <label for="is_tax_deductible" class="flex items-center">
                                <input type="checkbox" name="is_tax_deductible" id="is_tax_deductible" class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2 text-sm text-gray-600">Is this tax-deductible?</span>
                            </label>
                             <label for="is_recurring" class="flex items-center">
                                <input type="checkbox" name="is_recurring" id="is_recurring" class="rounded border-gray-300 text-blue-600 shadow-sm">
                                <span class="ml-2 text-sm text-gray-600">Is this a recurring expense?</span>
                            </label>
                        </div>


                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded-lg hover:bg-blue-700">Save Expense</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Expense Records</h3>
                        <div class="flex space-x-2">
                            @foreach(['all', 'paid', 'due'] as $statusFilter)
                                <a href="{{ route('expenses.index', ['status' => $statusFilter]) }}" class="px-3 py-1 text-sm rounded-full {{ request('status', 'all') == $statusFilter ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                    {{ ucfirst($statusFilter) }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Receipt</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($expenses as $expense)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $expense->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($expense->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $expense->expense_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $expense->vendor }}</td>
                                        <td class="px-6 py-4">{{ ucfirst($expense->category) }}</td>
                                        <td class="px-6 py-4 text-right">PHP {{ number_format($expense->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if($expense->receipt_path)
                                                <a href="{{ $expense->receipt_url }}" target="_blank" class="text-blue-500 hover:text-blue-700">
                                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center text-sm font-medium space-x-2">
                                            <a href="{{ route('expenses.edit', $expense) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this expense?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-8 text-gray-500">No expense records match your filters.</td>
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
