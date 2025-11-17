{{-- resources/views/layouts/topbar.blade.php --}}

@php
    $user = Auth::user(); // Get the full user object
@endphp

<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex items-center sm:hidden">
                <button @click="sidebarOpen = ! sidebarOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': sidebarOpen, 'inline-flex': ! sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 flex justify-end">
                
                <div class="flex items-center ms-6">

                    {{-- This is Item 1: Theme Toggle --}}
                    <div class="me-4">
                        <x-theme-toggle />
                    </div>

                    {{-- This is Item 2: Profile Dropdown --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            
                            {{-- 
                              --- THIS IS THE UPDATED BUTTON ---
                              - Sizes are now 24px (h-6 w-6) and 32px (sm:h-8 sm:w-8).
                              - Uses a safe <img> tag to prevent the dropdown from breaking.
                            --And --}} {{-- <-- FIX: Corrected the comment --}}
                            <button class="flex-shrink-0 flex items-center justify-center 
                                           h-6 w-6 sm:h-8 sm:w-8 {{-- <-- 24px (mobile) & 32px (desktop) --}}
                                           rounded-full bg-gray-200 dark:bg-gray-700 
                                           text-gray-700 dark:text-gray-300 
                                           text-sm font-semibold focus:outline-none 
                                           focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 
                                           transition ease-in-out duration-150 overflow-hidden">
                                
                                @if ($user->profile_picture)
                                    {{-- 1. If pic exists, show <img> tag --}}
                                    <img class="h-full w-full object-cover" src="{{ Storage::url($user->profile_picture) }}" alt="{{ $user->name }}">
                                @else
                                    {{-- 2. If no pic, show initials --}}
                                    @php
                                        $name = $user->name;
                                        $nameParts = explode(' ', $name);
                                        $initials = count($nameParts) > 1
                                            ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
                                            : strtoupper(substr($nameParts[0], 0, 2));
                                    @endphp
                                    {{ $initials }}
                                @endif
                            
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="block px-4 py-2 text-xs text-gray-400 sm:hidden">
                                {{ $user->name }}
                            </div>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('about.us')">
                                {{ __('About Us') }}
                            </x-dropdown-link>

                            <hr class="border-gray-200 dark:border-gray-600">
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

        </div>
    </div>
</nav>