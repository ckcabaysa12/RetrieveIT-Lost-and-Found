<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'user')->latest();

        if (! $request->has('status')) {
            $query->where('verification_status', 'pending');
        } elseif ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        return view('admin.users.index', [
            'users' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function verify(User $user): RedirectResponse
    {
        abort_if($user->role !== 'user', 404);

        $user->update([
            'verification_status' => 'verified',
            'is_verified' => true,
        ]);

        return back()->with('success', "{$user->name} is now verified.");
    }

    public function reject(User $user): RedirectResponse
    {
        abort_if($user->role !== 'user', 404);

        $user->update([
            'verification_status' => 'rejected',
            'is_verified' => false,
        ]);

        return back()->with('success', "Verification rejected for {$user->name}.");
    }
}
