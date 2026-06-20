@extends('layouts.app')
@section('title', 'Categories')

@section('content')
@include('partials.back-button', ['url' => route('admin.dashboard'), 'label' => 'Back to admin'])

<div class="mb-4">
    <p class="section-label mb-0">Item organization</p>
    <h1 class="h4 fw-bold">Manage Categories</h1>
</div>

<form class="card-lf p-3 mb-4" method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    <div class="input-group">
        <input type="text" name="name" class="form-control" placeholder="New category (wallet, phone, bag...)" required>
        <button class="btn btn-lf">Add category</button>
    </div>
</form>

<div class="card-lf">
    @foreach($categories as $category)
        <div class="p-3 border-bottom d-flex flex-wrap align-items-center gap-2">
            <form class="d-flex gap-2 flex-grow-1" method="POST" action="{{ route('admin.categories.update', $category) }}">
                @csrf @method('PUT')
                <input type="text" name="name" class="form-control form-control-sm" value="{{ $category->name }}">
                <button class="btn btn-lf-outline btn-sm">Save</button>
            </form>
            <span class="badge bg-light text-muted">{{ $category->items_count }} items</span>
            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm">Delete</button>
            </form>
        </div>
    @endforeach
</div>
@endsection
