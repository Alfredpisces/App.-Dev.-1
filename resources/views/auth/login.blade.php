<x-guest-layout>
    <div class="mb-6 text-center">
        {{-- <x-application-logo class="w-20 h-20 mx-auto text-gray-500" /> --}}
        <h2 class="mt-4 text-2xl font-bold text-gray-900">Welcome to Financial Management System</h2>
        <p class="text-sm text-gray-600">Log in to access your financial command center.</p>
    </div>



    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">
                {{ __('Need an account?') }}
            </a>
            <x-primary-button class="ml-4">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>