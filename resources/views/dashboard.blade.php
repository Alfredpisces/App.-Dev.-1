<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ auth()->user()->business_name }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
    <?php
        // Use the user's stored timezone, falling back to app config or UTC
        $userTimezone = auth()->user()->timezone ?? config('app.timezone') ?? 'UTC';
        
        // Create a Carbon instance based on the user's timezone
        $now = now()->setTimezone($userTimezone);

        // Calculate the current hour in the user's timezone
        $hour = (int)$now->format('H');
        $greeting = 'Hello'; // Default greeting

        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good morning';
        } elseif ($hour >= 12 && $hour < 17) {
            $greeting = 'Good afternoon';
        } elseif ($hour >= 17 || $hour < 5) { // 5 PM to 5 AM
            $greeting = 'Good evening';
        }
    ?>
    Financial Command Center | {{ $greeting }}, {{ auth()->user()->name }}. It's {{ $now->format('l, F j, Y') }}.
</p>
            </div>
            <form method="GET" action="{{ route('dashboard') }}">
                <select name="period" onchange="this.form.submit()" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="month" @if(request('period') == 'month') selected @endif>This Month</option>
                    <option value="quarter" @if(request('period') == 'quarter') selected @endif>This Quarter</option>
                    <option value="year" @if(request('period') == 'year') selected @endif>This Year</option>
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if(isset($actionItems) && count($actionItems) > 0)
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Action Center
                </h3>
                <ul class="space-y-3">
                    @foreach($actionItems as $item)
                    <li class="flex items-center justify-between p-3 bg-{{ $item['color'] }}-50 dark:bg-gray-700/50 rounded-md border-l-4 border-{{ $item['color'] }}-400">
                        <div>
                            <p class="font-medium text-{{ $item['color'] }}-800 dark:text-{{ $item['color'] }}-300">{{ $item['title'] }}</p>
                            <p class="text-sm text-{{ $item['color'] }}-600 dark:text-{{ $item['color'] }}-400">{{ $item['description'] }}</p>
                        </div>
                        <a href="{{ $item['link'] }}" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">View</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Profit & Loss ({{ ucfirst(request('period', 'month')) }})</h4>
                    <p class="text-3xl font-bold mt-2 {{ $snapshot['net_profit'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        PHP {{ number_format($snapshot['net_profit'], 2) }}
                    </p>
                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        (₱{{ number_format($snapshot['revenue'], 0) }} Rev - ₱{{ number_format($snapshot['expenses'], 0) }} Exp)
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Accounts Receivable</h4>
                    <p class="text-3xl font-bold mt-2 text-blue-600 dark:text-blue-400">
                        PHP {{ number_format($ar['total_due'], 2) }}
                    </p>
                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        ({{ $ar['overdue_count'] }} invoices overdue)
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Cash Balance</h4>
                    <p class="text-3xl font-bold mt-2 text-indigo-600 dark:text-indigo-400">
                        PHP {{ number_format($cashBalance, 2) }}
                    </p>
                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        Across all accounts
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                <div class="lg:col-span-3 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">30-Day Cash Flow Forecast</h3>
                    <div class="relative h-96">
                        <canvas id="cashFlowChart"></canvas>
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Est. Quarterly Tax Due</h4>
                        <p class="text-3xl font-bold mt-2 text-orange-600 dark:text-orange-400">PHP {{ number_format($taxEstimate, 2) }}</p>
                    </div>
                     <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md text-center">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Savings Rate ({{ ucfirst(request('period', 'month')) }})</h4>
                        <p class="text-3xl font-bold mt-2 text-teal-600 dark:text-teal-400">{{ number_format($savingsRate, 1) }}%</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                 <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Income vs. Expense Trend</h3>
                    <div class="relative h-96">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Expense Breakdown</h3>
                    <div class="relative h-96">
                        <canvas id="expenseDonutChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
            const labelColor = isDarkMode ? '#d1d5db' : '#6b7280'; // gray-300 / gray-500
            const legendColor = isDarkMode ? '#f9fafb' : '#374151'; // gray-50 / gray-700
            
            Chart.defaults.color = labelColor;

            const cashFlowCtx = document.getElementById('cashFlowChart')?.getContext('2d');
            if (cashFlowCtx) {
                new Chart(cashFlowCtx, {
                    type: 'line', 
                    data: {
                        labels: @json($cashFlowData['labels'] ?? []),
                        datasets: [
                            {
                                label: 'Projected Cash Balance',
                                data: @json($cashFlowData['balance'] ?? []),
                                type: 'line', 
                                borderColor: 'rgba(99, 102, 241, 1)', // Indigo-500
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                fill: true, 
                                tension: 0.4,
                            }
                        ]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        scales: { 
                            y: { 
                                beginAtZero: true,
                                grid: { color: gridColor } 
                            }, 
                            x: { 
                                grid: { color: gridColor } 
                            } 
                        }, 
                        plugins: { 
                            legend: { labels: { color: legendColor } }
                        }
                    }
                });
            }

            const monthlyTrendCtx = document.getElementById('monthlyTrendChart')?.getContext('2d');
            
            if (monthlyTrendCtx) { 
                new Chart(monthlyTrendCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($monthlyTrend['labels'] ?? []),
                        datasets: [
                            { label: 'Income', data: @json($monthlyTrend['income'] ?? []), backgroundColor: 'rgba(16, 185, 129, 0.7)' }, // Emerald-500
                            { label: 'Expenses', data: @json($monthlyTrend['expenses'] ?? []), backgroundColor: 'rgba(249, 115, 22, 0.7)' } // Orange-500
                        ]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, grid: { color: gridColor } }, x: { grid: { color: gridColor } } }, plugins: { legend: { labels: { color: legendColor } } } }
                });
            }

            // --- THIS IS THE FIX ---
            // Changed getContext('d') to getContext('2d')
            const expenseDonutCtx = document.getElementById('expenseDonutChart')?.getContext('2d'); 
            if (expenseDonutCtx) {
                new Chart(expenseDonutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($expenseBreakdown['labels'] ?? []),
                        datasets: [{
                            data: @json($expenseBreakdown['data'] ?? []),
                            backgroundColor: ['#d946ef', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#8b5cf6'],
                            borderColor: isDarkMode ? '#1f2937' : '#ffffff' // gray-800 / white
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { color: legendColor } } } }
                });
            }
        });
    </script>
</x-app-layout>