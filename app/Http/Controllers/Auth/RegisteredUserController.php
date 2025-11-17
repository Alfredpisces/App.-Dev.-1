<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // <-- 1. IMPORTED STORAGE
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 2. VALIDATION: Added 'profile_picture'
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'timezone' => ['required', 'string', Rule::in(\DateTimeZone::listIdentifiers())],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // <-- ADDED THIS
        ]);

        // Keep your existing business name logic
        $businessName = $request->input('business_name');
        if (empty(trim($businessName))) {
            $businessName = 'Personal';
        }

        // 3. FILE UPLOAD LOGIC
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // 4. USER CREATION: Added 'profile_picture'
        $user = User::create([
            'name' => $request->name,
            'business_name' => $businessName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'timezone' => $request->timezone,
            'profile_picture' => $profilePicturePath, // <-- ADDED THIS
        ]);

        // Keep your existing Role Assignment Logic
        $ownerRole = Role::where('name', 'owner')->first();
        if ($ownerRole) {
            $user->roles()->attach($ownerRole);
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}