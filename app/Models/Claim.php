<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Claim extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'claim_message',
        'claim_code',
        'status',
        'finder_ownership',
        'finder_ownership_note',
        'finder_reviewed_at',
        'owner_confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'finder_reviewed_at' => 'datetime',
            'owner_confirmed_at' => 'datetime',
        ];
    }

    public function isConfirmedByOwner(): bool
    {
        return $this->owner_confirmed_at !== null;
    }

    public function isAwaitingFinderOwnershipReview(): bool
    {
        return $this->status === 'pending' && $this->finder_ownership === 'pending';
    }

    public function isAwaitingAdminConfirmation(): bool
    {
        return $this->status === 'pending' && $this->finder_ownership === 'approved';
    }

    public function finderOwnershipLabel(): string
    {
        return match ($this->finder_ownership) {
            'pending' => 'Waiting for finder to verify owner',
            'approved' => 'Finder confirmed this is the owner',
            'rejected' => 'Finder rejected ownership',
            default => $this->finder_ownership,
        };
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pickup(): HasOne
    {
        return $this->hasOne(Pickup::class);
    }
}
