<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

// --- ADD THESE ---
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image; // This is the v3 facade

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // --- START: Profile Picture Logic ---

        // 1. Check if user clicked "Remove Photo"
        if ($request->input('remove_picture') == '1') {
            
            // Delete old file from storage if it exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            // Set database column to null
            $user->profile_picture = null;
        
        // 2. Else, check if user uploaded a new photo (Intervention Image logic)
        } elseif ($request->hasFile('profile_picture')) {
            
            // Delete old file from storage if it exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Get the uploaded file
            $image_file = $request->file('profile_picture');

            // --- START: FINAL v3 CODE ---
            // 1. Read the image
            $image = Image::read($image_file);

            // 2. Crop it to a 200x200 square (v3 uses "cover", NOT "fit")
            $image->cover(200, 200);

            // 3. Encode it as a JPG with 80% quality
            $image_data = $image->toJpg(80);

            // 4. Create a unique filename
            $path = 'profile-pictures/' . Str::uuid() . '.jpg';

            // 5. Save the new, small image to storage
            Storage::disk('public')->put($path, $image_data);
            
            // 6. Set the new path in the database
            $user->profile_picture = $path;
            // --- END: FINAL v3 CODE ---
        }
        
        // --- END: Profile Picture Logic ---

        // Unset picture from $data, so fill() doesn't overwrite our logic
        unset($data['profile_picture']);
        
        // Fill other validated data (name, email, business_name)
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile picture if it exists
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}