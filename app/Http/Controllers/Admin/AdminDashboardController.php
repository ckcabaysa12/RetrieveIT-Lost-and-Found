<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::where('role', 'user')->count(),
                'pending_users' => User::where('role', 'user')->where('verification_status', 'pending')->count(),
                'items' => Item::count(),
                'available_items' => Item::where('status', 'available')->count(),
                'pending_claims' => Claim::where('status', 'pending')->count(),
            ],
            'recentClaims' => Claim::with(['user', 'item'])->latest()->limit(5)->get(),
        ]);
    }
}
