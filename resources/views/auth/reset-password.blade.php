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

        /* Adjusting the main instruction text (which previously used gray-600) to be light */
        .auth-card > div:first-child {
            color: #cbd5e1 !important; /* A slightly softer white/light gray */
        }
    </style>

    {{-- 1. CANVAS ELEMENT FOR FINANCE PARTICLES --}}
    <div id="tsparticles"></div>

    <div class="auth-card">
        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold">Set Your New Password</h2>
            <p class="text-sm">Please enter a new, secure password for your account.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <input type="hidden" name="email" value="{{ $request->email ?? old('email') }}" required autofocus />
            
            {{-- We can show the email if we want, but it's not required --}}
            {{-- <div class="mb-4">
                <x-input-label for="email_display" :value="__('Email')" />
                <x-text-input id="email_display" class="block mt-1 w-full" type="email" :value="$request->email ?? old('email')" disabled />
            </div> --}}

            <div class="mt-4">
                <x-input-label for="password" :value="__('New Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button class="w-full bg-blue-600 hover:bg-blue-700 border-blue-600 focus:ring-blue-500">
                    {{ __('Reset Password') }}
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