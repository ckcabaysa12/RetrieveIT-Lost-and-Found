@extends('layouts.app')
@section('title', $item->title)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-lf mb-4">
            @if($item->image)
                <img src="{{ asset('storage/'.$item->image) }}" class="w-100" alt="" style="max-height:340px;object-fit:cover">
            @endif
            <div class="p-4">
                <div class="mb-2">
                    <span class="{{ $item->type === 'found' ? 'badge-found' : 'badge-lost' }}">{{ ucfirst($item->type) }}</span>
                    <span class="badge bg-light text-muted">{{ $item->category->name }}</span>
                    <span class="badge-status bg-secondary-subtle text-secondary">{{ str_replace('_', ' ', $item->status) }}</span>
                </div>
                <h1 class="h3 fw-bold">{{ $item->title }}</h1>
                <p class="text-muted">{{ $item->description }}</p>
                <div class="row g-2 small">
                    <div class="col-sm-6"><i class="bi bi-geo-alt me-1"></i> {{ $item->location }}</div>
                    <div class="col-sm-6"><i class="bi bi-calendar me-1"></i> {{ $item->date_reported->format('M d, Y') }}</div>
                </div>
                <hr>
                <p class="mb-0 small">
                    Posted by <strong>{{ $item->user->name }}</strong>
                    @include('partials.verified-badge', ['user' => $item->user])
                </p>
            </div>
        </div>

        @if($matches->isNotEmpty())
            <div class="card-lf">
                <div class="card-header"><i class="bi bi-lightning me-1"></i> Smart Matches</div>
                <ul class="list-group list-group-flush">
                    @foreach($matches as $match)
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <span class="small fw-medium">{{ $match->title }}</span>
                                <span class="text-muted small d-block">{{ $match->location }}</span>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ route('items.show', $match) }}" class="btn btn-lf-outline btn-sm">View</a>
                                @auth
                                    @if($match->type === 'found' && $match->status === 'available' && $match->user_id !== auth()->id())
                                        <a href="{{ route('items.show', $match) }}#claim" class="btn btn-amber btn-sm">Claim</a>
                                    @elseif($match->type === 'lost' && $match->status === 'available' && $match->user_id !== auth()->id())
                                        <a href="{{ route('items.create', ['from_lost' => $match->id]) }}" class="btn btn-lf btn-sm">I found this</a>
                                    @endif
                                @endauth
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                            <textarea name="claim_message" class="form-control mb-3" rows="4" required minlength="20" placeholder="Describe unique marks, contents, color, etc."></textarea>
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
                            Wait for someone to click <strong>I found this item</strong>, or check smart matches below.
                        @else
                            Wait for an owner to submit a claim.
                        @endif
                    </p>
                    @if($item->type === 'found')
                        <a href="{{ route('claims.received', ['item' => $item->id]) }}" class="btn btn-lf-outline btn-sm mt-3">
                            <i class="bi bi-shield-check me-1"></i> Review claims for this item
                        </a>
                    @endif
                </div>
            @elseif($item->status !== 'available')
                <div class="card-lf p-4">
                    <p class="fw-medium mb-1"><i class="bi bi-lock me-1"></i> Not available</p>
                    <p class="text-muted small mb-0">Status: <strong>{{ str_replace('_', ' ', $item->status) }}</strong>.</p>
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
