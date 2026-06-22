<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pickups', function (Blueprint $table) {
            $table->string('schedule_proposed_by', 10)->nullable()->after('status');
            $table->timestamp('owner_confirmed_schedule_at')->nullable()->after('finder_confirmed_at');
        });

        DB::statement("ALTER TABLE pickups MODIFY COLUMN status ENUM('awaiting_schedule', 'awaiting_finder', 'awaiting_owner', 'reschedule_requested', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'awaiting_schedule'");
    }

    public function down(): void
    {
        DB::table('pickups')->where('status', 'awaiting_owner')->update(['status' => 'awaiting_schedule']);

        Schema::table('pickups', function (Blueprint $table) {
            $table->dropColumn(['schedule_proposed_by', 'owner_confirmed_schedule_at']);
        });

        DB::statement("ALTER TABLE pickups MODIFY COLUMN status ENUM('awaiting_schedule', 'awaiting_finder', 'reschedule_requested', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'awaiting_schedule'");
    }
};
