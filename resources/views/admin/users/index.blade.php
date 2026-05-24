@extends('layouts.app')
@section('title', 'Verify Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <p class="section-label mb-0">Blue check system</p>
        <h1 class="h4 fw-bold mb-0">User Verification</h1>
    </div>
    <form method="GET">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All statuses</option>
            <option value="pending" @selected(request('status')==='pending')>Pending</option>
            <option value="verified" @selected(request('status')==='verified')>Verified</option>
            <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
        </select>
    </form>
</div>

@foreach($users as $user)
    <div class="card-lf p-3 mb-3">
        <div class="row align-items-center g-3">
            <div class="col-md-4">
                <strong>{{ $user->name }}</strong> @include('partials.verified-badge', ['user' => $user])
                <br><small class="text-muted">{{ $user->email }}</small>
            </div>
            <div class="col-md-4 text-center">
                @if($user->id_image)
                    <img src="{{ asset('storage/'.$user->id_image) }}" alt="ID" class="rounded-3 border" style="max-height:90px">
                @else
                    <span class="text-muted small">No ID uploaded</span>
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
                @else
                    <span class="badge rounded-pill bg-secondary">{{ ucfirst($user->verification_status) }}</span>
                @endif
            </div>
        </div>
    </div>
@endforeach
{{ $users->links() }}
@endsection
