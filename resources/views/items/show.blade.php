@extends('layouts.app')
@section('title', $item->title)

@section('content')
@include('partials.back-button', ['url' => route('items.index'), 'label' => 'Back to listings'])

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-lf mb-4">
            @include('partials.item-photo-gallery', [
                'urls' => $item->imageUrls(),
                'alt' => $item->title,
                'id' => $item->id,
            ])
            <div class="p-4">
                <div class="mb-2">
                    <span class="{{ $item->type === 'found' ? 'badge-found' : 'badge-lost' }}">{{ ucfirst($item->type) }}</span>
                    <span class="badge bg-light text-muted">{{ $item->category->name }}</span>
                    <span class="badge-status bg-secondary-subtle text-secondary">{{ str_replace('_', ' ', $item->status) }}</span>
                    @php $activeClaims = $item->claims->whereIn('status', ['pending', 'approved']); @endphp
                    @if($activeClaims->isNotEmpty())
                        <span class="badge bg-info-subtle text-info-emphasis">{{ $activeClaims->count() }} active claim{{ $activeClaims->count() > 1 ? 's' : '' }}</span>
                    @endif
                </div>
                <h1 class="h3 fw-bold">{{ $item->title }}</h1>
                <p class="text-muted">{{ $item->description }}</p>
                <div class="row g-2 small">
                    <div class="col-sm-6"><i class="bi bi-geo-alt me-1"></i> {{ $item->location }}</div>
                    <div class="col-sm-6"><i class="bi bi-calendar me-1"></i> {{ $item->date_reported->format('M d, Y') }}</div>
                </div>
                <hr>
                <p class="mb-0 small d-inline-flex align-items-center gap-1">
                    Posted by <strong>{{ $item->user->name }}</strong>
                    @include('partials.verified-badge', ['user' => $item->user, 'verifiedOnly' => true])
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @auth
            @if($canClaim)
                <div class="card-lf mb-3" style="border-top: 4px solid var(--lf-amber)" id="claim">
                    <div class="p-4">
                        <h5 class="fw-bold mb-2"><i class="bi bi-hand-index me-1"></i> Claim this item</h5>
                        <p class="small text-muted mb-3">Think this found item is yours? Submit proof below. Admin will review before pickup.</p>
                        @include('partials.safe-pickup')
                        <form method="POST" action="{{ route('claims.store', $item) }}" class="mt-3">
                            @csrf
                            <label class="form-label small fw-medium">Why is this yours?</label>
                            <textarea name="claim_message" class="form-control mb-3" rows="4" required placeholder="Describe unique marks, contents, color, etc."></textarea>
                            <x-input-error :messages="$errors->get('claim_message')" />
                            <button class="btn btn-amber w-100 fw-semibold"><i class="bi bi-send me-1"></i> Submit claim</button>
                        </form>
                    </div>
                </div>
            @elseif($canReportFound)
                <div class="card-lf mb-3" style="border-top: 4px solid var(--lf-teal)">
                    <div class="p-4 text-center">
                        <h5 class="fw-bold mb-2"><i class="bi bi-check-circle me-1"></i> I found this item</h5>
                        <p class="small text-muted mb-3">
                            Someone lost this. If you have it, post a <strong>found</strong> report so the owner can claim it from you.
                        </p>
                        <a href="{{ route('items.create', ['from_lost' => $item->id]) }}" class="btn btn-lf w-100 fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i> Report as found
                        </a>
                    </div>
                </div>
            @elseif($item->user_id === auth()->id())
                <div class="card-lf p-4">
                    <p class="fw-medium mb-1"><i class="bi bi-person-check me-1"></i> This is your listing</p>
                    <p class="text-muted small mb-0">
                        @if($item->type === 'lost')
                            Wait for someone to click <strong>I found this item</strong>.
                        @else
                            Wait for an owner to submit a claim.
                        @endif
                    </p>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @if($canEdit)
                            <a href="{{ route('items.edit', $item) }}" class="btn btn-lf btn-sm">
                                <i class="bi bi-pencil me-1"></i> Edit listing
                            </a>
                            @include('partials.remove-item-form', ['item' => $item])
                        @endif
                        @if($item->type === 'found')
                            <a href="{{ route('claims.received', ['item' => $item->id]) }}" class="btn btn-lf-outline btn-sm">
                                <i class="bi bi-shield-check me-1"></i> Review claims
                            </a>
                        @endif
                    </div>
                </div>
            @elseif(!auth()->user()->isVerified())
                <div class="card-lf p-4 text-center">
                    <p class="fw-medium mb-2">ID approval required</p>
                    <p class="small text-muted mb-3">An admin must verify your valid ID before you can claim or report items.</p>
                    <a href="{{ route('verification.pending') }}" class="btn btn-lf-outline w-100">View verification status</a>
                </div>
            @elseif($item->status !== 'available')
                <div class="card-lf p-4">
                    <p class="fw-medium mb-1"><i class="bi bi-lock me-1"></i> Not available</p>
                    <p class="text-muted small mb-0">Status: <strong>{{ str_replace('_', ' ', $item->status) }}</strong>.</p>
                    @if($activeClaims->isNotEmpty())
                        <p class="text-muted small mb-0 mt-2">This item has {{ $activeClaims->count() }} active claim(s).</p>
                    @endif
                </div>
            @endif
        @else
            <div class="card-lf p-4 text-center">
                @if($item->type === 'found' && $item->status === 'available')
                    <p class="fw-medium mb-2">Is this yours?</p>
                    <a href="{{ route('login') }}" class="btn btn-amber w-100 mb-2">Log in to claim</a>
                @elseif($item->type === 'lost' && $item->status === 'available')
                    <p class="fw-medium mb-2">Do you have this item?</p>
                    <a href="{{ route('login') }}" class="btn btn-lf w-100 mb-2">Log in — I found this</a>
                @else
                    <p class="mb-2">Log in to take action</p>
                    <a href="{{ route('login') }}" class="btn btn-lf">Log in</a>
                @endif
            </div>
        @endauth
    </div>
</div>
@endsection

@push('scripts')
@if(request()->has('claim'))
<script>document.getElementById('claim')?.scrollIntoView({ behavior: 'smooth' });</script>
@endif
@endpush
