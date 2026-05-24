@if($user->is_verified)
    <span class="verified-badge" title="Verified User"><span aria-hidden="true">&#x1F535;</span> Verified</span>
@elseif($user->verification_status === 'pending')
    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Pending ID</span>
@elseif($user->verification_status === 'rejected')
    <span class="badge rounded-pill bg-danger-subtle text-danger">ID Rejected</span>
@endif
