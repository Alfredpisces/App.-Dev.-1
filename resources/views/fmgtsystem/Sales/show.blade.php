<x-app-layout>
<x-slot name="header">
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">

        {{-- Page Title --}}
        <div class="mb-4 sm:mb-0">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                Invoice #{{ $sale->id }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Customer: {{ $sale->customer_name }}
            </p>
        </div>
        
        {{-- Back Button --}}
        <div>
            <a href="{{ route('sales.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                &larr; Back to All Sales
            </a>
        </div>

    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 md:p-8">
                
                {{-- Invoice Header --}}
                <div class="flex flex-col md:flex-row justify-between items-start mb-6 gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $sale->customer_name }}</h3>
                        <p class="text-gray-500 dark:text-gray-400">Invoice #{{ $sale->id }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        @php
                            // Determine status, prioritizing overdue
                            $status = $sale->is_overdue ? 'overdue' : $sale->status;
                            $statusClass = match($status) {
                                'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
                                'sent' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                                'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                                'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                            };
                        @endphp
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                </div>

                {{-- Invoice Details Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</h4>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">PHP {{ number_format($sale->amount, 2) }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sale Date</h4>
                        <p class="text-lg text-gray-900 dark:text-gray-100">{{ $sale->sale_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</h4>
                        <p class="text-lg text-gray-900 dark:text-gray-100">{{ $sale->due_date ? $sale->due_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>

                {{-- Notes Section --}}
                @if($sale->notes)
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Notes</h4>
                    <p class="mt-2 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $sale->notes }}</p>
                </div>
                @endif

                {{-- Other Info Section --}}
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Other Info</h4>
                    <ul class="mt-2 text-gray-700 dark:text-gray-300">
                        <li>
                            <strong>Taxable:</strong> {{ $sale->is_taxable ? 'Yes' : 'No' }}
                        </li>
                        <li>
                            <strong>Outstanding Amount:</strong> PHP {{ number_format($sale->outstanding_amount, 2) }}
                        </li>
                    </ul>
                </div>
                
                {{-- Payment History --}}
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payment History</h4>
                    
                    @if(is_array($sale->payment_history) && count($sale->payment_history) > 0)
                        <div class="mt-4 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Payment Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Notes
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Amount Paid
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($sale->payment_history as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ \Carbon\Carbon::parse($payment['date'])->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $payment['notes'] ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                                PHP {{ number_format($payment['amount'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @elseif(is_string($sale->payment_history) && !empty($sale->payment_history))
                        <div class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                            {!! nl2br(e(str_replace('; ', "\n", $sale->payment_history))) !!}
                        </div>
                    
                    @else
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No payments have been recorded for this invoice yet.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>


</x-app-layout>