@extends('layouts.app')
@section('title', 'Admin')

@section('content')
@include('partials.back-button', ['url' => route('dashboard'), 'label' => 'Back to dashboard'])

<div class="page-hero mb-4">
    <h1 class="h4 mb-1">Admin Panel</h1>
    <p class="mb-0">Review claims, manage items, and monitor campus lost & found activity.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="stat-pill"><div class="num">{{ $stats['users'] }}</div><div class="lbl">Users</div></div></div>
    <div class="col-6 col-md-3"><div class="stat-pill"><div class="num">{{ $stats['items'] }}</div><div class="lbl">Items</div></div></div>
    <div class="col-6 col-md-3"><div class="stat-pill"><div class="num">{{ $stats['available_items'] }}</div><div class="lbl">Available</div></div></div>
    <div class="col-6 col-md-3"><div class="stat-pill"><div class="num text-danger">{{ $stats['pending_claims'] }}</div><div class="lbl">Pending Claims</div></div></div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('admin.items.index') }}" class="admin-nav-pill"><i class="bi bi-box"></i> Items</a>
    <a href="{{ route('admin.claims.index') }}" class="admin-nav-pill"><i class="bi bi-clipboard-check"></i> Claims</a>
    <a href="{{ route('admin.categories.index') }}" class="admin-nav-pill"><i class="bi bi-tags"></i> Categories</a>
    <a href="{{ route('admin.reports.index') }}" class="admin-nav-pill"><i class="bi bi-bar-chart"></i> Reports</a>
</div>

<div class="card-lf">
    <div class="card-header">Recent Claims</div>
    <ul class="list-group list-group-flush">
        @forelse($recentClaims as $c)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span class="small">{{ $c->user->name }} → <strong>{{ $c->item->title }}</strong></span>
                <a href="{{ route('admin.claims.show', $c) }}" class="btn btn-lf-outline btn-sm">Review</a>
            </li>
        @empty
            <li class="list-group-item text-muted small">No claims yet.</li>
        @endforelse
    </ul>
</div>
@endsection
