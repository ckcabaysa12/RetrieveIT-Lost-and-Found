@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="page-hero mb-4">
    <h1 class="h4 mb-1">My Profile</h1>
    <p class="mb-0">Verification builds trust for safer claiming @include('partials.verified-badge', ['user' => $user])</p>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card-lf p-4 text-center">
            <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px">
                <i class="bi bi-person fs-2 text-muted"></i>
            </div>
            <h4 class="fw-bold">{{ $user->name }}</h4>
            <p class="text-muted mb-1">{{ $user->email }}</p>
            <p class="text-muted small">{{ $user->phone ?? 'Add phone in profile' }}</p>
            <p class="mb-2">
                <span class="badge rounded-pill bg-{{ $user->verification_status === 'verified' ? 'success' : ($user->verification_status === 'rejected' ? 'danger' : 'warning') }}-subtle text-dark">
                    ID: {{ ucfirst($user->verification_status) }}
                </span>
            </p>
            @if($user->id_image)
                <p class="section-label mt-3">ID on file</p>
                <img src="{{ asset('storage/'.$user->id_image) }}" class="img-fluid rounded-3 border" alt="ID" style="max-height:180px">
            @endif
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card-lf p-4">
            <h5 class="fw-bold mb-3">Update profile</h5>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-medium">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Contact number</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Re-upload ID <span class="text-muted fw-normal">(if rejected or updated)</span></label>
                    <input type="file" name="id_image" class="form-control" accept="image/*">
                </div>
                <button class="btn btn-lf">Save changes</button>
            </form>
        </div>
        <div class="card-lf p-3 mt-3">
            <p class="small text-muted mb-0">
                <i class="bi bi-patch-check-fill text-primary"></i>
                <strong>Blue check</strong> appears on your profile, posts, and claims once admin approves your ID.
            </p>
        </div>
    </div>
</div>
@endsection
