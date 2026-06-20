@extends('layouts.app')
@section('title', 'Review Claim')

@section('content')
@include('partials.back-button', ['url' => route('admin.claims.index'), 'label' => 'Back to claims'])

<p class="section-label">Final ownership confirmation</p>
<h1 class="h4 fw-bold mb-4">Admin: Confirm ownership</h1>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-lf p-4 mb-3">
            <h6 class="fw-bold text-muted small text-uppercase">Claimant (says they are the owner)</h6>
            <p class="mb-1 fs-5 fw-bold">{{ $claim->user->name }} @include('partials.verified-badge', ['user' => $claim->user])</p>
            <p class="text-muted small mb-3">{{ $claim->user->email }} · {{ $claim->user->phone ?? 'no phone' }}</p>
            <h6 class="fw-bold text-muted small text-uppercase">Their proof</h6>
            <div class="bg-light rounded-3 p-3">{{ $claim->claim_message }}</div>
        </div>
        <div class="card-lf p-4">
            <h6 class="fw-bold text-muted small text-uppercase">Finder (has the item)</h6>
            <p class="fw-bold mb-1">{{ $claim->item->title }}</p>
            <p class="small mb-2">{{ $claim->item->user->name }} — {{ $claim->item->user->email }}</p>
            <p class="small mb-0">
                <strong>Finder decision:</strong>
                <span class="badge bg-{{ $claim->finder_ownership === 'approved' ? 'success' : ($claim->finder_ownership === 'rejected' ? 'danger' : 'warning') }}">
                    {{ $claim->finderOwnershipLabel() }}
                </span>
            </p>
            @if($claim->finder_ownership_note)
                <p class="small text-muted mt-2 mb-0">Note: {{ $claim->finder_ownership_note }}</p>
            @endif
            <a href="{{ route('items.show', $claim->item) }}" class="btn btn-lf-outline btn-sm mt-2">View item</a>
        </div>
    </div>
    <div class="col-lg-6">
        @if($claim->status === 'pending')
            @if($claim->finder_ownership === 'pending')
                <div class="alert alert-warning">
                    <strong>Waiting for finder.</strong> The person who found the item must confirm this is the real owner before you can approve.
                </div>
            @elseif($claim->finder_ownership === 'approved')
                <div class="card-lf p-4 mb-3" style="border-top:4px solid #22c55e">
                    <h5 class="fw-bold mb-3">Confirm ownership (admin)</h5>
                    <p class="small text-muted mb-3">
                        The finder believes this is the true owner. Review the proof and ID verification, then approve to issue a claim code.
                    </p>
                    <form method="POST" action="{{ route('admin.claims.approve', $claim) }}">@csrf
                        <button class="btn btn-lf w-100 mb-2">Confirm — approve claim</button>
                    </form>
                </div>
                <form method="POST" action="{{ route('admin.claims.reject', $claim) }}">@csrf
                    <button class="btn btn-outline-danger w-100">Reject — not the owner</button>
                </form>
            @else
                <div class="alert alert-danger mb-0">Finder rejected this ownership claim.</div>
            @endif
        @else
            <div class="card-lf p-4">
                <p>Claim: <strong>{{ ucfirst($claim->status) }}</strong></p>
                @if($claim->claim_code)<p>Code: <code>{{ $claim->claim_code }}</code></p>@endif
                @if($claim->pickup)
                    <p class="small mb-0">Pickup: <strong>{{ $claim->pickup->statusLabel() }}</strong></p>
                @endif
                @if($claim->isConfirmedByOwner())
                    <div class="alert alert-success small mb-0 mt-3">Owner confirmed receipt.</div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
