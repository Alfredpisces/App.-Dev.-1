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
        <div class="mb-4 text-sm text-gray-400 font-bold">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status (Displays the success message "We have emailed your password reset link!") -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if (session('status'))
            <!-- "Continue to Login" Button (Visible AFTER link is sent) -->
            <div class="flex items-center justify-center mt-6">
                <a href="{{ route('login') }}" class="w-full text-center py-2 px-4 rounded-md text-sm font-semibold text-white bg-green-600 hover:bg-green-700 focus:ring-green-500 transition duration-150 ease-in-out shadow-lg">
                    {{ __('Continue to Login') }}
                </a>
            </div>
        @else
            <!-- Original Form (Only visible if the link hasn't been sent yet) -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- UPDATED: Added "Back to Login" link here -->
                <div class="flex items-center justify-between mt-4">
                    <a class="underline text-sm hover:text-blue-400" href="{{ route('login') }}">
                        {{ __('Back to Login') }}
                    </a>

                    <x-primary-button class="ml-4 bg-blue-600 hover:bg-blue-700 border-blue-600 focus:ring-blue-500">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        @endif
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