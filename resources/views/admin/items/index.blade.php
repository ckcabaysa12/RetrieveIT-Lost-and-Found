@extends('layouts.app')
@section('title', 'Manage Items')

@section('content')
@include('partials.back-button', ['url' => route('admin.dashboard'), 'label' => 'Back to admin'])

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <p class="section-label mb-0">Lost & found reports</p>
        <h1 class="h4 fw-bold mb-0">Review Items</h1>
    </div>
    <form class="d-flex gap-2" method="GET">
        <select name="type" class="form-select form-select-sm">
            <option value="">All types</option>
            <option value="lost" @selected(request('type')==='lost')>Lost</option>
            <option value="found" @selected(request('type')==='found')>Found</option>
        </select>
        <select name="status" class="form-select form-select-sm">
            <option value="">All status</option>
            @foreach(['available','pending_claim','claimed','returned'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ str_replace('_',' ',$s) }}</option>
            @endforeach
        </select>
        <button class="btn btn-lf btn-sm">Filter</button>
    </form>
</div>

<div class="card-lf">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Item</th><th>User</th><th>Type</th><th>Status</th><th>Update</th></tr></thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td><a href="{{ route('items.show', $item) }}" class="fw-medium">{{ $item->title }}</a></td>
                        <td class="small">{{ $item->user->name }}</td>
                        <td><span class="{{ $item->type === 'found' ? 'badge-found' : 'badge-lost' }}">{{ $item->type }}</span></td>
                        <td class="small">{{ str_replace('_',' ',$item->status) }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.items.status', $item) }}" class="d-flex gap-1">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm">
                                    @foreach(['available','pending_claim','claimed','returned'] as $s)
                                        <option value="{{ $s }}" @selected($item->status===$s)>{{ str_replace('_',' ',$s) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-lf-outline btn-sm">Save</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{ $items->links() }}
@endsection
