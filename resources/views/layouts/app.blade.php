<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Financial Manager') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-900 via-teal-800 to-emerald-700 font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-md p-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-12 h-12 bg-black-200 rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-green-800">Financial Management System</span> <!-- Logo Placeholder -->
                        </div>
                    </div>
                </div>
                <!-- Navigation Links -->
                @include('layouts.navigation')
                <!-- Logout Button -->
                @auth
                    <div class="ml-4 flex items-center">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white font-medium py-2 px-4 rounded-lg transition duration-200" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-primary-button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </div>
</body>
</html>