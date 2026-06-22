<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'title',
        'description',
        'location',
        'date_reported',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_reported' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ItemImage::class)->orderBy('sort_order');
    }

    public function imageUrl(): ?string
    {
        $fromGallery = $this->relationLoaded('images')
            ? $this->images->first()?->url()
            : $this->images()->first()?->url();

        return $fromGallery ?? \App\Support\MediaUrl::for($this->image);
    }

    /**
     * @return list<string>
     */
    public function imageUrls(): array
    {
        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            return $this->images->map(fn (ItemImage $image) => $image->url())->filter()->values()->all();
        }

        if (! $this->relationLoaded('images')) {
            $fromDb = $this->images()->get()->map(fn (ItemImage $image) => $image->url())->filter()->values()->all();
            if ($fromDb !== []) {
                return $fromDb;
            }
        }

        $legacy = \App\Support\MediaUrl::for($this->image);

        return $legacy ? [$legacy] : [];
    }

    public function scopeBrowsable(Builder $query): Builder
    {
        return $query
            ->where('status', 'available')
            ->whereDoesntHave('claims', function (Builder $claimQuery) {
                $claimQuery->whereIn('status', ['pending', 'approved']);
            });
    }

    public function hasActiveClaim(): bool
    {
        return $this->claims()
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }

    public function isBrowsable(): bool
    {
        return $this->status === 'available' && ! $this->hasActiveClaim();
    }
}
