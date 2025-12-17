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
        Schema::create('secret_discoveries', function (Blueprint $table) {
            $table->id();
            $table->string('secret_hash', 64)->unique(); // SHA256 of the combo
            $table->string('secret_name'); // Decoded name (BAD FOOD, etc)
            $table->foreignId('play_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('discovered_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secret_discoveries');
    }
};
