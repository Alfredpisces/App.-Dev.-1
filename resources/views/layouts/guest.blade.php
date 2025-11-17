<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-t">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- ALL STYLING IS NOW IN THE LAYOUT --}}
        <style>
            [x-cloak] { display: none !important; }

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

            /* The card wrapper that was in the individual files */
            .auth-card {
                background-color: rgba(18, 22, 32, 0.95) !important; /* Darker navy with transparency, fixed */
                border-radius: 1rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
                padding: 2.5rem;
                border: 1px solid rgba(70, 130, 180, 0.5); /* Subtle blue border for finance touch */
                z-index: 10;
                position: relative;
                width: 100%;
                max-width: 28rem; /* sm:max-w-md */
                margin-top: 1.5rem; /* mt-6 */
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        {{-- 1. CANVAS ELEMENT FOR FINANCE PARTICLES --}}
        <div id="tsparticles"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            {{-- 
                THIS IS NOW THE WRAPPER.
                The old "w-full sm:max-w-md..." div is replaced by this.
            --}}
            <div class="auth-card">
                {{-- The content from login.blade.php, verify.blade.php, etc. goes here --}}
                {{ $slot }}
            </div>

        </div>

        {{-- 2. JAVASCRIPT CALL (now loaded in the layout for all auth pages) --}}
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
    </body>
</html>