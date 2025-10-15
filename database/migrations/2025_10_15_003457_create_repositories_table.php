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
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('owner');
            $table->string('name');
            $table->string('github_url');
            $table->integer('balance')->default(100);
            $table->integer('total_commits')->default(0);
            $table->string('last_commit_hash')->nullable();
            $table->timestamp('last_played_at')->nullable();
            $table->timestamps();

            $table->unique(['owner', 'name']);
            $table->index('owner');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};
