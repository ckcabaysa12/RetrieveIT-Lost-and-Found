@extends('layouts.app')
@section('title', 'Claim Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-lf p-4 mb-3">
            <p class="section-label">Claim for</p>
            <h1 class="h4 fw-bold">
                <a href="{{ route('items.show', $claim->item) }}" class="text-decoration-none text-dark">{{ $claim->item->title }}</a>
            </h1>
            <p class="mb-2">
                Claim:
                <span class="badge rounded-pill bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'rejected' ? 'danger' : 'warning') }}">
                    {{ ucfirst($claim->status) }}
                </span>
            </p>
            <p class="small mb-3">
                <strong>Ownership check:</strong>
                <span class="badge bg-{{ $claim->finder_ownership === 'approved' ? 'success' : ($claim->finder_ownership === 'rejected' ? 'danger' : 'secondary') }}">
                    {{ $claim->finderOwnershipLabel() }}
                </span>
                @if($claim->status === 'pending' && $claim->finder_ownership === 'approved')
                    <span class="text-muted"> → Admin must confirm next</span>
                @endif
            </p>
            @if($claim->pickup && $claim->status === 'approved')
                <p class="small text-muted mb-3"><i class="bi bi-info-circle"></i> Pickup: <strong>{{ $claim->pickup->statusLabel() }}</strong></p>
            @endif

            @if($isClaimer && $claim->status === 'pending')
                <div class="alert alert-info small">
                    @if($claim->finder_ownership === 'pending')
                        Waiting for the <strong>finder</strong> to verify you are the real owner.
                    @elseif($claim->finder_ownership === 'approved')
                        Finder confirmed ownership. Waiting for <strong>admin</strong> final confirmation.
                    @else
                        The finder rejected your ownership claim.
                    @endif
                </div>
            @endif

            <p class="fw-medium small text-muted mb-1">{{ $isClaimer ? 'Your proof message' : 'Claimant proof' }}</p>
            <div class="bg-light rounded-3 p-3 mb-3">{{ $claim->claim_message }}</div>

            @if($claim->claim_code)
                <div class="text-center p-4 rounded-3 mb-3" style="background:var(--lf-teal-soft)">
                    <p class="section-label mb-1">Claim code</p>
                    <code class="fs-2 fw-bold" style="color:var(--lf-teal-dark)">{{ $claim->claim_code }}</code>
                    <p class="small text-muted mt-2 mb-0">Show this at pickup after the schedule is confirmed.</p>
                </div>
            @endif

            @if($claim->status === 'approved')
                @if($isClaimer)
                    @include('partials.contact-details', ['user' => $claim->item->user])
                @elseif($isFinder)
                    @include('partials.contact-details', ['user' => $claim->user])
                @endif
            @endif

            @if($claim->pickup && $claim->pickup->location && $claim->pickup->date)
                <div class="card-lf p-3 mb-0" style="border-left:4px solid var(--lf-amber)">
                    <h5 class="fw-bold h6"><i class="bi bi-calendar-event me-1"></i> Proposed pickup</h5>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-1"><strong>Location:</strong> {{ $claim->pickup->location }}</li>
                        <li class="mb-1"><strong>Date:</strong> {{ $claim->pickup->date->format('l, M d, Y') }}</li>
                        <li><strong>Time:</strong> {{ $claim->pickup->time }}</li>
                    </ul>
                    @if($claim->pickup->finder_confirmed_at)
                        <p class="small text-success mb-0 mt-2"><i class="bi bi-check-circle"></i> Finder confirmed {{ $claim->pickup->finder_confirmed_at->format('M d, g:i A') }}</p>
                    @endif
                </div>
            @endif

            @if($claim->pickup?->status === 'reschedule_requested')
                <div class="alert alert-warning mt-3 mb-0 small">
                    <strong>Reschedule requested</strong> by {{ $claim->pickup->reschedule_requested_by === 'owner' ? 'owner' : 'finder' }}:<br>
                    {{ $claim->pickup->reschedule_note }}
                </div>
            @endif
        </div>

        {{-- Finder: verify ownership --}}
        @if($isFinder && $claim->isAwaitingFinderOwnershipReview())
            <div class="card-lf p-4 mb-3" style="border-top:4px solid var(--lf-teal)">
                <h5 class="fw-bold mb-2"><i class="bi bi-person-check me-1"></i> Is this the real owner?</h5>
                <p class="small text-muted mb-3">You have the item. Compare their proof with what you found before admin approves.</p>
                <form method="POST" action="{{ route('claims.approve-ownership', $claim) }}" class="mb-3">
                    @csrf
                    <label class="form-label small">Optional note</label>
                    <textarea name="finder_ownership_note" class="form-control mb-2" rows="2" placeholder="e.g. Description matches what I found"></textarea>
                    <button class="btn btn-lf w-100">Yes — this is the owner</button>
                </form>
                <form method="POST" action="{{ route('claims.reject-ownership', $claim) }}">
                    @csrf
                    <label class="form-label small">Reason required if not the owner</label>
                    <textarea name="finder_ownership_note" class="form-control mb-2" rows="2" required minlength="10" placeholder="Why this does not match..."></textarea>
                    <button class="btn btn-outline-danger w-100">No — not the owner</button>
                </form>
            </div>
        @endif

        {{-- Owner: propose schedule --}}
        @if($isClaimer && $claim->status === 'approved' && $claim->pickup?->isAwaitingOwnerSchedule())
            <div class="card-lf p-4 mb-3">
                <h5 class="fw-bold mb-2"><i class="bi bi-calendar-plus me-1"></i> Propose pickup schedule</h5>
                <p class="small text-muted mb-3">You (the owner) choose a safe location, date, and time. The finder must confirm before pickup.</p>
                @include('partials.safe-pickup')
                <form method="POST" action="{{ route('claims.schedule', $claim) }}" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">Safe location</label>
                        <select name="location" class="form-select" required>
                            @foreach($safeLocations as $loc)
                                <option value="{{ $loc }}" @selected(old('location', $claim->pickup->location) === $loc)>{{ $loc }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-medium">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', $claim->pickup->date?->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-medium">Time</label>
                            <input type="time" name="time" class="form-control" value="{{ old('time', $claim->pickup->time) }}" required>
                        </div>
                    </div>
                    <button class="btn btn-lf w-100">Send schedule to finder</button>
                </form>
            </div>
        @endif

        {{-- Finder: confirm schedule --}}
        @if($isFinder && $claim->status === 'approved' && $claim->pickup?->isAwaitingFinder())
            <div class="card-lf p-4 mb-3 border-success">
                <h5 class="fw-bold mb-2"><i class="bi bi-check2-square me-1"></i> Confirm pickup schedule</h5>
                <p class="small text-muted mb-3">The owner proposed the schedule above. Confirm if you can meet at that time, or request a reschedule if there is an emergency.</p>
                <form method="POST" action="{{ route('claims.confirm-schedule', $claim) }}" class="mb-2">
                    @csrf
                    <button class="btn btn-lf w-100">Confirm schedule</button>
                </form>
            </div>
        @endif

        {{-- Reschedule (owner or finder) --}}
        @if($claim->status === 'approved' && $claim->pickup && in_array($claim->pickup->status, ['awaiting_finder', 'confirmed']))
            @if($isClaimer || $isFinder)
                <div class="card-lf p-4 mb-3">
                    <h5 class="fw-bold h6 mb-2"><i class="bi bi-arrow-repeat me-1"></i> Request reschedule</h5>
                    <p class="small text-muted mb-2">Use if you or the other person cannot make the scheduled time (emergency, class, etc.). The owner will propose a new schedule.</p>
                    <form method="POST" action="{{ route('claims.reschedule', $claim) }}">
                        @csrf
                        <textarea name="reschedule_note" class="form-control mb-2" rows="2" required minlength="10" placeholder="Brief reason and when you are available..."></textarea>
                        <button class="btn btn-outline-secondary btn-sm w-100">Request reschedule</button>
                    </form>
                </div>
            @endif
        @endif

        @if($claim->pickup?->isConfirmed())
            @include('partials.safe-pickup')
        @endif

        {{-- Owner: confirm receipt --}}
        @if($isClaimer && $claim->status === 'approved' && $claim->pickup?->isConfirmed() && !$claim->isConfirmedByOwner())
            <div class="card-lf p-4 mb-3" style="border-top:4px solid #22c55e">
                <h5 class="fw-bold mb-2"><i class="bi bi-check2-circle me-1"></i> Confirm you received your item</h5>
                <p class="small text-muted mb-3">Only after pickup at the confirmed time and location.</p>
                <form method="POST" action="{{ route('claims.confirm', $claim) }}" onsubmit="return confirm('Confirm that you received this item?');">
                    @csrf
                    <button class="btn btn-lf w-100">Yes, I received my item</button>
                </form>
            </div>
        @elseif($claim->isConfirmedByOwner())
            <div class="alert alert-success mb-3">
                <i class="bi bi-check-circle-fill me-1"></i>
                Owner confirmed receipt on {{ $claim->owner_confirmed_at->format('M d, Y g:i A') }}. Case closed.
            </div>
        @endif

        <a href="{{ $isClaimer ? route('claims.index') : route('claims.received') }}" class="btn btn-lf-outline">← Back</a>
    </div>
</div>
@endsection
