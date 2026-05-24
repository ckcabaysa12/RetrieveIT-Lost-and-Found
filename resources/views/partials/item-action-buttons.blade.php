@php
    $isOwner = auth()->check() && $item->user_id === auth()->id();
    $canClaimItem = auth()->check()
        && $item->type === 'found'
        && $item->status === 'available'
        && ! $isOwner;
    $canFoundItem = auth()->check()
        && $item->type === 'lost'
        && $item->status === 'available'
        && ! $isOwner;
@endphp

<div class="d-grid gap-2 {{ $class ?? '' }}">
    <a href="{{ route('items.show', $item) }}" class="btn btn-lf-outline btn-sm">View details</a>

    @guest
        @if($item->type === 'found' && $item->status === 'available')
            <a href="{{ route('login') }}" class="btn btn-amber btn-sm"><i class="bi bi-hand-index me-1"></i> Log in to claim</a>
        @elseif($item->type === 'lost' && $item->status === 'available')
            <a href="{{ route('login') }}" class="btn btn-lf btn-sm"><i class="bi bi-check-circle me-1"></i> Log in — I found this</a>
        @endif
    @else
        @if($canClaimItem)
            <a href="{{ route('items.show', $item) }}#claim" class="btn btn-amber btn-sm fw-semibold">
                <i class="bi bi-hand-index me-1"></i> Claim this item
            </a>
        @elseif($canFoundItem)
            <a href="{{ route('items.create', ['from_lost' => $item->id]) }}" class="btn btn-lf btn-sm fw-semibold">
                <i class="bi bi-check-circle me-1"></i> I found this item
            </a>
        @elseif($isOwner)
            <span class="btn btn-light btn-sm disabled">Your listing</span>
        @elseif($item->status !== 'available')
            <span class="btn btn-light btn-sm disabled">No longer available</span>
        @endif
    @endguest
</div>
