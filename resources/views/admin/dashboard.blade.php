@extends('layouts.app')
@section('title', 'Admin')

@section('content')
<div class="page-hero mb-4">
    <h1 class="h4 mb-1">Admin Panel</h1>
    <p class="mb-0">Verify users, review claims, and monitor campus lost & found activity.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md"><div class="stat-pill"><div class="num">{{ $stats['users'] }}</div><div class="lbl">Users</div></div></div>
    <div class="col-6 col-md"><div class="stat-pill" style="border-color:#fcd34d"><div class="num text-warning">{{ $stats['pending_verifications'] }}</div><div class="lbl">Pending IDs</div></div></div>
    <div class="col-6 col-md"><div class="stat-pill"><div class="num">{{ $stats['items'] }}</div><div class="lbl">Items</div></div></div>
    <div class="col-6 col-md"><div class="stat-pill"><div class="num">{{ $stats['available_items'] }}</div><div class="lbl">Available</div></div></div>
    <div class="col-6 col-md"><div class="stat-pill"><div class="num text-danger">{{ $stats['pending_claims'] }}</div><div class="lbl">Pending Claims</div></div></div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="{{ route('admin.users.index') }}" class="admin-nav-pill"><i class="bi bi-person-check"></i> Verify Users</a>
    <a href="{{ route('admin.items.index') }}" class="admin-nav-pill"><i class="bi bi-box"></i> Items</a>
    <a href="{{ route('admin.claims.index') }}" class="admin-nav-pill"><i class="bi bi-clipboard-check"></i> Claims</a>
    <a href="{{ route('admin.categories.index') }}" class="admin-nav-pill"><i class="bi bi-tags"></i> Categories</a>
    <a href="{{ route('admin.reports.index') }}" class="admin-nav-pill"><i class="bi bi-bar-chart"></i> Reports</a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-lf">
            <div class="card-header">Pending ID Verifications</div>
            <ul class="list-group list-group-flush">
                @forelse($pendingUsers as $u)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $u->name }}<br><small class="text-muted">{{ $u->email }}</small></span>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-lf btn-sm">Review</a>
                    </li>
                @empty
                    <li class="list-group-item text-muted small">All caught up!</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-lf">
            <div class="card-header">Recent Claims</div>
            <ul class="list-group list-group-flush">
                @foreach($recentClaims as $c)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="small">{{ $c->user->name }} → <strong>{{ $c->item->title }}</strong></span>
                        <a href="{{ route('admin.claims.show', $c) }}" class="btn btn-lf-outline btn-sm">Review</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
