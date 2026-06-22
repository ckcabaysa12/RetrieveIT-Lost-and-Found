@extends('layouts.app')
@section('title', 'Verify Users')

@section('content')
@include('partials.back-button', ['url' => route('admin.dashboard'), 'label' => 'Back to admin'])

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <p class="section-label mb-0">Blue check system</p>
        <h1 class="h4 fw-bold mb-0">User Verification</h1>
    </div>
    <form method="GET" class="m-0">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="pending" @selected(request('status', 'pending')==='pending')>Pending</option>
            <option value="" @selected(request()->has('status') && request('status')==='')>All statuses</option>
            <option value="verified" @selected(request('status')==='verified')>Verified</option>
            <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
        </select>
    </form>
</div>

@foreach($users as $user)
    <div class="card-lf p-3 mb-3">
        <div class="row align-items-center g-3">
            <div class="col-md-4">
                <strong>{{ $user->name }}</strong> @include('partials.verified-badge', ['user' => $user, 'verifiedOnly' => true])
                <br><small class="text-muted">{{ $user->email }}</small>
            </div>
            <div class="col-md-4 text-center">
                @if($url = $user->idImageUrl())
                    <img src="{{ $url }}" alt="ID" class="rounded-3 border" style="max-height:90px">
                @else
                    <span class="text-muted small">{{ $user->id_image ? 'ID file missing on server' : 'No ID uploaded' }}</span>
                @endif
            </div>
            <div class="col-md-4 text-md-end">
                @if($user->verification_status === 'pending')
                    <form class="d-inline" method="POST" action="{{ route('admin.users.verify', $user) }}">@csrf
                        <button class="btn btn-lf btn-sm"><i class="bi bi-patch-check"></i> Verify</button>
                    </form>
                    <form class="d-inline" method="POST" action="{{ route('admin.users.reject', $user) }}">@csrf
                        <button class="btn btn-outline-danger btn-sm">Reject</button>
                    </form>
                @elseif($user->isVerified())
                    <span class="badge rounded-pill bg-success">Verified</span>
                @else
                    <span class="badge rounded-pill bg-{{ $user->verification_status === 'rejected' ? 'danger' : 'secondary' }}">{{ ucfirst($user->verification_status) }}</span>
                @endif
            </div>
        </div>
    </div>
@endforeach
{{ $users->links() }}
@endsection
