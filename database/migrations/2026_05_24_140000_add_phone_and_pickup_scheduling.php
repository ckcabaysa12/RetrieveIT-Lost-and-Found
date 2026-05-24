<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
        });

        Schema::table('pickups', function (Blueprint $table) {
            $table->timestamp('finder_confirmed_at')->nullable()->after('status');
            $table->text('reschedule_note')->nullable()->after('finder_confirmed_at');
            $table->string('reschedule_requested_by', 10)->nullable()->after('reschedule_note');
        });

        DB::table('pickups')->where('status', 'scheduled')->update(['status' => 'confirmed']);

        DB::statement("ALTER TABLE pickups MODIFY COLUMN location VARCHAR(255) NULL");
        DB::statement('ALTER TABLE pickups MODIFY COLUMN date DATE NULL');
        DB::statement('ALTER TABLE pickups MODIFY COLUMN time TIME NULL');
        DB::statement("ALTER TABLE pickups MODIFY COLUMN status ENUM('awaiting_schedule', 'awaiting_finder', 'reschedule_requested', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'awaiting_schedule'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::table('pickups', function (Blueprint $table) {
            $table->dropColumn(['finder_confirmed_at', 'reschedule_note', 'reschedule_requested_by']);
        });
    }
};
