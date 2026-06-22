<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pickup extends Model
{
    protected $fillable = [
        'claim_id',
        'location',
        'date',
        'time',
        'status',
        'schedule_proposed_by',
        'finder_confirmed_at',
        'owner_confirmed_schedule_at',
        'reschedule_note',
        'reschedule_date',
        'reschedule_time',
        'reschedule_requested_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'reschedule_date' => 'date',
            'finder_confirmed_at' => 'datetime',
            'owner_confirmed_schedule_at' => 'datetime',
        ];
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function canBeScheduled(): bool
    {
        return in_array($this->status, ['awaiting_schedule', 'reschedule_requested'], true);
    }

    public function isAwaitingOwnerSchedule(): bool
    {
        return $this->canBeScheduled();
    }

    public function isAwaitingFinder(): bool
    {
        return $this->status === 'awaiting_finder';
    }

    public function isAwaitingOwner(): bool
    {
        return $this->status === 'awaiting_owner';
    }

    public function hasFinderAvailability(): bool
    {
        return $this->status === 'reschedule_requested'
            && $this->reschedule_requested_by === 'finder'
            && $this->reschedule_date
            && $this->reschedule_time;
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'awaiting_schedule' => 'Waiting for a pickup schedule',
            'awaiting_finder' => 'Waiting for finder to confirm',
            'awaiting_owner' => 'Waiting for owner to accept schedule',
            'reschedule_requested' => 'Reschedule requested',
            'confirmed' => 'Pickup confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => $this->status,
        };
    }
}
