<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Sales & Invoicing Hub
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Outstanding: <span class="font-bold text-blue-600">PHP {{ number_format($outstandingAmount, 2) }}</span>
                    | Overdue: <span class="font-bold text-red-600">PHP {{ number_format($overdueAmount, 2) }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Create Form --}}
            <div id="invoiceFormContainer" class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Create New Sale / Invoice</h3>
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('sales.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('customer_name') border-red-500 @enderror" required>
                            </div>
                            <div>
                                <label for="sale_date" class="block text-sm font-medium text-gray-700">Sale Date</label>
                                <input type="date" name="sale_date" id="sale_date" value="{{ old('sale_date', now()->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('sale_date') border-red-500 @enderror" required>
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('due_date') border-red-500 @enderror">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('amount') border-red-500 @enderror" required>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('status') border-red-500 @enderror" required>
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_taxable" id="is_taxable" {{ old('is_taxable') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_taxable" class="ml-2 block text-sm text-gray-900">Is Taxable</label>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                             <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-black bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transform hover:scale-105 transition-transform duration-300">
                                Create Sale / Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-md">
                <div class="flex space-x-2">
                    @php $currentStatus = request('status', 'all'); @endphp
                    <a href="{{ route('sales.index', ['status' => 'all']) }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $currentStatus == 'all' ? 'bg-blue-600 text-black shadow-md' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }}">All</a>
                    <a href="{{ route('sales.index', ['status' => 'draft']) }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $currentStatus == 'draft' ? 'bg-blue-600 text-black shadow-md' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }}">Draft</a>
                    <a href="{{ route('sales.index', ['status' => 'sent']) }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $currentStatus == 'sent' ? 'bg-blue-600 text-black shadow-md' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }}">Sent</a>
                    <a href="{{ route('sales.index', ['status' => 'paid']) }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $currentStatus == 'paid' ? 'bg-blue-600 text-black shadow-md' : 'text-gray-700 bg-gray-100 hover:bg-gray-200' }}">Paid</a>
                    <a href="{{ route('sales.index', ['status' => 'overdue']) }}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors {{ $currentStatus == 'overdue' ? 'bg-red-600 text-black shadow-md' : 'text-red-700 bg-red-100 hover:bg-red-200' }}">Overdue</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($sales as $sale)
                                    @php
                                        $status = $sale->is_overdue ? 'overdue' : $sale->status;
                                        $statusClass = match($status) {
                                            'paid' => 'bg-green-100 text-green-800',
                                            'sent' => 'bg-blue-100 text-blue-800',
                                            'overdue' => 'bg-red-100 text-red-800',
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4"><span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">{{ ucfirst($status) }}</span></td>
                                        <td class="px-6 py-4 font-medium text-gray-900">#{{ $sale->id }}</td>
                                        <td class="px-6 py-4">{{ $sale->customer_name }}</td>
                                        <td class="px-6 py-4">{{ $sale->sale_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">{{ $sale->due_date ? $sale->due_date->format('M d, Y') : 'N/A' }}</td>
                                        <td class="px-6 py-4 text-right font-medium text-gray-900">PHP {{ number_format($sale->amount, 2) }}</td>
                                        <td class="px-6 py-4 text-center text-sm font-medium space-x-2">
                                            <a href="{{ route('sales.show', $sale) }}" class="text-indigo-600 hover:text-indigo-900">Details</a>
                                            <a href="{{ route('sales.edit', $sale) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                            @if($sale->status !== 'paid')
                                                <a href="{{ route('sales.payment.form', $sale) }}" class="text-green-600 hover:text-green-900">Pay</a>
                                            @endif
                                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center py-8 text-gray-500">No records found. Start by adding a new sale!</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

