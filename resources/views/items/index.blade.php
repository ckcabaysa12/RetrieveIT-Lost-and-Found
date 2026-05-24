@extends('layouts.app')
@section('title', 'Browse Items')

@section('content')
<div class="page-hero mb-4">
    <h1 class="h3 mb-2">Browse Listings</h1>
    <p>
        <strong class="badge-found me-1">Found</strong> → tap <strong>Claim this item</strong> if it's yours.
        <strong class="badge-lost ms-2 me-1">Lost</strong> → tap <strong>I found this item</strong> if you have it.
    </p>
</div>

<form class="card-lf p-3 mb-4" method="GET">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small text-muted">Search</label>
            <input type="text" name="search" class="form-control" placeholder="Title, description, location..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small text-muted">Type</label>
            <select name="type" class="form-select">
                <option value="">All</option>
                <option value="lost" @selected(request('type')==='lost')>Lost</option>
                <option value="found" @selected(request('type')==='found')>Found</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small text-muted">Category</label>
            <select name="category_id" class="form-select">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('category_id')==$cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1"><button class="btn btn-lf w-100">Go</button></div>
        @auth
            <div class="col-md-2"><a href="{{ route('items.create') }}" class="btn btn-amber w-100"><i class="bi bi-plus"></i> Report</a></div>
        @endauth
    </div>
</form>

<div class="row g-3">
    @forelse($items as $item)
        <div class="col-md-6 col-lg-4">
            <div class="card-lf item-card h-100 d-flex flex-column">
                <div class="card-img-wrap">
                    @if($item->image)
                        <img src="{{ asset('storage/'.$item->image) }}" alt="">
                    @else
                        <div class="placeholder-img"><i class="bi bi-image"></i></div>
                    @endif
                </div>
                <div class="p-3 flex-grow-1 d-flex flex-column">
                    <div class="mb-2">
                        <span class="{{ $item->type === 'found' ? 'badge-found' : 'badge-lost' }}">{{ ucfirst($item->type) }}</span>
                        <span class="badge bg-light text-muted">{{ $item->category->name }}</span>
                    </div>
                    <h5 class="fw-bold mb-1">{{ $item->title }}</h5>
                    <p class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($item->description, 70) }}</p>
                    <p class="small mb-1"><i class="bi bi-geo-alt text-muted"></i> {{ $item->location }}</p>
                    <p class="small mb-3">{{ $item->user->name }} @include('partials.verified-badge', ['user' => $item->user])</p>
                    <div class="mt-auto">
                        @include('partials.item-action-buttons', ['item' => $item])
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-lf p-5 text-center text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                No items match your search.
            </div>
        </div>
    @endforelse
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
