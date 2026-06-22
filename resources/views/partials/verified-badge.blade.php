@if($user->isVerified())
    <i class="bi bi-patch-check-fill text-primary verified-check" title="Verified user" aria-label="Verified user"></i>
@elseif(!($verifiedOnly ?? false))
    @if($user->verification_status === 'pending')
        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Pending ID</span>
    @elseif($user->verification_status === 'rejected')
        <span class="badge rounded-pill bg-danger-subtle text-danger">ID Rejected</span>
    @endif
@endif
