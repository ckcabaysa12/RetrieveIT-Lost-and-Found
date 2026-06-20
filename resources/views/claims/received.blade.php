@extends('layouts.app')
@section('title', 'Claims on My Found Items')

@section('content')
@include('partials.back-button', ['url' => route('dashboard'), 'label' => 'Back to dashboard'])

<div class="page-hero mb-4">
    <h1 class="h4 mb-1">Claims on My Found Items</h1>
    <p class="mb-0">Review claimant details before handing over any item.</p>
</div>

<div class="card-lf">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Item</th>
                    <th>Claimant</th>
                    <th>Ownership</th>
                    <th>Status</th>
                    <th>Pickup</th>
                    <th>Claim message</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($claims as $claim)
                    <tr>
                        <td>
                            <div class="fw-medium">{{ $claim->item->title }}</div>
                            <div class="small text-muted">{{ $claim->item->category->name }}</div>
                        </td>
                        <td>
                            <div class="fw-medium">{{ $claim->user->name }}</div>
                            <div class="small text-muted">{{ $claim->user->email }}</div>
                            @if($claim->status === 'approved' && $claim->user->phone)
                                <div class="small"><i class="bi bi-telephone"></i> {{ $claim->user->phone }}</div>
                            @endif
                        </td>
                        <td class="small">
                            @if($claim->isAwaitingFinderOwnershipReview())
                                <a href="{{ route('claims.show', $claim) }}" class="btn btn-lf btn-sm">Review owner</a>
                            @else
                                <span class="badge bg-{{ $claim->finder_ownership === 'approved' ? 'success' : 'danger' }}">{{ $claim->finder_ownership }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($claim->status) }}
                            </span>
                        </td>
                        <td class="small">
                            @if($claim->pickup)
                                {{ $claim->pickup->statusLabel() }}
                            @else — @endif
                        </td>
                        <td class="small text-muted">{{ \Illuminate\Support\Str::limit($claim->claim_message, 80) }}</td>
                        <td>
                            <a href="{{ route('claims.show', $claim) }}" class="btn btn-lf-outline btn-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No one has submitted a claim for your found items yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $claims->links() }}
@endsection
