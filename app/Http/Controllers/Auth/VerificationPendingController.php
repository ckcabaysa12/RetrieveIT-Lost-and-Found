<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VerificationPendingController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isVerified()) {
            return redirect()->route('dashboard');
        }

        return view('auth.verification-pending', [
            'user' => $user,
        ]);
    }
}
