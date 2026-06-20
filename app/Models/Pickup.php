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
        'finder_confirmed_at',
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
        ];
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }

    public function isAwaitingOwnerSchedule(): bool
    {
        return in_array($this->status, ['awaiting_schedule', 'reschedule_requested'], true);
    }

    public function isAwaitingFinder(): bool
    {
        return $this->status === 'awaiting_finder';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'awaiting_schedule' => 'Owner needs to propose schedule',
            'awaiting_finder' => 'Waiting for finder to confirm',
            'reschedule_requested' => 'Reschedule requested',
            'confirmed' => 'Pickup confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => $this->status,
        };
    }
}
