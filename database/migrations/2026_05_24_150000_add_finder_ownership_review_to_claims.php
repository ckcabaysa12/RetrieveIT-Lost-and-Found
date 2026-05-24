<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->enum('finder_ownership', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('status');
            $table->text('finder_ownership_note')->nullable()->after('finder_ownership');
            $table->timestamp('finder_reviewed_at')->nullable()->after('finder_ownership_note');
        });
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['finder_ownership', 'finder_ownership_note', 'finder_reviewed_at']);
        });
    }
};
