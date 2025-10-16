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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('moderation_status', ['approved', 'pending', 'rejected'])
                ->default('approved')
                ->after('github_username');
            $table->timestamp('moderated_at')->nullable()->after('moderation_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['moderation_status', 'moderated_at']);
        });
    }
};
