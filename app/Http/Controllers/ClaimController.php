<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Item;
use App\Models\Pickup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClaimController extends Controller
{
    public function index(): View
    {
        $claims = Claim::with(['item.category', 'pickup'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('claims.index', compact('claims'));
    }

    public function received(Request $request): View
    {
        $claims = Claim::with(['user', 'item.category', 'pickup'])
            ->whereHas('item', function ($query) use ($request) {
                $query->where('user_id', auth()->id());

                if ($request->filled('item')) {
                    $query->where('id', $request->integer('item'));
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('claims.received', compact('claims'));
    }

    public function show(Claim $claim): View
    {
        $isClaimer = $claim->user_id === auth()->id();
        $isFinder = $claim->item()->where('user_id', auth()->id())->exists();

        abort_unless($isClaimer || $isFinder || auth()->user()->isAdmin(), 403);

        $claim->load(['item.user', 'item.category', 'pickup', 'user']);

        return view('claims.show', [
            'claim' => $claim,
            'isClaimer' => $isClaimer,
            'isFinder' => $isFinder,
            'safeLocations' => config('pickup.safe_locations'),
        ]);
    }

    public function store(Request $request, Item $item): RedirectResponse
    {
        abort_unless($item->type === 'found' && $item->status === 'available', 403, 'This item is no longer available to claim.');
        abort_if($item->user_id === auth()->id(), 403, 'You cannot claim your own item.');

        $existing = Claim::where('item_id', $item->id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'You already have an active claim for this item.');
        }

        $data = $request->validate([
            'claim_message' => ['required', 'string', 'min:20'],
        ]);

        Claim::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'claim_message' => $data['claim_message'],
            'status' => 'pending',
        ]);

        $item->update(['status' => 'pending_claim']);

        return redirect()->route('claims.index')
            ->with('success', 'Claim submitted. The finder will verify ownership first, then admin will confirm.');
    }

    public function approveOwnership(Request $request, Claim $claim): RedirectResponse
    {
        $claim->load('item');
        abort_unless($claim->item->user_id === auth()->id(), 403);
        abort_unless($claim->isAwaitingFinderOwnershipReview(), 403);

        $data = $request->validate([
            'finder_ownership_note' => ['nullable', 'string', 'max:500'],
        ]);

        $claim->update([
            'finder_ownership' => 'approved',
            'finder_ownership_note' => $data['finder_ownership_note'] ?? null,
            'finder_reviewed_at' => now(),
        ]);

        return back()->with('success', 'You confirmed this claimant is the real owner. Waiting for admin final confirmation.');
    }

    public function rejectOwnership(Request $request, Claim $claim): RedirectResponse
    {
        $claim->load('item');
        abort_unless($claim->item->user_id === auth()->id(), 403);
        abort_unless($claim->isAwaitingFinderOwnershipReview(), 403);

        $data = $request->validate([
            'finder_ownership_note' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $claim->update([
            'status' => 'rejected',
            'finder_ownership' => 'rejected',
            'finder_ownership_note' => $data['finder_ownership_note'],
            'finder_reviewed_at' => now(),
        ]);

        $this->revertItemIfNoActiveClaims($claim->item, $claim->id);

        return back()->with('success', 'Ownership claim rejected. The item is open again for other claims.');
    }

    public function proposeSchedule(Request $request, Claim $claim): RedirectResponse
    {
        abort_unless($claim->user_id === auth()->id(), 403);
        abort_unless($claim->status === 'approved', 403);

        $claim->load('pickup');
        abort_unless($claim->pickup && $claim->pickup->isAwaitingOwnerSchedule(), 403);

        $data = $request->validate([
            'location' => ['required', Rule::in(config('pickup.safe_locations'))],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'time' => ['required'],
        ]);

        $claim->pickup->update([
            'location' => $data['location'],
            'date' => $data['date'],
            'time' => $data['time'],
            'status' => 'awaiting_finder',
            'finder_confirmed_at' => null,
            'reschedule_note' => null,
            'reschedule_requested_by' => null,
        ]);

        return back()->with('success', 'Pickup schedule sent to the finder for confirmation.');
    }

    public function confirmSchedule(Claim $claim): RedirectResponse
    {
        $claim->load(['pickup', 'item']);
        abort_unless($claim->item->user_id === auth()->id(), 403);
        abort_unless($claim->status === 'approved' && $claim->pickup?->isAwaitingFinder(), 403);

        $claim->pickup->update([
            'status' => 'confirmed',
            'finder_confirmed_at' => now(),
        ]);

        return back()->with('success', 'Pickup schedule confirmed. Meet at the safe location with the claim code.');
    }

    public function requestReschedule(Request $request, Claim $claim): RedirectResponse
    {
        $claim->load('pickup', 'item');
        $isClaimer = $claim->user_id === auth()->id();
        $isFinder = $claim->item->user_id === auth()->id();

        abort_unless($isClaimer || $isFinder, 403);
        abort_unless($claim->status === 'approved' && $claim->pickup, 403);
        abort_unless(in_array($claim->pickup->status, ['awaiting_finder', 'confirmed'], true), 403);

        $data = $request->validate([
            'reschedule_note' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        $claim->pickup->update([
            'status' => 'reschedule_requested',
            'reschedule_note' => $data['reschedule_note'],
            'reschedule_requested_by' => $isClaimer ? 'owner' : 'finder',
            'finder_confirmed_at' => null,
        ]);

        return back()->with('success', 'Reschedule request sent. The owner will propose a new date and time.');
    }

    public function confirmReceipt(Claim $claim): RedirectResponse
    {
        abort_unless($claim->user_id === auth()->id(), 403);
        abort_unless($claim->status === 'approved', 403);
        abort_if($claim->isConfirmedByOwner(), 403);

        $claim->load('item', 'pickup');
        abort_unless($claim->pickup?->isConfirmed(), 403, 'Pickup must be confirmed by the finder first.');

        $claim->update(['owner_confirmed_at' => now()]);
        $claim->pickup->update(['status' => 'completed']);
        $claim->item->update(['status' => 'returned']);

        return redirect()->route('claims.show', $claim)
            ->with('success', 'Thank you! You confirmed that you received your item. This case is now closed.');
    }

    private function revertItemIfNoActiveClaims(Item $item, ?int $exceptClaimId = null): void
    {
        $query = $item->claims()->whereIn('status', ['pending', 'approved']);

        if ($exceptClaimId) {
            $query->where('id', '!=', $exceptClaimId);
        }

        if (! $query->exists()) {
            $item->update(['status' => 'available']);
        }
    }
}
