<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            Record Payment for Invoice #{{ $sale->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            {{-- Added dark mode classes to the main container --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <!-- FIX: Changed route name to match web.php -->
                <form method="POST" action="{{ route('sales.payment', $sale) }}">
                    @csrf
                    <div class="p-6">
                        <div class="mb-4 border-b border-gray-200 dark:border-gray-700 pb-4">
                            {{-- Added dark mode text classes --}}
                            <p class="text-gray-600 dark:text-gray-400"><strong>Customer:</strong> <span class="text-gray-900 dark:text-gray-100">{{ $sale->customer_name }}</span></p>
                            <p class="text-gray-600 dark:text-gray-400"><strong>Total Amount:</strong> <span class="text-gray-900 dark:text-gray-100">PHP {{ number_format($sale->amount, 2) }}</span></p>
                            <p class="font-bold text-red-600 dark:text-red-400"><strong>Outstanding Balance:</strong> PHP {{ number_format($sale->outstanding_amount, 2) }}</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                {{-- Added dark mode text classes --}}
                                <label for="payment_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Amount</label>
                                {{-- Added dark mode classes for input --}}
                                <input type="number" name="payment_amount" id="payment_amount" step="0.01" value="{{ old('payment_amount', $sale->outstanding_amount) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('payment_amount') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                {{-- Added dark mode text classes --}}
                                <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Date</label>
                                {{-- Added dark mode classes for input --}}
                                <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                 @error('payment_date') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                {{-- Added dark mode text classes --}}
                                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                                {{-- Added dark mode classes for textarea --}}
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Added dark mode classes for footer and button --}}
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 flex justify-end items-center space-x-4">
                        <a href="{{ route('sales.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                        <button type="submit" class="bg-green-600 text-white font-bold py-2 px-5 rounded-lg shadow-md hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400 dark:text-gray-900 transition-colors">
                            Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>