<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    Proactive Budget Planner
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    This Month's Progress: You've spent <span class="font-bold text-gray-800">PHP {{ number_format($totalSpent, 2) }}</span> of your <span class="font-bold text-gray-800">PHP {{ number_format($totalBudgeted, 2) }}</span> budget.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form method="POST" action="{{ route('budgets.store') }}">
                    @csrf
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">Set Your Monthly Budget Amounts</h3>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($all_categories as $category)
                            <div>
                                <label for="budget_{{ $category['id'] }}" class="block text-sm font-medium text-gray-700">{{ $category['name'] }}</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" step="0.01" name="budgets[{{ $category['id'] }}]" id="budget_{{ $category['id'] }}" value="{{ $category['budgeted_amount'] ?? 0 }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-black hover:bg-blue-700 sm:w-auto sm:text-sm">
                            Save Budgets
                        </button>
                    </div>
                </form>
            </div>

            @if(count($budgets) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($budgets as $budget)
                        @php
                            $progress = ($budget['budgeted'] > 0) ? ($budget['spent'] / $budget['budgeted']) * 100 : 0;
                            $progress = min($progress, 100); // Cap at 100% for visual
                            $bgColor = 'bg-gray-200';
                            if ($budget['pacing_status'] == 'Exceeded') $bgColor = 'bg-red-500';
                            elseif ($budget['pacing_status'] == 'Over Pace') $bgColor = 'bg-yellow-400';
                            else $bgColor = 'bg-green-500';
                        @endphp
                        <div class="bg-white p-5 rounded-lg shadow-lg">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-semibold text-gray-800">{{ $budget['category'] }}</h4>
                                <span class="text-xs font-medium px-2 py-1 rounded-full
                                    @if($budget['pacing_status'] == 'Exceeded') bg-red-100 text-red-800
                                    @elseif($budget['pacing_status'] == 'Over Pace') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $budget['pacing_status'] }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="{{ $bgColor }} h-4 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <p class="text-right text-sm text-gray-600 mt-2">
                                <span class="font-bold">₱{{ number_format($budget['spent'], 0) }}</span> spent of ₱{{ number_format($budget['budgeted'], 0) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center bg-white p-12 rounded-lg shadow-lg">
                    <h3 class="text-xl font-medium text-gray-800">You haven't set any budgets yet!</h3>
                    <p class="text-gray-500 mt-2">Click "Save Budgets" above to create your spending plan.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
