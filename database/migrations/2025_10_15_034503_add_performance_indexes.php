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
            // Composite index for daily leaderboard query (user_id + played_at)
            // Helps with: WHERE user_id = X AND played_at BETWEEN ...
            $table->index(['user_id', 'played_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            // Indexes for leaderboard sorting
            $table->index('total_balance');
            $table->index('biggest_win');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plays', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'played_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['total_balance']);
            $table->dropIndex(['biggest_win']);
        });
    }
};
