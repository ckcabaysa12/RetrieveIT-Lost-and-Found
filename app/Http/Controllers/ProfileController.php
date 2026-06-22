<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('profile.show', ['user' => auth()->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'profile_image' => ['nullable', 'image', 'max:4096'],
            'id_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $data = $request->only(['name', 'phone']);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        if ($request->hasFile('id_image')) {
            if ($user->id_image) {
                Storage::disk('public')->delete($user->id_image);
            }
            $data['id_image'] = $request->file('id_image')->store('id_cards', 'public');

            if (! $user->isAdmin() && ! $user->isVerified()) {
                $data['verification_status'] = 'pending';
                $data['is_verified'] = false;
            }
        }

        $user->update($data);

        return Redirect::route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
