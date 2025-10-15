<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('plays', function (Blueprint $table) {
            $table->index('pattern_type'); // For pattern distribution queries
            $table->index(['played_at', 'pattern_type']); // For time-series + pattern queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plays', function (Blueprint $table) {
            $table->dropIndex(['pattern_type']);
            $table->dropIndex(['played_at', 'pattern_type']);
        });
    }
};
