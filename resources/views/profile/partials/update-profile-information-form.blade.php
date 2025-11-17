<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            {{ __("Update your account's profile information, email address, and business name.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Profile Picture Section --}}
        <div x-data="{ photoPreview: null, photoRemoved: false }">
            {{-- Hidden input to track if the user wants to remove the photo --}}
            <input type="hidden" name="remove_picture" x-bind:value="photoRemoved ? '1' : '0'">
            
            <x-input-label for="profile_picture" :value="__('Profile Picture')" class="dark:text-gray-200" />
            
            {{-- START: SIMPLIFIED DISPLAY LOGIC --}}
            <div class="mt-2">
                @php
                    // Calculate initials and picture status once
                    $name = Auth::user()->name;
                    $nameParts = explode(' ', $name);
                    $initials = count($nameParts) > 1
                        ? strtoupper(substr($nameParts[0], 0, 1) . substr(end($nameParts), 0, 1))
                        : strtoupper(substr($nameParts[0], 0, 2));
                    $hasPicture = (bool) $user->profile_picture;
                @endphp

                {{-- 1. New Photo Preview --}}
                <span x-show="photoPreview" class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                </span>
                
                {{-- 2. Current Photo (Show if it exists AND we're not removing it AND no preview) --}}
                <img x-show="!photoPreview && !photoRemoved && {{ $hasPicture ? 'true' : 'false' }}" 
                     src="{{ $user->profile_picture ? Storage::url($user->profile_picture) : '' }}" 
                     alt="{{ $user->name }}" 
                     class="rounded-full w-20 h-20 object-cover">
                
                {{-- 3. Initials (Show if no preview AND (we ARE removing photo OR we never had one)) --}}
                <div x-show="!photoPreview && (photoRemoved || {{ !$hasPicture ? 'true' : 'false' }})" 
                     class="flex items-center justify-center h-20 w-20 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold text-lg">
                    {{ $initials }}
                </div>
            </div>
            {{-- END: SIMPLIFIED DISPLAY LOGIC --}}

            
            {{-- Hidden File Input --}}
            <input 
                type="file" 
                id="profile_picture" 
                name="profile_picture" 
                class="hidden"
                x-ref="photoInput"
                @change="
                    const file = $event.target.files[0];
                    if (file) {
                        photoRemoved = false; // A new file un-does removal
                        const reader = new FileReader();
                        reader.onload = (e) => { photoPreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                "
            >

            {{-- Button to select a new photo --}}
            <x-secondary-button type="button" class="mt-2" @click="document.getElementById('profile_picture').click()">
                {{ __('Select A New Photo') }}
            </x-secondary-button>

            {{-- Button to remove existing photo (only shows if one exists) --}}
            @if ($user->profile_picture)
                <x-danger-button type="button" class="mt-2" @click="
                    photoRemoved = true;
                    photoPreview = null;
                    $refs.photoInput.value = null;
                ">
                    {{ __('Remove Photo') }}
                </x-danger-button>
            @endif

            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
        </div>
        {{-- END: Profile Picture Section --}}


        <div>
            <x-input-label for="name" :value="__('Name')" class="dark:text-gray-200" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full dark:bg-gray-900 dark:text-white dark:border-gray-600" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="business_name" :value="__('Business Name')" class="dark:text-gray-200" />
            <x-text-input id="business_name" name="business_name" type="text" class="mt-1 block w-full dark:bg-gray-900 dark:text-white dark:border-gray-600" :value="old('business_name', $user->business_name)" autocomplete="organization" />
            <x-input-error class="mt-2" :messages="$errors->get('business_name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="dark:text-gray-200" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full dark:bg-gray-900 dark:text-white dark:border-row-600" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 border-blue-600 focus:ring-blue-500">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
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