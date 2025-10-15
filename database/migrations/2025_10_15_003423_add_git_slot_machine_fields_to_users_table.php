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
            $table->string('github_username')->unique()->after('id');
            $table->integer('total_balance')->default(0)->after('github_username');
            $table->integer('total_commits')->default(0)->after('total_balance');
            $table->integer('biggest_win')->default(0)->after('total_commits');

            $table->index('github_username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['github_username']);
            $table->dropColumn(['github_username', 'total_balance', 'total_commits', 'biggest_win']);
        });
    }
};
