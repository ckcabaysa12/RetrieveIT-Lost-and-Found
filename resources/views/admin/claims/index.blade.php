@extends('layouts.app')
@section('title', 'Manage Claims')

@section('content')
@include('partials.back-button', ['url' => route('admin.dashboard'), 'label' => 'Back to admin'])

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="section-label mb-0">Trust-based claiming</p>
        <h1 class="h4 fw-bold mb-0">Review Claims</h1>
    </div>
    <form method="GET">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="pending" @selected(request('status')==='pending')>Pending</option>
            <option value="approved" @selected(request('status')==='approved')>Approved</option>
            <option value="rejected" @selected(request('status')==='rejected')>Rejected</option>
        </select>
    </form>
</div>

<div class="card-lf">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Claimant</th><th>Item</th><th>Finder</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach($claims as $claim)
                    <tr>
                        <td>{{ $claim->user->name }} @include('partials.verified-badge', ['user' => $claim->user])</td>
                        <td class="fw-medium">{{ $claim->item->title }}</td>
                        <td class="small">
                            <span class="badge bg-{{ $claim->finder_ownership === 'approved' ? 'success' : ($claim->finder_ownership === 'rejected' ? 'danger' : 'secondary') }}">
                                {{ $claim->finder_ownership }}
                            </span>
                        </td>
                        <td><span class="badge rounded-pill bg-{{ $claim->status === 'approved' ? 'success' : ($claim->status === 'rejected' ? 'danger' : 'warning') }}">{{ $claim->status }}</span></td>
                        <td><a href="{{ route('admin.claims.show', $claim) }}" class="btn btn-lf btn-sm">Review</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{ $claims->links() }}
@endsection
