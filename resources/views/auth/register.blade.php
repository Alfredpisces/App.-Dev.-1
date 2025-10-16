<x-guest-layout>
    <div class="mb-6 text-center">
        {{-- <x-application-logo class="w-20 h-20 mx-auto text-gray-500" /> --}}
        <h2 class="mt-4 text-2xl font-bold text-gray-900">Create Your Financial Hub</h2>
        <p class="text-sm text-gray-600">Join now to take control of your business finances.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <fieldset class="border-t border-gray-200 pt-4">
            <legend class="text-sm font-semibold text-gray-700">Business & Personal Information</legend>
            <div class="mt-4 space-y-4">
                <div>
                    <x-input-label for="name" :value="__('Your Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                </div>

                <div>
                    <x-input-label for="business_name" :value="__('Business or Company Name')" />
                    <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" required />
                </div>
            </div>
        </fieldset>

        <fieldset class="border-t border-gray-200 pt-4 mt-6">
            <legend class="text-sm font-semibold text-gray-700">Account Credentials</legend>
            <div class="mt-4 space-y-4">
                 <div>
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>
        </fieldset>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>