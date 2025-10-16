<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-yellow-300 bg-gradient-to-r from-purple-800 via-indigo-900 to-purple-800 p-2 rounded-lg shadow-lg animate-pulse">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-teal-100 via-green-50 to-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Welcome Section -->
            <div class="bg-gradient-to-br from-purple-900 via-indigo-800 to-blue-700 rounded-2xl shadow-2xl p-10 mb-8 relative overflow-hidden transform hover:scale-105 transition duration-500 ease-in-out animate-fade-in-up">
                <div class="absolute top-0 right-0 w-40 h-40 bg-yellow-400 opacity-30 rounded-full -mt-20 -mr-20 animate-spin-slow"></div>
                <div class="absolute bottom-0 left-0 w-60 h-60 bg-green-300 opacity-20 rounded-full -mb-20 -ml-20 animate-pulse"></div>
                <div class="relative z-10">
                    @php
                        $user = auth()->user();
                        $displayName = $user 
                            ? ($user->business_name ?? $user->name ?? 'New User') 
                            : 'Guest';
                        $hour = now()->hour;
                        $greeting = ($hour < 12) ? 'Good Morning' : (($hour < 17) ? 'Good Afternoon' : 'Good Evening');
                    @endphp
                    <h1 class="text-5xl md:text-6xl font-extrabold mb-6 text-yellow-300 drop-shadow-2xl animate-text-glow">
                        {{ $greeting }} to FMS, {{ $displayName }}!
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-green-100 drop-shadow-xl">
    Hey there! It’s {{ now('Asia/Manila')->format('g:i A') }} on {{ now('Asia/Manila')->format('l, F d, Y') }} (PST). Dive into FMS—your ultimate financial powerhouse built with cutting-edge Laravel tech!
