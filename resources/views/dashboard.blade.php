@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-hero mb-4">
    <h1 class="h3 mb-1">Hey, {{ auth()->user()->name }}!</h1>
    <p>Welcome to RetrieveIT. @include('partials.verified-badge', ['user' => auth()->user()])</p>
</div>

@if(!auth()->user()->is_verified)
    <div class="card-lf p-3 mb-4 border-warning" style="border-left: 4px solid var(--lf-amber) !important;">
        <div class="d-flex gap-3 align-items-start">
            <i class="bi bi-info-circle fs-4 text-warning"></i>
            <div>
                <strong>Get verified for more trust</strong>
                <p class="small text-muted mb-2">Upload a clear valid ID and wait for admin approval to receive your blue check badge.</p>
                <a href="{{ route('profile.show') }}" class="btn btn-lf btn-sm">Go to Profile</a>
            </div>
        </div>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="stat-pill"><div class="num">{{ $stats['items'] }}</div><div class="lbl">My Items</div></div></div>
    <div class="col-md-4"><div class="stat-pill"><div class="num">{{ $stats['claims'] }}</div><div class="lbl">My Claims</div></div></div>
    <div class="col-md-4"><div class="stat-pill"><div class="num">{{ $stats['pending_claims'] }}</div><div class="lbl">Pending Claims</div></div></div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('items.create') }}" class="btn btn-lf"><i class="bi bi-plus-lg me-1"></i> Report Item</a>
    <a href="{{ route('items.index') }}" class="btn btn-lf-outline"><i class="bi bi-search me-1"></i> Browse</a>
    <a href="{{ route('claims.index') }}" class="btn btn-lf-outline"><i class="bi bi-inbox me-1"></i> My Claims</a>
    <a href="{{ route('items.mine') }}" class="btn btn-lf-outline"><i class="bi bi-collection me-1"></i> My Items</a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-lf">
            <div class="card-header">Recent Items</div>
            <ul class="list-group list-group-flush">
                @forelse($myItems as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="{{ $item->type === 'found' ? 'badge-found' : 'badge-lost' }} me-1">{{ ucfirst($item->type) }}</span>
                            <a href="{{ route('items.show', $item) }}" class="fw-medium text-decoration-none">{{ $item->title }}</a>
                        </div>
                        <span class="badge-status bg-light text-muted">{{ str_replace('_', ' ', $item->status) }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted small">No items yet. <a href="{{ route('items.create') }}">Report one</a></li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-lf">
            <div class="card-header">Recent Claims</div>
            <ul class="list-group list-group-flush">
                @forelse($myClaims as $claim)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('claims.show', $claim) }}" class="fw-medium text-decoration-none">{{ $claim->item->title }}</a>
                        <span class="badge rounded-pill bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'rejected' ? 'danger' : 'warning') }}">{{ $claim->status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted small">No claims yet. Browse found items to claim.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin())
    <div class="mt-4 text-center">
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-pill"><i class="bi bi-speedometer2"></i> Open Admin Panel</a>
    </div>
@endif
@endsection
