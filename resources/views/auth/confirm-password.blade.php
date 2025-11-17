<x-guest-layout>
    {{-- Animated Finance Theme Styling (Fixed Background) --}}
    <style>
        /* Body: Fixed finance dark, overrides any theme */
        body {
            background-color: #121620 !important; /* Fixed dark navy */
            min-height: 100vh;
            position: relative;
        }

        /* Styles for the canvas element: Fixed for full-page coverage during scroll */
        #tsparticles {
            position: fixed;
            width: 100%;
            height: 100vh;
            top: 0;
            left: 0;
            z-index: -1;
            pointer-events: none;
        }

        .auth-card {
            background-color: rgba(18, 22, 32, 0.95) !important; /* Darker navy with transparency, fixed */
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
            padding: 2.5rem;
            border: 1px solid rgba(70, 130, 180, 0.5); /* Subtle blue border for finance touch */
            z-index: 10;
            position: relative;
        }

        /* Ensure all text/labels inside the card are light */
        .auth-card h2, .auth-card p, .auth-card label, .auth-card legend, .auth-card a, .auth-card span, .auth-card div {
            color: #e2e8f0 !important; /* Light text color for readability */
        }
        
        /* Input Fixes: Ensures text is visible (white text) in fixed dark theme */
        .auth-card input {
            color: #ffffff !important; /* TEXT COLOR: Now clearly white */
            background-color: #1a202c !important; /* Deeper dark for input background */
            border-color: #4a5568 !important;
        }
        .auth-card input:focus {
            border-color: #4682B4 !important; /* Blue focus for finance */
            box-shadow: 0 0 0 3px rgba(70, 130, 180, 0.3) !important;
        }

        /* Adjusting the main instruction text */
        .auth-card > div:first-child {
            color: #cbd5e1 !important; /* A slightly softer white/light gray */
        }

        /* UPDATED: Password Toggle Icon Styling (Now Red) */
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 0.75rem; /* 12px */
            transform: translateY(-50%);
            cursor: pointer;
            color: #EF4444; /* red-500 */
        }
        .password-toggle:hover {
            color: #F87171; /* red-400 */
        }
        [x-cloak] { display: none !important; }
    </style>

    {{-- 1. CANVAS ELEMENT FOR FINANCE PARTICLES --}}
    <div id="tsparticles"></div>

    <div class="auth-card">
        <div class="mb-4 text-sm">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            {{-- PEEK PASSWORD TOGGLE (Styled for dark theme) --}}
            <div x-data="{ show: false }">
                <x-input-label for="password" :value="__('Password')" />
                
                {{-- Make parent relative for icon positioning --}}
                <div class="relative mt-1">
                    <x-text-input id="password" class="block w-full"
                                    {{-- Use x-bind:type to avoid Blade error --}}
                                    x-bind:type="show ? 'text' : 'password'"
                                    name="password"
                                    required autocomplete="current-password" />
                    
                    {{-- Updated icon container with new styles --}}
                    <span class="password-toggle" @click="show = !show">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.47 1.252-1.223 2.39-2.122 3.39M15.82 15.82a9 9 0 01-1.33 1.33M9.18 9.18a9 9 0 011.33-1.33"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .47-1.252 1.223-2.39 2.122-3.39M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 3.98l16.04 16.04M10.99 10.99a3 3 0 113.82 3.82m-3.82-3.82L10.99 10.99z"></path></svg>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-4">
                <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-blue-600 focus:ring-blue-500">
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    {{-- 2. JAVASCRIPT CALL (updated for finance particles, with retry) --}}
    <script>
        function initParticles() {
            if (typeof window.loadFinanceParticles === 'function') {
                window.loadFinanceParticles('tsparticles');
            } else {
                // Retry after 500ms if function not ready
                setTimeout(initParticles, 500);
            }
        }

        document.addEventListener('DOMContentLoaded', initParticles);
    </script>
</x-guest-layout>