</p>
                    <div class="flex flex-col md:flex-row justify-center items-center space-y-4 md:space-y-0 md:space-x-6">
                        <!-- Dashboard Button: Inline Styles for Reliability -->
                        <a href="{{ route('dashboard') }}" 
                           role="button" tabindex="0"
                           style="
                               background: linear-gradient(to right, #f59e0b, #ea580c); /* Amber to Orange */
                               background-color: #f59e0b; /* Fallback */
                               color: white;
                               font-weight: bold;
                               padding: 1rem 2rem;
                               border-radius: 9999px; /* Full rounded */
                               box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
                               text-decoration: none;
                               display: inline-block;
                               min-width: 200px;
                               text-align: center;
                               font-size: 1.1rem;
                               text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* Contrast for white text */
                               transition: all 0.3s ease;
                           "
                           onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 15px 35px -5px rgba(245, 158, 11, 0.6)'; this.style.background='linear-gradient(to right, #ea580c, #dc2626)';"
                           onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 10px 25px -5px rgba(0, 0, 0, 0.3)'; this.style.background='linear-gradient(to right, #f59e0b, #ea580c)';"
                           onfocus="this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(245, 158, 11, 0.5), 0 10px 25px -5px rgba(0, 0, 0, 0.3)';"
                           onblur="this.style.boxShadow='0 10px 25px -5px rgba(0, 0, 0, 0.3)';">
                            🚀 Access Your Dashboard
                        </a>

                        <!-- Profile Button: Inline Styles for Reliability -->
                        <a href="{{ route('profile.edit') }}" 
                           role="button" tabindex="0"
                           style="
                               background: linear-gradient(to right, #10b981, #0d9488); /* Emerald to Teal */
                               background-color: #10b981; /* Fallback */
                               color: white;
                               font-weight: bold;
                               padding: 1rem 2rem;
                               border-radius: 9999px; /* Full rounded */
                               box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
                               text-decoration: none;
                               display: inline-block;
                               min-width: 200px;
                               text-align: center;
                               font-size: 1.1rem;
                               text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); /* Contrast for white text */
                               transition: all 0.3s ease;
                           "
                           onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 15px 35px -5px rgba(16, 185, 129, 0.6)'; this.style.background='linear-gradient(to right, #0d9488, #0891b2)';"
                           onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 10px 25px -5px rgba(0, 0, 0, 0.3)'; this.style.background='linear-gradient(to right, #10b981, #0d9488)';"
                           onfocus="this.style.outline='none'; this.style.boxShadow='0 0 0 3px rgba(16, 185, 129, 0.5), 0 10px 25px -5px rgba(0, 0, 0, 0.3)';"
                           onblur="this.style.boxShadow='0 10px 25px -5px rgba(0, 0, 0, 0.3)';">
                            🎨 Update Your Profile
                        </a>
                    </div>
                    <div class="mt-6 text-lg text-yellow-200 animate-bounce drop-shadow-xl">
                        You’re live! Next financial check-in: {{ now()->addMonth()->format('F d, Y') }}.
                    </div>
                </div>
            </div>

            <!-- Purpose Section (Kept Tailwind for simplicity, but works without) -->
            <div class="bg-white rounded-2xl p-8 shadow-2xl mb-8 transform hover:-translate-y-2 transition duration-300">
                <h3 class="text-3xl font-bold text-indigo-800 mb-6 text-center underline decoration-wavy decoration-green-400">
                    Why FMS Rocks
                </h3>
                <p class="text-lg text-gray-700 mb-4 text-center leading-relaxed">
                    FMS is your financial superhero, blending seamless income tracking, ninja-level expense management, epic budget planning, slick invoice handling, and mind-blowing reports—all in one spot!
                </p>
                <p class="text-lg text-gray-700 text-center leading-relaxed">
                    Powered by Laravel’s beastly framework, FMS brings top-tier security and scalability for your financial adventures in 2025!
                </p>
            </div>

            <!-- Get Started Section (Same as before) -->
            <div class="bg-gradient-to-r from-indigo-100 to-purple-50 rounded-2xl p-8 shadow-2xl mb-8 transform hover:-translate-y-2 transition duration-300">
                <h3 class="text-3xl font-bold text-purple-800 mb-6 text-center underline decoration-dashed decoration-yellow-400">
                    Kick Off with FMS
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-white rounded-xl shadow-md hover:bg-gradient-to-r from-green-100 to-teal-100 transition duration-300 transform hover:-translate-y-1">
                        <h4 class="text-xl font-semibold text-green-700 mb-3">1. Explore Your Dashboard</h4>
                        <p class="text-gray-600">Unlock real-time financial insights with a single click!</p>
                        <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-red-500 mt-2 inline-block underline">🌟 Go to Dashboard</a>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-md hover:bg-gradient-to-r from-green-100 to-teal-100 transition duration-300 transform hover:-translate-y-1">
                        <h4 class="text-xl font-semibold text-green-700 mb-3">2. Track Your Finances</h4>
                        <p class="text-gray-600">Log income and expenses to build your financial foundation.</p>
                        <a href="{{ route('incomes') }}" class="text-blue-600 hover:text-red-500 mt-2 inline-block underline">💰 Add Income</a>
                        <a href="{{ route('expenses') }}" class="text-blue-600 hover:text-red-500 mt-2 inline-block underline">📉 Add Expenses</a>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-md hover:bg-gradient-to-r from-green-100 to-teal-100 transition duration-300 transform hover:-translate-y-1">
                        <h4 class="text-xl font-semibold text-green-700 mb-3">3. Plan Your Budget</h4>
                        <p class="text-gray-600">Craft a budget that powers your dreams!</p>
                        <a href="{{ route('budgets') }}" class="text-blue-600 hover:text-red-500 mt-2 inline-block underline">📊 Create Budget</a>
                    </div>
                    <div class="p-6 bg-white rounded-xl shadow-md hover:bg-gradient-to-r from-green-100 to-teal-100 transition duration-300 transform hover:-translate-y-1">
                        <h4 class="text-xl font-semibold text-green-700 mb-3">4. Generate Reports</h4>
                        <p class="text-gray-600">Dive into analytics that wow you!</p>
                        <a href="{{ route('reports') }}" class="text-blue-600 hover:text-red-500 mt-2 inline-block underline">📈 View Reports</a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mt-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md animate-fade-in relative">
                    {{ session('success') }}
                    <button type="button" class="absolute top-2 right-2 text-green-700 hover:text-green-900 text-xl font-bold leading-none" onclick="this.parentElement.style.display='none';" aria-label="Close">&times;</button>
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes text-glow {
            0% { text-shadow: 0 0 5px #ffd700, 0 0 10px #ff4500; }
            50% { text-shadow: 0 0 10px #ffd700, 0 0 20px #ff4500; }
            100% { text-shadow: 0 0 5px #ffd700, 0 0 10px #ff4500; }
        }
        .animate-text-glow {
            animation: text-glow 2s ease-in-out infinite;
        }
        @keyframes spin-slow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 20s linear infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce {
            animation: bounce 2s infinite;
        }
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out;
        }
        @keyframes fade-in {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }
    </style>
</x-app-layout>