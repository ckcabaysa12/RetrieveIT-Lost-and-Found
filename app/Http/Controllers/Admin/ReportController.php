<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('admin.reports.index', [
            'userStats' => [
                'total' => User::where('role', 'user')->count(),
                'verified' => User::where('role', 'user')->where('is_verified', true)->count(),
                'pending' => User::where('role', 'user')->where('verification_status', 'pending')->count(),
            ],
            'itemStats' => [
                'lost' => Item::where('type', 'lost')->count(),
                'found' => Item::where('type', 'found')->count(),
                'returned' => Item::where('status', 'returned')->count(),
            ],
            'claimStats' => [
                'pending' => Claim::where('status', 'pending')->count(),
                'approved' => Claim::where('status', 'approved')->count(),
                'rejected' => Claim::where('status', 'rejected')->count(),
            ],
            'byCategory' => Category::withCount('items')->orderByDesc('items_count')->get(),
        ]);
    }
}
