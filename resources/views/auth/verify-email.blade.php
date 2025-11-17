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
        
        /* Adjusting the main instruction text */
        .auth-card > div:first-child {
            color: #cbd5e1 !important; /* A slightly softer white/light gray */
        }

        /* Status message styling */
        .auth-card .text-green-600 {
            color: #34D399 !important; /* Brighter green for dark BG */
        }

        /* Logout button styling */
        .auth-card .logout-button {
            color: #cbd5e1 !important;
            text-decoration: underline;
        }
        .auth-card .logout-button:hover {
            color: #4682B4 !important; /* Finance blue hover */
        }

    </style>

    {{-- 1. CANVAS ELEMENT FOR FINANCE PARTICLES --}}
    <div id="tsparticles"></div>

    <div class="auth-card">
        <div class="mb-4 text-sm font-medium">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-blue-600 focus:ring-blue-500">
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="logout-button text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
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