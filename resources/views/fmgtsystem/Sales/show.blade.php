<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Invoice #{{ $sale->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 border-b-2 border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Invoice to: {{ $sale->customer_name }}</h3>
                            <p class="text-gray-500">Invoice Date: {{ $sale->sale_date->format('F d, Y') }}</p>
                            <p class="text-gray-500">Due Date: {{ $sale->due_date ? $sale->due_date->format('F d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <h3 class="text-3xl font-extrabold text-gray-800">PHP {{ number_format($sale->amount, 2) }}</h3>
                             <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @switch($sale->status)
                                    @case('paid') bg-green-100 text-green-800 @break
                                    @case('sent') bg-blue-100 text-blue-800 @break
                                    @case('draft') bg-gray-100 text-gray-800 @break
                                    @default bg-yellow-100 text-yellow-800
                                @endswitch">
                                {{ ucfirst($sale->is_overdue ? 'Overdue' : $sale->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <h4 class="font-semibold text-gray-700">Notes:</h4>
                    <p class="mt-2 text-gray-600">{{ $sale->notes ?: 'No notes provided.' }}</p>
                </div>

                <div class="p-8 bg-gray-50 text-right">
                     <a href="{{ route('sales.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors">Back to Sales</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
