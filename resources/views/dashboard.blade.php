@extends('layouts.app')
@section('title', 'Home')

@section('content')
<section class="page-hero mb-4">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <p class="section-label text-white opacity-75 mb-2">IPT Project</p>
            <h1 class="h3 mb-1 d-inline-flex align-items-center gap-2 flex-wrap">
                Hey, {{ auth()->user()->name }}!
                @include('partials.verified-badge', ['user' => auth()->user(), 'verifiedOnly' => true])
            </h1>
            <p class="lead mb-2">Web-Based Lost and Found Management System</p>
            <p class="mb-0 opacity-90">Welcome to {{ config('app.name') }}.</p>
        </div>
        <div class="col-lg-4 d-none d-lg-block text-center position-relative" style="z-index:1">
            @include('partials.logo', ['class' => 'site-logo--hero'])
        </div>
    </div>
</section>

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
    <a href="{{ route('profile.show') }}" class="btn btn-lf-outline"><i class="bi bi-person me-1"></i> Profile</a>
</div>

<div class="row g-4 mb-4">
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

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="feature-tile">
            <div class="icon-wrap"><i class="bi bi-patch-check-fill"></i></div>
            <h5 class="fw-bold">Verified Users</h5>
            <p class="text-muted small mb-0">Upload your valid ID and earn the blue check badge for trusted claiming.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-tile">
            <div class="icon-wrap"><i class="bi bi-search"></i></div>
            <h5 class="fw-bold">Lost & Found Listings</h5>
            <p class="text-muted small mb-0">Report lost or found items and browse listings with filters.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature-tile">
            <div class="icon-wrap"><i class="bi bi-geo-alt-fill"></i></div>
            <h5 class="fw-bold">Safe Pickup</h5>
            <p class="text-muted small mb-0">Claims approved with a code, date, time, and meeting point — only at campus-safe locations.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        @include('partials.claim-flow')
    </div>
    <div class="col-lg-5">
        @include('partials.safe-pickup')
    </div>
</div>
@endsection
