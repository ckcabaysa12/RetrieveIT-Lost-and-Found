<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        User::query()
            ->where('role', 'user')
            ->where('verification_status', 'verified')
            ->update([
                'verification_status' => 'pending',
                'is_verified' => false,
            ]);
    }

    public function down(): void
    {
        // Cannot safely restore previous verification state.
    }
};
