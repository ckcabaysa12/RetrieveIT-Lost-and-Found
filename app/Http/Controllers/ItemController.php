<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = Item::with(['user', 'category', 'claims', 'images'])
            ->whereIn('status', ['available', 'pending_claim'])
            ->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        return view('items.index', [
            'items' => $query->paginate(12)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function myItems(): View
    {
        $items = Item::with(['category', 'claims'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('items.my', compact('items'));
    }

    public function create(Request $request): View
    {
        $relatedLost = null;

        if ($request->filled('from_lost')) {
            $relatedLost = Item::query()
                ->browsable()
                ->where('type', 'lost')
                ->find($request->integer('from_lost'));
        }

        return view('items.create', [
            'categories' => Category::orderBy('name')->get(),
            'relatedLost' => $relatedLost,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:lost,found'],
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'date_reported' => ['required', 'date'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => ['image', 'max:4096'],
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = 'available';
        unset($data['images']);

        $item = Item::create($data);
        $this->attachUploadedImages($item, $request->file('images', []));

        return redirect()->route('items.show', $item)
            ->with('success', 'Item reported successfully.');
    }

    public function edit(Item $item): View
    {
        $this->assertOwnerCanEdit($item);

        $item->load('images');

        return view('items.edit', [
            'item' => $item,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $this->assertOwnerCanEdit($item);

        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'date_reported' => ['required', 'date'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer', 'exists:item_images,id'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
        ]);

        $removeIds = collect($data['remove_images'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $imagesToRemove = $item->images()->whereIn('id', $removeIds)->get();
        if ($imagesToRemove->count() !== $removeIds->count()) {
            abort(403);
        }

        $remainingCount = $item->images()->count() - $imagesToRemove->count();
        $newCount = count($request->file('images', []));

        if ($remainingCount + $newCount > 8) {
            return back()
                ->withErrors(['images' => 'You can have at most 8 photos total.'])
                ->withInput();
        }

        unset($data['remove_images'], $data['images']);

        $item->update($data);

        foreach ($imagesToRemove as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $this->attachUploadedImages($item, $request->file('images', []), $remainingCount);

        return redirect()->route('items.show', $item)
            ->with('success', 'Listing updated successfully.');
    }

    public function show(Item $item): View
    {
        $item->load(['user', 'category', 'claims', 'images']);

        $canClaim = auth()->check()
            && auth()->user()->isVerified()
            && $item->type === 'found'
            && $item->isBrowsable()
            && $item->user_id !== auth()->id();

        $canReportFound = auth()->check()
            && auth()->user()->isVerified()
            && $item->type === 'lost'
            && $item->isBrowsable()
            && $item->user_id !== auth()->id();

        $canEdit = auth()->check()
            && auth()->id() === $item->user_id
            && $item->canBeEditedByOwner();

        return view('items.show', compact('item', 'canClaim', 'canReportFound', 'canEdit'));
    }

    private function assertOwnerCanEdit(Item $item): void
    {
        abort_unless($item->user_id === auth()->id(), 403);
        abort_unless($item->canBeEditedByOwner(), 403, 'This listing can no longer be edited.');
    }

    /**
     * @param  list<UploadedFile>  $files
     */
    private function attachUploadedImages(Item $item, array $files, int $startOrder = 0): void
    {
        foreach ($files as $index => $file) {
            $path = $file->store('items', 'public');
            $item->images()->create([
                'path' => $path,
                'sort_order' => $startOrder + $index,
            ]);
        }

        $this->syncCoverImage($item);
    }

    private function syncCoverImage(Item $item): void
    {
        $coverPath = $item->images()->orderBy('sort_order')->value('path');
        $item->update(['image' => $coverPath]);
    }
}
