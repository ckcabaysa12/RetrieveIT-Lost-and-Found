<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Collection;

class ItemMatchingService
{
    public function findMatches(Item $item, int $limit = 5): Collection
    {
        $oppositeType = $item->type === 'lost' ? 'found' : 'lost';
        $keywords = collect(preg_split('/\s+/', strtolower($item->title)))
            ->filter(fn (string $word) => strlen($word) > 2)
            ->unique()
            ->values();

        $query = Item::query()
            ->with(['user', 'category'])
            ->browsable()
            ->where('type', $oppositeType)
            ->where('id', '!=', $item->id);

        if ($item->category_id) {
            $query->where('category_id', $item->category_id);
        }

        if ($item->location) {
            $location = $item->location;
            $query->where(function ($q) use ($location) {
                $q->where('location', 'like', "%{$location}%");
            });
        }

        $start = $item->date_reported->copy()->subDays(7);
        $end = $item->date_reported->copy()->addDays(7);
        $query->whereBetween('date_reported', [$start, $end]);

        if ($keywords->isNotEmpty()) {
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('title', 'like', "%{$word}%")
                        ->orWhere('description', 'like', "%{$word}%");
                }
            });
        }

        return $query->latest()->limit($limit)->get();
    }
}
