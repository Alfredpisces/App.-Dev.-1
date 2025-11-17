<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('About This System') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Introduction Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <h3 class="text-3xl font-bold mb-4 text-gray-900 dark:text-gray-100">Empowering Your Business Finances</h3>
                    <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                        Welcome to your Financial Management System, a comprehensive tool designed to give you complete control over your financial operations. Our platform simplifies complex financial tasks, allowing you to focus on what truly matters: growing your business.
                    </p>
                </div>
            </div>

            <!-- Key Features Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">Key Features</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Sales -->
                        <div class="flex items-start">
                            <div class="shrink-0">
                                <span class="flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/50">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0l.879-.659M12 6l-3.732 3.732m6.732-3.732L12 6m6.732 3.732L12 6m-3.732 3.732l6.732 0" />
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Sales & Invoicing</h4>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Easily create and send invoices, track sales, and monitor outstanding and overdue payments.</p>
                            </div>
                        </div>

                        <!-- Expenses -->
                        <div class="flex items-start">
                            <div class="shrink-0">
                                <span class="flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-900/50">
                                    <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Expense Tracking</h4>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Log every bill and expense, attach receipts, and categorize spending to see where your money goes.</p>
                            </div>
                        </div>

                        <!-- Budgets -->
                        <div class="flex items-start">
                            <div class="shrink-0">
                                <span class="flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/50">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Budget Management</h4>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Set monthly budgets for different categories and get alerts to prevent overspending.</p>
                            </div>
                        </div>

                        <!-- Reports -->
                        <div class="flex items-start">
                            <div class="shrink-0">
                                <span class="flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50">
                                    <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Insightful Reports</h4>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">Generate P&L, Cash Flow, and Tax Summary reports with one click to make data-driven decisions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mission & Developer Note -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Our Mission -->
                <div class="md:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Our Mission</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                            Our mission is to provide an intuitive, powerful, and accessible system for managing sales, tracking expenses, setting budgets, and generating insightful reports. We believe that with the right tools, any business can achieve financial clarity and make data-driven decisions with confidence.
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Whether you are invoicing clients, monitoring cash flow, or planning for the future, our system is here to support you every step of the way.
                        </p>
                    </div>
                </div>

                <!-- Meet the Developer -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Meet the Developer</h3>
                        <div class="flex items-center">
                            <div class="rounded-full bg-gray-200 dark:bg-gray-700 h-16 w-16 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-2.144M9 13H5a2 2 0 00-2 2v5a2 2 0 002 2h5m-1-14v-2a3 3 0 00-6 0v2H4a2 2 0 00-2 2v5a2 2 0 002 2h5M19 16v-2a3 3 0 00-6 0v2H9a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2h-2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">John Alfred A. Moncano</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Lead Developer</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-4">
                            This system was built with care to help you succeed.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
