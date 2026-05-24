<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        return view('dashboard', [
            'myItems' => Item::where('user_id', $user->id)->latest()->limit(5)->get(),
            'myClaims' => Claim::with('item')->where('user_id', $user->id)->latest()->limit(5)->get(),
            'stats' => [
                'items' => Item::where('user_id', $user->id)->count(),
                'claims' => Claim::where('user_id', $user->id)->count(),
                'pending_claims' => Claim::where('user_id', $user->id)->where('status', 'pending')->count(),
            ],
        ]);
    }
}
