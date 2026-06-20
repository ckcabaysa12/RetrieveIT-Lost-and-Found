@extends('layouts.app')
@section('title', 'Home')

@section('content')
<section class="page-hero mb-5">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <p class="section-label text-white opacity-75 mb-2">IPT Project</p>
            <h1 class="display-6 mb-2">{{ config('app.name') }}</h1>
            <p class="lead mb-4">Web-Based Lost and Found Management System</p>
            <p class="mb-4 opacity-90">
                A web-based system with <strong>user verification</strong>, <strong>trust-based claiming</strong>,
                and <strong>safe pickup scheduling</strong> so lost items get back to their owners responsibly.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('items.index') }}" class="btn btn-amber btn-lg">Browse Items</a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg">Sign up with ID</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Log in</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">My Dashboard</a>
                @endguest
            </div>
        </div>
        <div class="col-lg-4 d-none d-lg-block text-center position-relative" style="z-index:1">
            @include('partials.logo', ['class' => 'site-logo--hero'])
        </div>
    </div>
</section>

<div class="row g-4 mb-5">
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

<div class="row g-4 mb-5">
    <div class="col-lg-7">
        <p class="section-label">For students & staff</p>
        <h2 class="h4 fw-bold mb-3">What you can do</h2>
        <div class="row g-2">
            @foreach([
                ['bi-person-plus', 'Register / Login with valid ID'],
                ['bi-person-badge', 'Profile with verification status'],
                ['bi-exclamation-circle', 'Report lost items'],
                ['bi-check-circle', 'Report found items'],
                ['bi-grid', 'Browse item listings'],
                ['bi-chat-left-text', 'Submit claim requests'],
                ['bi-hourglass-split', 'Track claim status'],
                ['bi-calendar-check', 'View pickup instructions'],
            ] as [$icon, $label])
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-2 p-2 rounded-3 bg-white border">
                        <i class="bi {{ $icon }} text-primary" style="color:var(--lf-teal)!important"></i>
                        <span class="small fw-medium">{{ $label }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="col-lg-5">
        @include('partials.claim-flow')
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-12">
        @include('partials.safe-pickup')
    </div>
</div>
@endsection
