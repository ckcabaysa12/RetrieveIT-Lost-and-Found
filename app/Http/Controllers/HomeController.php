<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        if (! auth()->check()) {
            return view('auth.login');
        }

        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->isVerified()) {
            return redirect()->route('verification.pending');
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return app(DashboardController::class)();
    }
}
