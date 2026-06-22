@extends('layouts.app')
@section('title', 'Profile')

@section('content')
@include('partials.back-button', ['url' => auth()->user()->isVerified() || auth()->user()->isAdmin() ? route('dashboard') : route('verification.pending'), 'label' => auth()->user()->isVerified() || auth()->user()->isAdmin() ? 'Back to dashboard' : 'Back'])

<div class="page-hero mb-4">
    <h1 class="h4 mb-1">My Profile</h1>
    <p class="mb-0">Manage your account and verification status.</p>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card-lf p-4 text-center">
            @include('partials.user-avatar', ['user' => $user, 'size' => 96])
            <h4 class="fw-bold mt-3 mb-0 d-inline-flex align-items-center justify-content-center gap-1 flex-wrap">
                {{ $user->name }}
                @include('partials.verified-badge', ['user' => $user, 'verifiedOnly' => true])
            </h4>
            <p class="text-muted mb-1">{{ $user->email }}</p>
            <p class="text-muted small">{{ $user->phone ?? 'No contact number yet' }}</p>
            @if(!$user->isVerified())
                <p class="mb-2">
                    <span class="badge rounded-pill bg-{{ $user->verification_status === 'rejected' ? 'danger' : 'warning' }}-subtle text-dark">
                        ID: {{ ucfirst($user->verification_status) }}
                    </span>
                </p>
            @endif
            @if($user->id_image)
                <p class="section-label mt-3">Valid ID on file</p>
                <img src="{{ asset('storage/'.$user->id_image) }}" class="img-fluid rounded-3 border" alt="Valid ID" style="max-height:180px">
            @endif
            <button type="button" class="btn btn-lf mt-4" id="toggle-profile-edit">
                <i class="bi bi-pencil me-1"></i> Edit profile
            </button>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card-lf p-4 d-none" id="profile-edit-panel">
            <h5 class="fw-bold mb-3">Edit profile</h5>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-medium">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    <x-input-error :messages="$errors->get('name')" />
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Contact number</label>
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                    <x-input-error :messages="$errors->get('phone')" />
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Profile photo</label>
                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                    <x-input-error :messages="$errors->get('profile_image')" />
                </div>
                <div class="mb-3">
                    <label class="form-label fw-medium">Update valid ID <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="file" name="id_image" class="form-control" accept="image/*">
                    <x-input-error :messages="$errors->get('id_image')" />
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-lf">Save changes</button>
                    <button type="button" class="btn btn-lf-outline" id="cancel-profile-edit">Cancel</button>
                </div>
            </form>
        </div>
        <div class="card-lf p-3 mt-3" id="profile-help-panel">
            <p class="small text-muted mb-0">
                @if($user->isVerified())
                    <i class="bi bi-patch-check-fill text-primary"></i>
                    The blue check appears beside your name once your valid ID is verified.
                @elseif($user->verification_status === 'rejected')
                    <i class="bi bi-x-circle text-danger"></i>
                    Your ID was rejected. Update your ID photo below and contact an admin to review again.
                @else
                    <i class="bi bi-hourglass-split text-warning"></i>
                    Your ID is pending admin review. You can browse the home page, but other features unlock after approval.
                @endif
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const editPanel = document.getElementById('profile-edit-panel');
    const toggleBtn = document.getElementById('toggle-profile-edit');
    const cancelBtn = document.getElementById('cancel-profile-edit');

    toggleBtn?.addEventListener('click', () => {
        editPanel.classList.remove('d-none');
        toggleBtn.classList.add('d-none');
    });

    cancelBtn?.addEventListener('click', () => {
        editPanel.classList.add('d-none');
        toggleBtn.classList.remove('d-none');
    });

    @if($errors->any())
        editPanel?.classList.remove('d-none');
        toggleBtn?.classList.add('d-none');
    @endif
</script>
@endpush
