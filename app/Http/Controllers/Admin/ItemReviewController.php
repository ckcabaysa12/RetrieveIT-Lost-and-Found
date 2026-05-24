<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Item::with(['user', 'category'])->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('admin.items.index', [
            'items' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function updateStatus(Request $request, Item $item): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:available,pending_claim,claimed,returned'],
        ]);

        $item->update($data);

        return back()->with('success', 'Item status updated.');
    }
}
