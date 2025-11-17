{{-- resources/views/layouts/sidebar.blade.php --}}

<div 
    x-show="sidebarOpen" 
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 bg-gray-600 bg-opacity-75 sm:hidden" 
    @click="sidebarOpen = false"
    x-cloak
></div>

<div class="fixed inset-y-0 left-0 z-40 flex flex-col w-64 transform transition-transform duration-300 ease-in-out 
            bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700
            sm:translate-x-0"
     :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
     x-cloak
>
    
    {{-- Enhanced nav: Explicitly hidden on mobile when sidebar closed, with transition --}}
    <nav 
        class="flex-1 overflow-y-auto pt-4 pb-3 space-y-1" 
        x-show="sidebarOpen || window.innerWidth >= 640"  {{-- Hides nav on mobile when closed; always shows on sm+ --}}
        x-transition:enter="transition-all ease-in-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-x-2"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition-all ease-in-out duration-300"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform -translate-x-2"
    > 
        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.*')">
            {{ __('Sales') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('expenses.index')" :active="request()->routeIs('expenses.*')">
            {{ __('Expenses') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('budgets.index')" :active="request()->routeIs('budgets.*')">
            {{ __('Budgets') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
            {{ __('Reports') }}
        </x-responsive-nav-link>
    </nav>

    {{-- 
      THE ENTIRE MOBILE-ONLY USER MENU THAT WAS HERE IS NOW GONE, 
      BECAUSE IT IS MOVING TO THE TOPBAR. 
    --}}
</div>