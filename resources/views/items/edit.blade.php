@extends('layouts.app')
@section('title', 'Edit Listing')

@section('content')
@include('partials.back-button', ['url' => route('items.show', $item), 'label' => 'Back to listing'])

<div class="page-hero mb-4">
    <h1 class="h4 mb-1">Edit Listing</h1>
    <p class="small mb-0">Update details or photos for your {{ $item->type }} item report.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-lf p-4">
            <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Type</label>
                        <div class="form-control bg-light">
                            <span class="{{ $item->type === 'found' ? 'badge-found' : 'badge-lost' }}">{{ ucfirst($item->type) }}</span>
                            <span class="text-muted small ms-2">Cannot be changed after posting</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Category</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $item->category_id) == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Title</label>
                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $item->title) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $item->description) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Location found / lost</label>
                        <input type="text" name="location" class="form-control"
                            value="{{ old('location', $item->location) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Date</label>
                        <input type="date" name="date_reported" class="form-control"
                            value="{{ old('date_reported', $item->date_reported->format('Y-m-d')) }}" required>
                    </div>
                    @if($item->images->isNotEmpty())
                        @php $missingPhotos = $item->images->filter(fn ($img) => ! $img->url())->count(); @endphp
                        @if($missingPhotos > 0)
                            <div class="col-12">
                                <div class="alert alert-warning small mb-0">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    {{ $missingPhotos }} photo{{ $missingPhotos > 1 ? 's' : '' }} could not be loaded (file missing on server).
                                    Check <strong>Remove</strong> below and upload again.
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <label class="form-label fw-medium">Current photos</label>
                            <div class="row g-2">
                                @foreach($item->images as $image)
                                    <div class="col-6 col-md-3">
                                        <label class="d-block border rounded p-2 h-100">
                                            @if($url = $image->url())
                                                <img src="{{ $url }}" alt="" class="img-fluid rounded mb-2"
                                                    style="aspect-ratio:1;object-fit:cover;width:100%">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center rounded bg-light mb-2 text-muted small text-center p-2"
                                                    style="aspect-ratio:1;">
                                                    <span>
                                                        <i class="bi bi-image-alt d-block fs-4 mb-1 opacity-50"></i>
                                                        Unavailable
                                                    </span>
                                                </div>
                                            @endif
                                            <span class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remove_images[]"
                                                    value="{{ $image->id }}" @checked(in_array($image->id, old('remove_images', [])))>
                                                <span class="form-check-label small text-danger">Remove</span>
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <label class="form-label fw-medium">Add photos <span class="text-muted fw-normal">(up to 8 total)</span></label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        <div class="form-text">Add more angles or details. Check photos above to remove any you no longer need.</div>
                        @if($errors->has('images') || $errors->has('images.*'))
                            <div class="text-danger small mt-1">
                                @foreach(array_merge($errors->get('images'), $errors->get('images.*')) as $message)
                                    <div>{{ $message }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-12">
                        <button class="btn btn-lf">Save changes</button>
                        <a href="{{ route('items.show', $item) }}" class="btn btn-lf-outline ms-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-lf p-4 mt-4 border-danger-subtle">
            <h2 class="h6 fw-bold text-danger mb-2">Remove listing</h2>
            <p class="small text-muted mb-3">Permanently delete this post and all of its photos. This cannot be undone.</p>
            @include('partials.remove-item-form', ['item' => $item, 'label' => 'Remove listing'])
        </div>
    </div>
</div>
@endsection
