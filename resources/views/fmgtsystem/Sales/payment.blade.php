<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Record Payment for Invoice #{{ $sale->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- FIX: Changed route name to match web.php -->
                <form method="POST" action="{{ route('sales.payment', $sale) }}">
                    @csrf
                    <div class="p-6">
                        <div class="mb-4">
                            <p class="text-gray-600"><strong>Customer:</strong> {{ $sale->customer_name }}</p>
                            <p class="text-gray-600"><strong>Total Amount:</strong> PHP {{ number_format($sale->amount, 2) }}</p>
                            <p class="font-bold text-red-600"><strong>Outstanding Balance:</strong> PHP {{ number_format($sale->outstanding_amount, 2) }}</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="payment_amount" class="block text-sm font-medium text-gray-700">Payment Amount</label>
                                <input type="number" name="payment_amount" id="payment_amount" step="0.01" value="{{ old('payment_amount', $sale->outstanding_amount) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                @error('payment_amount') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="payment_date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                                <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                 @error('payment_date') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex justify-end items-center space-x-4">
                        <a href="{{ route('sales.index') }}" class="text-sm font-medium text-gray-600">Cancel</a>
                        <button type="submit" class="bg-green-600 text-black font-bold py-2 px-5 rounded-lg shadow-md hover:bg-green-700 transition-colors">
                            Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
