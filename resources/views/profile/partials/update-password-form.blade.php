<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div x-data="{ show: false }">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="dark:text-gray-200" />
            <div class="relative mt-1">
                <x-text-input id="update_password_current_password" name="current_password" 
                                x-bind:type="show ? 'text' : 'password'"
                                class="block w-full dark:bg-gray-900 dark:text-white dark:border-gray-600" 
                                autocomplete="current-password" />
                
                <span @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-red-500 hover:text-red-400">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.47 1.252-1.223 2.39-2.122 3.39M15.82 15.82a9 9 0 01-1.33 1.33M9.18 9.18a9 9 0 011.33-1.33"></path></svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .47-1.252 1.223-2.39 2.122-3.39M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 3.98l16.04 16.04M10.99 10.99a3 3 0 113.82 3.82m-3.82-3.82L10.99 10.99z"></path></svg>
                </span>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <x-input-label for="update_password_password" :value="__('New Password')" class="dark:text-gray-200" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password" name="password" 
                                x-bind:type="show ? 'text' : 'password'"
                                class="block w-full dark:bg-gray-900 dark:text-white dark:border-gray-600" 
                                autocomplete="new-password" />

                <span @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-red-500 hover:text-red-400">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.47 1.252-1.223 2.39-2.122 3.39M15.82 15.82a9 9 0 01-1.33 1.33M9.18 9.18a9 9 0 011.33-1.33"></path></svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .47-1.252 1.223-2.39 2.122-3.39M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 3.98l16.04 16.04M10.99 10.99a3 3 0 113.82 3.82m-3.82-3.82L10.99 10.99z"></path></svg>
                </span>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div x-data="{ show: false }">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="dark:text-gray-200" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" 
                                x-bind:type="show ? 'text' : 'password'"
                                class="block w-full dark:bg-gray-900 dark:text-white dark:border-gray-600" 
                                autocomplete="new-password" />

                <span @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-red-500 hover:text-red-400">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.47 1.252-1.223 2.39-2.122 3.39M15.82 15.82a9 9 0 01-1.33 1.33M9.18 9.18a9 9 0 011.33-1.33"></path></svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .47-1.252 1.223-2.39 2.122-3.39M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 3.98l16.04 16.04M10.99 10.99a3 3 0 113.82 3.82m-3.82-3.82L10.99 10.99z"></path></svg>
                </span>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-blue-600 focus:ring-blue-500">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-300"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>