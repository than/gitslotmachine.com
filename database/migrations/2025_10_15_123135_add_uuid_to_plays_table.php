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
        // Check if column already exists (idempotent migration)
        if (!Schema::hasColumn('plays', 'uuid')) {
            Schema::table('plays', function (Blueprint $table) {
                $table->uuid('uuid')->after('id')->nullable()->unique();
            });

            // Backfill UUIDs for existing plays
            DB::statement('UPDATE plays SET uuid = gen_random_uuid() WHERE uuid IS NULL');

            // Make non-nullable after backfill
            Schema::table('plays', function (Blueprint $table) {
                $table->uuid('uuid')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plays', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
