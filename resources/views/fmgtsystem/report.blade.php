<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight printable-hidden">
            Business Insights Center
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded-lg shadow-lg printable-hidden">
                <form method="GET" action="{{ route('reports.generate') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label for="report_type" class="block text-sm font-medium text-gray-700">Report Type</label>
                        <select name="report_type" id="report_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="pnl" @if($report_type == 'pnl') selected @endif>Profit & Loss</option>
                            <option value="cashflow" @if($report_type == 'cashflow') selected @endif>Cash Flow</option>
                            <option value="tax" @if($report_type == 'tax') selected @endif>Tax Summary</option>
                        </select>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $start_date }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $end_date }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-black font-bold py-2 px-3 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                        Generate Report
                    </button>
                </form>
            </div>

            @if(isset($reportData))
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 md:p-8">
                        <div class="flex justify-between items-start printable-hidden">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $reportData['title'] }}</h3>
                                <p class="text-sm text-gray-500">For the period: {{ $reportData['period'] }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('reports.generate', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="text-sm text-gray-600 hover:underline">Export PDF</a>
                                <a href="{{ route('reports.generate', array_merge(request()->query(), ['export' => 'csv'])) }}" class="text-sm text-gray-600 hover:underline">Export CSV</a>
                                <button onclick="window.print()" class="bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-sm hover:bg-gray-300">Print</button>
                            </div>
                        </div>

                        <!-- Printable Report Area -->
                        <div id="printableArea" class="mt-6">
                            <!-- Report Header -->
                            <div class="report-header">
                                <h1 class="text-3xl font-bold text-center">{{ auth()->user()->company_name ?? 'Your Company LLC' }}</h1>
                                <p class="text-center text-gray-600">{{ $reportData['title'] }}</p>
                                <p class="text-center text-sm text-gray-500">For the Period {{ $reportData['period'] }}</p>
                            </div>

                            <!-- Report Body -->
                            <div class="mt-8">
                                @if($report_type == 'pnl')
                                    <table class="w-full report-table">
                                        <thead>
                                            <tr>
                                                <th class="text-left py-2">Account</th>
                                                <th class="text-right py-2">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="font-semibold"><td class="py-3">Revenue</td><td class="text-right">PHP {{ number_format($reportData['revenue']['total'], 2) }}</td></tr>
                                            <tr class="font-semibold"><td class="py-3 pl-4">Operating Expenses</td><td class="text-right"></td></tr>
                                            @foreach($reportData['expenses']['categories'] as $category)
                                                <tr><td class="py-2 pl-8 text-gray-600">{{ $category['name'] }}</td><td class="text-right text-gray-600">PHP {{ number_format($category['amount'], 2) }}</td></tr>
                                            @endforeach
                                            <tr class="font-semibold border-t-2"><td class="py-3">Total Expenses</td><td class="text-right">PHP {{ number_format($reportData['expenses']['total'], 2) }}</td></tr>
                                            <tr class="font-bold text-lg border-t-4 border-double">
                                                <td class="py-4">Net Profit</td><td class="text-right">PHP {{ number_format($reportData['net_profit']['amount'], 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif

                                @if($report_type == 'cashflow')
                                     <table class="w-full report-table">
                                        <tbody>
                                            <tr><td class="py-3">Opening Cash Balance</td><td class="text-right">PHP {{ number_format($reportData['opening_balance'], 2) }}</td></tr>
                                            <tr class="text-green-700"><td class="py-3 pl-4">Cash Inflows (from Sales)</td><td class="text-right">PHP {{ number_format($reportData['inflows'], 2) }}</td></tr>
                                            <tr class="text-red-700"><td class="py-3 pl-4">Cash Outflows (from Expenses)</td><td class="text-right">(PHP {{ number_format($reportData['outflows'], 2) }})</td></tr>
                                            <tr class="font-semibold border-t-2"><td class="py-3">Net Cash Flow</td><td class="text-right">PHP {{ number_format($reportData['net_cash_flow'], 2) }}</td></tr>
                                            <tr class="font-bold text-lg border-t-4 border-double"><td class="py-4">Closing Cash Balance</td><td class="text-right">PHP {{ number_format($reportData['closing_balance'], 2) }}</td></tr>
                                        </tbody>
                                    </table>
                                @endif

                                @if($report_type == 'tax')
                                    <table class="w-full report-table">
                                        <tbody>
                                            <tr><td class="py-3">Total Taxable Revenue</td><td class="text-right">PHP {{ number_format($reportData['taxable_revenue'], 2) }}</td></tr>
                                            <tr><td class="py-3">Total Deductible Expenses</td><td class="text-right">(PHP {{ number_format($reportData['deductible_expenses'], 2) }})</td></tr>
                                            <tr class="font-bold text-lg border-t-4 border-double"><td class="py-4">Estimated Taxable Income</td><td class="text-right">PHP {{ number_format($reportData['estimated_taxable_income'], 2) }}</td></tr>
                                        </tbody>
                                    </table>
                                    <p class="text-xs text-gray-500 mt-6">*This is an estimate for informational purposes only. Please consult with a professional tax advisor.</p>
                                @endif
                            </div>

                             <!-- Report Footer -->
                            <div class="report-footer mt-12 text-center text-xs text-gray-400">
                                <p>Report generated on {{ now()->format('F j, Y, g:i a') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center bg-white p-12 rounded-lg shadow-lg">
                    <h3 class="text-xl font-medium text-gray-800">Generate a report to get started</h3>
                    <p class="text-gray-500 mt-2">Select a report type and a date range above to view your business insights.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .report-table th, .report-table td {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        .report-table thead th {
            border-bottom: 2px solid #000;
        }
        .report-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        @media print {
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
            }
            .report-header {
                border-bottom: 2px solid #000;
                padding-bottom: 1rem;
                margin-bottom: 2rem;
            }
            .report-footer {
                border-top: 1px solid #ccc;
                padding-top: 1rem;
                position: fixed;
                bottom: 0;
                width: 100%;
            }
            /* Ensure colors are simple for printing */
            .text-green-700 { color: #000 !important; }
            .text-red-700 { color: #000 !important; }
        }
    </style>
</x-app-layout>

