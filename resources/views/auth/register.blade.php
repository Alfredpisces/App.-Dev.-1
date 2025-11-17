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

        .auth-card h2, .auth-card p, .auth-card label, .auth-card legend, .auth-card a, .auth-card span, .auth-card div {
            color: #e2e8f0 !important;
        }

        /* Input Fixes: Ensures text is visible in fixed dark theme */
        .auth-card input, .auth-card select {
            color: #ffffff !important;
            background-color: #1a202c !important; /* Deeper dark for inputs */
            border-color: #4a5568 !important;
        }
        .auth-card input:focus, .auth-card select:focus {
            border-color: #4682B4 !important; /* Blue focus for finance */
            box-shadow: 0 0 0 3px rgba(70, 130, 180, 0.3) !important;
        }
        
        /* Timezone select option color */
        .auth-card select option {
             background-color: #1a202c !important;
             color: #ffffff !important;
        }

        /* Logo container to ensure background match */
        .logo-container {
            background-color: #121620;
            padding: 0.5rem;
            display: inline-block;
            border-radius: 0.5rem;
        }

        .auth-card fieldset {
            border-color: #4a5568 !important;
        }
        
        /* Password Toggle Icon Styling (Red) */
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

        /* --- NEW STYLES FOR UPLOADER --- */
        .file-input-label {
            display: inline-block;
            cursor: pointer;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #e2e8f0;
            background-color: #2d3748; /* gray-700 */
            border: 1px solid #4a5568;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .file-input-label:hover {
            background-color: #4a5568; /* gray-600 */
        }
        .photo-preview {
            width: 6rem; /* w-24 */
            height: 6rem; /* h-24 */
            border-radius: 9999px; /* rounded-full */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border: 2px solid #4a5568;
            background-color: #1a202c;
        }

    </style>

    {{-- 1. CANVAS ELEMENT FOR FINANCE PARTICLES --}}
    <div id="tsparticles"></div>

    <div class="auth-card">
        <div class="mb-6 text-center">
            {{-- LOGO CONTAINER AND IMAGE --}}
            <div class="logo-container mx-auto w-24 h-24 mb-4 flex items-center justify-center">
                <img src="{{ asset('images/financial_logo.png') }}" alt="Financial Management Logo" class="w-full h-full object-contain" />
            </div>

            <h2 class="mt-4 text-2xl font-bold">Create Your Financial Hub</h2>
            <p class="text-sm">Join now to take control of your business finances.</p>
        </div>

        {{-- ADDED enctype="multipart/form-data" --}}
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <fieldset class="border-t pt-4">
                <legend class="text-sm font-semibold">Business & Personal Information</legend>
                <div class="mt-4 space-y-4">
                    <div>
                        <x-input-label for="name" :value="__('Your Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="business_name" :value="__('Business or Company Name (Optional)')" />
                        <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" autocomplete="organization" />
                        <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
                    </div>

                    {{-- Timezone Selection Field --}}
                    <div>
                        <x-input-label for="timezone" :value="__('Your Timezone')" />
                        <select id="timezone" name="timezone" required class="block mt-1 w-full rounded-md shadow-sm">
                            <option value="" disabled selected>Select your local timezone...</option>
                            <optgroup label="Africa">
                                <option value="Africa/Cairo" @if(old('timezone') == 'Africa/Cairo') selected @endif>Cairo (GMT+2)</option>
                                <option value="Africa/Lagos" @if(old('timezone') == 'Africa/Lagos') selected @endif>Lagos (GMT+1)</option>
                            </optgroup>
                            <optgroup label="America">
                                <option value="America/New_York" @if(old('timezone') == 'America/New_York') selected @endif>New York (GMT-5)</option>
                                <option value="America/Chicago" @if(old('timezone') == 'America/Chicago') selected @endif>Chicago (GMT-6)</option>
                                <option value="America/Los_Angeles" @if(old('timezone') == 'America/Los_Angeles') selected @endif>Los Angeles (GMT-8)</option>
                            </optgroup>
                            <optgroup label="Asia">
                                <option value="Asia/Dubai" @if(old('timezone') == 'Asia/Dubai') selected @endif>Dubai (GMT+4)</option>
                                <option value="Asia/Kolkata" @if(old('timezone') == 'Asia/Kolkata') selected @endif>Kolkata (GMT+5:30)</option>
                                <option value="Asia/Manila" @if(old('timezone') == 'Asia/Manila') selected @endif>Manila (GMT+8)</option>
                                <option value="Asia/Shanghai" @if(old('timezone') == 'Asia/Shanghai') selected @endif>Shanghai (GMT+8)</option>
                                <option value="Asia/Tokyo" @if(old('timezone') == 'Asia/Tokyo') selected @endif>Tokyo (GMT+9)</option>
                            </optgroup>
                            <optgroup label="Europe">
                                <option value="Europe/London" @if(old('timezone') == 'Europe/London') selected @endif>London (GMT+0)</option>
                                <option value="Europe/Paris" @if(old('timezone') == 'Europe/Paris') selected @endif>Paris (GMT+1)</option>
                                <option value="Europe/Moscow" @if(old('timezone') == 'Europe/Moscow') selected @endif>Moscow (GMT+3)</option>
                            </optgroup>
                            <optgroup label="Australia">
                                <option value="Australia/Sydney" @if(old('timezone') == 'Australia/Sydney') selected @endif>Sydney (GMT+11)</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="UTC" @if(old('timezone') == 'UTC') selected @endif>UTC (GMT+0)</option>
                            </optgroup>
                        </select>
                        <x-input-error :messages="$errors->get('timezone')" class="mt-2" />
                    </div>
                </div>
            </fieldset>

            {{-- --- NEW: PROFILE PICTURE UPLOADER --- --}}
            <fieldset class="border-t pt-4 mt-6" x-data="{ photoPreview: null }">
                <legend class="text-sm font-semibold">Profile Picture (Optional)</legend>
                <div class="mt-4 flex items-center space-x-6">
                    <div class="photo-preview"
                         x-show="photoPreview"
                         x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </div>
                    <div class="photo-preview flex items-center justify-center text-gray-400" x-show="!photoPreview">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenod"></path></svg>
                    </div>

                    <div>
                        <input 
                            type="file" 
                            name="profile_picture" 
                            id="profile_picture" 
                            class="hidden"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => { photoPreview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            "
                        >
                        <label for="profile_picture" class="file-input-label">
                            Select a Photo
                        </label>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
            </fieldset>

            <fieldset class="border-t pt-4 mt-6">
                <legend class="text-sm font-semibold">Account Credentials</legend>
                <div class="mt-4 space-y-4">
                     <div>
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div x-data="{ show: false }">
                        <x-input-label for="password" :value="__('Password')" />
                        <div class="relative mt-1">
                            <x-text-input id="password" class="block w-full"
                                            x-bind:type="show ? 'text' : 'password'"
                                            name="password" required autocomplete="new-password" />
                            
                            <span class="password-toggle" @click="show = !show">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.47 1.252-1.223 2.39-2.122 3.39M15.82 15.82a9 9 0 01-1.33 1.33M9.18 9.18a9 9 0 011.33-1.33"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .47-1.252 1.223-2.39 2.122-3.39M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 3.98l16.04 16.04M10.99 10.99a3 3 0 113.82 3.82m-3.82-3.82L10.99 10.99z"></path></svg>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div x-data="{ show: false }">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <div class="relative mt-1">
                            <x-text-input id="password_confirmation" class="block w-full"
                                            x-bind:type="show ? 'text' : 'password'"
                                            name="password_confirmation" required autocomplete="new-password" />
                            
                            <span class="password-toggle" @click="show = !show">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.47 1.252-1.223 2.39-2.122 3.39M15.82 15.82a9 9 0 01-1.33 1.33M9.18 9.18a9 9 0 011.33-1.33"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .47-1.252 1.223-2.39 2.122-3.39M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 3.98l16.04 16.04M10.99 10.99a3 3 0 113.82 3.82m-3.82-3.82L10.99 10.99z"></path></svg>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>
            </fieldset>

            <div class="flex items-center justify-end mt-8">
                <a class="underline text-sm hover:text-blue-400" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
                <x-primary-button class="ml-4 bg-blue-600 hover:bg-blue-700 border-blue-600 focus:ring-blue-500">
                    {{ __('Register') }}
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