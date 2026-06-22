@extends('layouts.app')
@section('title', 'My Items')

@section('content')
@include('partials.back-button', ['url' => route('home'), 'label' => 'Back to home'])

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <p class="section-label mb-0">Your reports</p>
        <h1 class="h4 fw-bold mb-0">My Items</h1>
    </div>
    <a href="{{ route('items.create') }}" class="btn btn-lf"><i class="bi bi-plus-lg"></i> Report Item</a>
</div>

<div class="card-lf">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Item</th><th>Type</th><th>Status</th><th>Date</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="fw-medium">{{ $item->title }}</td>
                        <td><span class="{{ $item->type === 'found' ? 'badge-found' : 'badge-lost' }}">{{ $item->type }}</span></td>
                        <td><span class="badge-status bg-light text-muted">{{ str_replace('_', ' ', $item->status) }}</span></td>
                        <td class="small text-muted">{{ $item->date_reported->format('M d, Y') }}</td>
                        <td><a href="{{ route('items.show', $item) }}" class="btn btn-lf-outline btn-sm">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No items yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $items->links() }}
@endsection
