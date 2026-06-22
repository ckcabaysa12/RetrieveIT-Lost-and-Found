<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $items = Item::with('category')
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

        $coverPath = null;
        foreach ($request->file('images', []) as $index => $file) {
            $path = $file->store('items', 'public');
            $item->images()->create([
                'path' => $path,
                'sort_order' => $index,
            ]);
            if ($index === 0) {
                $coverPath = $path;
            }
        }

        if ($coverPath) {
            $item->update(['image' => $coverPath]);
        }

        return redirect()->route('items.show', $item)
            ->with('success', 'Item reported successfully.');
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

        return view('items.show', compact('item', 'canClaim', 'canReportFound'));
    }
}
