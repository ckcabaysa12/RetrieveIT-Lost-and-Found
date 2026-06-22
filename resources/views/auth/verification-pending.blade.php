@extends('layouts.app')
@section('title', 'ID Verification')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-lf p-4 p-md-5 text-center">
            @if($user->verification_status === 'rejected')
                <div class="icon-wrap mx-auto mb-3" style="width:64px;height:64px;border-radius:16px;background:#fef2f2;color:#dc2626;font-size:1.75rem;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <h1 class="h4 fw-bold mb-2">ID verification rejected</h1>
                <p class="text-muted mb-4">
                    Your submitted ID could not be verified. Please contact the campus admin or
                    <a href="{{ route('profile.show') }}">update your profile</a> with a clearer valid ID photo.
                </p>
            @else
                <div class="icon-wrap mx-auto mb-3" style="width:64px;height:64px;border-radius:16px;background:var(--lf-teal-soft);color:var(--lf-teal);font-size:1.75rem;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <h1 class="h4 fw-bold mb-2">Waiting for admin approval</h1>
                <p class="text-muted mb-4">
                    Your account was created successfully. An admin must review your valid ID before you can
                    browse, report items, or submit claims.
                </p>
            @endif

            @if($url = $user->idImageUrl())
                <div class="mb-4">
                    <p class="small text-muted mb-2">Submitted ID</p>
                    <img src="{{ $url }}" alt="Your ID" class="rounded-3 border" style="max-height:160px">
                </div>
            @endif

            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('profile.show') }}" class="btn btn-lf-outline">View profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">Log out</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
