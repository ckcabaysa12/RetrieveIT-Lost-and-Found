@extends('layouts.app')
@section('title', 'My Claims')

@section('content')
@include('partials.back-button', ['url' => route('home'), 'label' => 'Back to home'])

<div class="page-hero mb-4">
    <h1 class="h4 mb-1">My Claims</h1>
    <p class="mb-0">Track status, claim codes, and pickup instructions.</p>
</div>

<div class="card-lf">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Item</th><th>Ownership</th><th>Claim</th><th>Code</th><th>Pickup</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($claims as $claim)
                    <tr>
                        <td class="fw-medium">{{ $claim->item->title }}</td>
                        <td class="small">{{ $claim->finderOwnershipLabel() }}</td>
                        <td>
                            <span class="badge rounded-pill bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ $claim->status }}
                            </span>
                        </td>
                        <td><code>{{ $claim->claim_code ?? '—' }}</code></td>
                        <td class="small">
                            @if($claim->pickup)
                                @if($claim->pickup->location)
                                    {{ $claim->pickup->location }}<br>
                                @endif
                                @if($claim->pickup->date)
                                    <span class="text-muted">{{ $claim->pickup->date->format('M d') }} · {{ $claim->pickup->time }}</span>
                                @else
                                    <span class="text-muted">{{ $claim->pickup->statusLabel() }}</span>
                                @endif
                            @else
                                —
                            @endif
                        </td>
                        <td><a href="{{ route('claims.show', $claim) }}" class="btn btn-lf-outline btn-sm">Details</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No claims yet. Browse found items and claim a found item.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $claims->links() }}
@endsection
