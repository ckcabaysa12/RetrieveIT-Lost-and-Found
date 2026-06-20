<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pickups', function (Blueprint $table) {
            $table->date('reschedule_date')->nullable()->after('reschedule_note');
            $table->string('reschedule_time', 10)->nullable()->after('reschedule_date');
        });
    }

    public function down(): void
    {
        Schema::table('pickups', function (Blueprint $table) {
            $table->dropColumn(['reschedule_date', 'reschedule_time']);
        });
    }
};
