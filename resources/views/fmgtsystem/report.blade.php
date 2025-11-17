<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight printable-hidden">
            Business Insights Center
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Controls Section --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg printable-hidden">
                <form method="GET" action="{{ route('reports.generate') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Report Type</label>
                        <select name="report_type" id="report_type" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pnl" @if($report_type == 'pnl') selected @endif>Profit & Loss</option>
                            <option value="cashflow" @if($report_type == 'cashflow') selected @endif>Cash Flow</option>
                            <option value="tax" @if($report_type == 'tax') selected @endif>Tax Summary</option>
                        </select>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $start_date }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $end_date }}" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-3 rounded-lg shadow-md hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Generate Report
                    </button>
                </form>
            </div>

            {{-- Report Display Area --}}
            @if(isset($reportData))
                <div id="reportContainer" class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 md:p-8">
                        {{-- Report Actions (Print/Export) --}}
                        <div class="flex justify-between items-start printable-hidden mb-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $reportData['title'] }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">For the period: {{ $reportData['period'] }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('reports.generate', array_merge(request()->query(), ['export' => 'csv'])) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Export CSV</a>
                                <button onclick="window.print()" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-300 dark:hover:bg-gray-600">Print</button>
                            </div>
                        </div>

                        {{-- Printable Report Area --}}
                        <div id="printableArea" class="mt-6">
                            
                            {{-- Report Header (for print) --}}
                            <div class="report-header">
                                <h1 class="text-3xl font-bold text-center">{{ auth()->user()->business_name ?? auth()->user()->name }}</h1>
                                <p class="text-center text-xl">{{ $reportData['title'] }}</p>
                                <p class="text-center text-sm text-gray-500 dark:text-gray-400">For the Period {{ $reportData['period'] }}</p>
                            </div>

                            {{-- NEW: Key Figure Summary --}}
                            @php
                                $keyFigure = 0;
                                $keyLabel = '';
                                if ($report_type == 'pnl') {
                                    $keyFigure = $reportData['net_profit']['amount'];
                                    $keyLabel = 'Net Profit';
                                } elseif ($report_type == 'cashflow') {
                                    $keyFigure = $reportData['closing_balance'];
                                    $keyLabel = 'Closing Cash Balance';
                                } elseif ($report_type == 'tax') {
                                    $keyFigure = $reportData['estimated_taxable_income'];
                                    $keyLabel = 'Estimated Taxable Income';
                                }
                                $keyColorClass = $keyFigure >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                            @endphp
                            
                            <div class="my-8 text-center">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $keyLabel }}</h4>
                                <p class="text-5xl font-extrabold {{ $keyColorClass }} mt-2">PHP {{ number_format($keyFigure, 2) }}</p>
                            </div>


                            {{-- Report Data Tables --}}
                            <div class="mt-8 text-gray-900 dark:text-gray-200">
                                @if($report_type == 'pnl')
                                    <div class="overflow-x-auto">
                                        <table class="w-full report-table">
                                            <thead>
                                                <tr>
                                                    <th>Account</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="section-header">
                                                    <td>Revenue</td>
                                                    <td>PHP {{ number_format($reportData['revenue']['total'], 2) }}</td>
                                                </tr>
                                                <tr class="section-header">
                                                    <td>Operating Expenses</td>
                                                    <td></td>
                                                </tr>
                                                @forelse($reportData['expenses']['categories'] as $category)
                                                    <tr class="item-row">
                                                        <td>{{ $category['name'] }}</td>
                                                        <td>PHP {{ number_format($category['amount'], 2) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr class="item-row"><td class="text-gray-500">No expenses recorded.</td><td>PHP 0.00</td></tr>
                                                @endforelse
                                                <tr class="section-total">
                                                    <td>Total Expenses</td>
                                                    <td>PHP {{ number_format($reportData['expenses']['total'], 2) }}</td>
                                                </tr>
                                                <tr class="grand-total">
                                                    <td>Net Profit</td>
                                                    <td class="{{ $reportData['net_profit']['amount'] >= 0 ? 'text-green-600' : 'text-red-600' }}">PHP {{ number_format($reportData['net_profit']['amount'], 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if($report_type == 'cashflow')
                                     <div class="overflow-x-auto">
                                         <table class="w-full report-table">
                                            <tbody>
                                                <tr class="item-row">
                                                    <td>Opening Cash Balance</td>
                                                    <td>PHP {{ number_format($reportData['opening_balance'], 2) }}</td>
                                                </tr>
                                                <tr class="item-row text-green-700 dark:text-green-400">
                                                    <td>Cash Inflows (from Sales)</td>
                                                    <td>PHP {{ number_format($reportData['inflows'], 2) }}</td>
                                                </tr>
                                                <tr class="item-row text-red-700 dark:text-red-400">
                                                    <td>Cash Outflows (from Expenses)</td>
                                                    <td>(PHP {{ number_format($reportData['outflows'], 2) }})</td>
                                                </tr>
                                                <tr class="section-total">
                                                    <td>Net Cash Flow</td>
                                                    <td class="{{ $reportData['net_cash_flow'] >= 0 ? 'text-green-600' : 'text-red-600' }}">PHP {{ number_format($reportData['net_cash_flow'], 2) }}</td>
                                                </tr>
                                                <tr class="grand-total">
                                                    <td>Closing Cash Balance</td>
                                                    <td class="{{ $reportData['closing_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">PHP {{ number_format($reportData['closing_balance'], 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                @if($report_type == 'tax')
                                    <div class="overflow-x-auto">
                                        <table class="w-full report-table">
                                            <tbody>
                                                <tr class="item-row">
                                                    <td>Total Taxable Revenue</td>
                                                    <td>PHP {{ number_format($reportData['taxable_revenue'], 2) }}</td>
                                                </tr>
                                                <tr class="item-row">
                                                    <td>Total Deductible Expenses</td>
                                                    <td>(PHP {{ number_format($reportData['deductible_expenses'], 2) }})</td>
                                                </tr>
                                                <tr class="grand-total">
                                                    <td>Estimated Taxable Income</td>
                                                    <td class="{{ $reportData['estimated_taxable_income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">PHP {{ number_format($reportData['estimated_taxable_income'], 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-6 print-disclaimer">*This is an estimate for informational purposes only. Please consult with a professional tax advisor.</p>
                                @endif
                            </div>

                            {{-- Report Footer (for print) --}}
                            <div class="report-footer mt-12 text-center text-xs text-gray-400">
                                <p>Report generated on {{ now()->format('F j, Y, g:i a') }} by {{ Auth::user()->name }}</p>
                                <p>{{ auth()->user()->business_name ?? 'Financial Management System' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Placeholder when no report is generated --}}
                <div class="text-center bg-white dark:bg-gray-800 p-12 rounded-lg shadow-lg">
                    <h3 class="text-xl font-medium text-gray-800 dark:text-gray-100">Generate a report to get started</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Select a report type and a date range above to view your business insights.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- New CSS for professional tables and printing --}}
    <style>
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-table th, .report-table td {
            padding: 0.75rem 1rem;
            text-align: left;
        }
        .report-table td:last-child, .report-table th:last-child {
            text-align: right;
        }
        .report-table thead th {
            border-bottom: 2px solid #374151; /* gray-700 */
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280; /* gray-500 */
        }
        .report-table .item-row td {
            border-bottom: 1px solid #e5e7eb; /* gray-200 */
        }
        .report-table .section-header td {
            font-weight: 600;
            padding-top: 1.25rem;
            padding-left: 0.5rem;
            color: #111827; /* gray-900 */
        }
        .report-table .section-total td {
            font-weight: 600;
            padding-top: 0.75rem;
            border-top: 1px solid #9ca3af; /* gray-400 */
        }
        .report-table .grand-total td {
            font-weight: 700;
            font-size: 1.125rem;
            padding-top: 1rem;
            border-top: 2px solid #111827; /* gray-900 */
            border-bottom: 2px solid #111827; /* gray-900 */
        }

        /* Dark Mode Table Styles */
        .dark .report-table thead th {
            border-color: #9ca3af; /* gray-400 */
            color: #9ca3af; /* gray-400 */
        }
        .dark .report-table .item-row td {
            border-color: #374151; /* gray-700 */
        }
        .dark .report-table .section-header td {
            color: #f9fafb; /* gray-50 */
        }
        .dark .report-table .section-total td {
            border-color: #6b7280; /* gray-500 */
        }
        .dark .report-table .grand-total td {
            border-color: #f9fafb; /* gray-50 */
        }
        .dark .report-table .grand-total .text-green-600 { color: #34d399 !important; }
        .dark .report-table .grand-total .text-red-600 { color: #f87171 !important; }

        /* Print Styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            body * {
                visibility: hidden;
            }
            .printable-hidden {
                display: none !important;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            #reportContainer {
                box-shadow: none !important;
                border: none !important;
            }
            .report-header, .report-footer {
                display: block !important;
            }
            .report-footer {
                position: fixed;
                bottom: 20px;
                width: 100%;
            }
            .print-disclaimer {
                display: block !important;
            }
            
            /* Reset all colors for print */
            .report-table th, .report-table td, .report-header *, .report-footer *, p, h1, h3, h4 {
                color: black !important;
                border-color: #ddd !important;
            }
            .text-green-600 { color: #059669 !important; }
            .text-red-600 { color: #dc2626 !important; }
            .text-green-700 { color: #047857 !important; }
            .text-red-700 { color: #b91c1c !important; }
        }
    </style>
</x-app-layout>