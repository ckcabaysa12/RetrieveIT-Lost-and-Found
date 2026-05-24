<div class="card-lf p-3 mb-3" style="border-left:4px solid var(--lf-teal)">
    <h6 class="fw-bold small text-uppercase text-muted mb-2">Contact (for pickup coordination)</h6>
    <p class="mb-1 small"><i class="bi bi-envelope me-1"></i> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
    <p class="mb-0 small"><i class="bi bi-telephone me-1"></i>
        @if($user->phone)
            <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
        @else
            <span class="text-muted">No phone on file</span>
        @endif
    </p>
</div>
