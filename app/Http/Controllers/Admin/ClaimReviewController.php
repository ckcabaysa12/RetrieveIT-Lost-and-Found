<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\Pickup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ClaimReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Claim::with(['user', 'item'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('admin.claims.index', [
            'claims' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function show(Claim $claim): View
    {
        $claim->load(['user', 'item.user', 'item.category', 'pickup']);

        return view('admin.claims.show', ['claim' => $claim]);
    }

    public function approve(Claim $claim): RedirectResponse
    {
        abort_unless($claim->isAwaitingAdminConfirmation(), 403, 'Finder must confirm ownership before admin approval.');

        $claim->update([
            'status' => 'approved',
            'claim_code' => strtoupper(Str::random(8)),
        ]);

        Pickup::updateOrCreate(
            ['claim_id' => $claim->id],
            ['status' => 'awaiting_schedule']
        );

        $claim->item->update(['status' => 'claimed']);

        $claim->item->claims()
            ->where('id', '!=', $claim->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return redirect()->route('admin.claims.index')
            ->with('success', 'Ownership confirmed by admin. Owner can propose pickup; finder confirms schedule.');
    }

    public function reject(Claim $claim): RedirectResponse
    {
        abort_unless($claim->status === 'pending', 403);

        $claim->update([
            'status' => 'rejected',
            'finder_ownership' => $claim->finder_ownership === 'pending' ? 'rejected' : $claim->finder_ownership,
        ]);

        $hasActiveClaims = $claim->item->claims()
            ->whereIn('status', ['pending', 'approved'])
            ->where('id', '!=', $claim->id)
            ->exists();

        if (! $hasActiveClaims) {
            $claim->item->update(['status' => 'available']);
        }

        return back()->with('success', 'Claim rejected by admin.');
    }
}
