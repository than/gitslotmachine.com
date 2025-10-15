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
        Schema::create('plays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('repository_id')->constrained()->onDelete('cascade');
            $table->string('commit_hash', 7);
            $table->string('pattern_type');
            $table->string('pattern_name');
            $table->integer('payout');
            $table->integer('repo_balance_after');
            $table->timestamp('played_at');
            $table->timestamps();

            $table->index('user_id');
            $table->index('repository_id');
            $table->index('played_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plays');
    }
};
