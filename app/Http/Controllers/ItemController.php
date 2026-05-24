<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Services\ItemMatchingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function __construct(private ItemMatchingService $matching) {}

    public function index(Request $request): View
    {
        $query = Item::with(['user', 'category'])
            ->browsable()
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
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $data['user_id'] = auth()->id();
        $data['status'] = 'available';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        return redirect()->route('items.show', $item)
            ->with('success', 'Item reported successfully.');
    }

    public function show(Item $item): View
    {
        $item->load(['user', 'category']);

        $matches = $this->matching->findMatches($item);
        $canClaim = auth()->check()
            && $item->type === 'found'
            && $item->isBrowsable()
            && $item->user_id !== auth()->id();

        $canReportFound = auth()->check()
            && $item->type === 'lost'
            && $item->isBrowsable()
            && $item->user_id !== auth()->id();

        return view('items.show', compact('item', 'matches', 'canClaim', 'canReportFound'));
    }
}
