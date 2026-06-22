@php
    $isOwner = auth()->check() && $item->user_id === auth()->id();
    $isAvailable = $item->status === 'available';
    $canClaimItem = auth()->check()
        && auth()->user()->isVerified()
        && $item->type === 'found'
        && $isAvailable
        && ! $isOwner;
    $canFoundItem = auth()->check()
        && auth()->user()->isVerified()
        && $item->type === 'lost'
        && $isAvailable
        && ! $isOwner;
    $needsVerification = auth()->check() && ! auth()->user()->isVerified() && ! $isOwner && $isAvailable;
@endphp

<div class="d-grid gap-2 {{ $class ?? '' }}">
    <a href="{{ route('items.show', $item) }}" class="btn btn-lf-outline btn-sm">View details</a>

    @guest
        @if($item->type === 'found' && $isAvailable)
            <a href="{{ route('login') }}" class="btn btn-amber btn-sm"><i class="bi bi-hand-index me-1"></i> Log in to claim</a>
        @elseif($item->type === 'lost' && $isAvailable)
            <a href="{{ route('login') }}" class="btn btn-lf btn-sm"><i class="bi bi-check-circle me-1"></i> Log in — I found this</a>
        @endif
    @else
        @if($needsVerification)
            <a href="{{ route('verification.pending') }}" class="btn btn-light btn-sm">ID approval required</a>
        @elseif($canClaimItem)
            <a href="{{ route('items.show', $item) }}#claim" class="btn btn-amber btn-sm fw-semibold">
                <i class="bi bi-hand-index me-1"></i> Claim this item
            </a>
        @elseif($canFoundItem)
            <a href="{{ route('items.create', ['from_lost' => $item->id]) }}" class="btn btn-lf btn-sm fw-semibold">
                <i class="bi bi-check-circle me-1"></i> I found this item
            </a>
        @elseif($isOwner)
            <span class="btn btn-light btn-sm disabled">Your listing</span>
        @elseif($item->status === 'pending_claim')
            <span class="btn btn-light btn-sm disabled">Claim in progress</span>
        @elseif($item->status !== 'available')
            <span class="btn btn-light btn-sm disabled">No longer available</span>
        @endif
    @endguest
</div>
