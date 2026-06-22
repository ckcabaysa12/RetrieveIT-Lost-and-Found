@extends('layouts.app')
@section('title', 'Report Item')

@section('content')
@include('partials.back-button', ['url' => route('items.index'), 'label' => 'Back to listings'])

<div class="page-hero mb-4">
    <h1 class="h4 mb-1">Report an Item</h1>
    <p class="small mb-0">Lost something? Found something? Let the campus community help.</p>
</div>

@if($relatedLost ?? null)
    <div class="alert alert-info mb-4">
        <i class="bi bi-link-45deg me-1"></i>
        You are reporting a <strong>found</strong> item for the lost post:
        <strong>{{ $relatedLost->title }}</strong> ({{ $relatedLost->location }}).
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-lf p-4">
            <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Type</label>
                        <select name="type" id="item-type" class="form-select" required>
                            <option value="lost" @selected(old('type', $relatedLost ? 'found' : '') === 'lost')>I lost an item</option>
                            <option value="found" @selected(old('type', $relatedLost ? 'found' : '') === 'found')>I found an item</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Category</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $relatedLost?->category_id) == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Title</label>
                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $relatedLost?->title) }}"
                            placeholder="e.g. Black wallet with school ID" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" class="form-control" rows="4" required placeholder="Details that help identify the item...">{{ old('description', $relatedLost ? "Found item matching lost report: {$relatedLost->title}. {$relatedLost->description}" : '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Location found / lost</label>
                        <input type="text" name="location" class="form-control"
                            value="{{ old('location', $relatedLost?->location) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Date</label>
                        <input type="date" name="date_reported" class="form-control"
                            value="{{ old('date_reported', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Photos <span class="text-muted fw-normal">(optional, up to 8)</span></label>
                        <input type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
                        <div class="form-text">Up to 8 photos, 4 MB each. JPG or PNG works best (save iPhone photos as JPEG if upload fails).</div>
                        @if($errors->has('images') || $errors->has('images.*'))
                            <div class="text-danger small mt-1">
                                @foreach(array_merge($errors->get('images'), $errors->get('images.*')) as $message)
                                    <div>{{ $message }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-12">
                        <button class="btn btn-lf">Submit Report</button>
                        <a href="{{ route('items.index') }}" class="btn btn-lf-outline ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
