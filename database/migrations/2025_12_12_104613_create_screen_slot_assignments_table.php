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
        Schema::create('screen_slot_assignments', function (Blueprint $table) {
            $table->id();

            // Core foreign keys
            $table->foreignId('venue_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('screen_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('slot_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('movie_id')
                ->constrained()
                ->cascadeOnDelete();

            // Festival day (1–7)
            $table->unsignedTinyInteger('day'); // 1: Day1 ... 7: Day7

            // Show status
            $table->enum('status', ['active', 'inactive'])
                ->default('active');

            $table->timestamps();

            // Unique combination prevents double scheduling:
            // Same screen cannot have 2 movies at the same slot on the same day.
            $table->unique(['venue_id', 'screen_id', 'slot_id', 'day'], 'ssa_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screen_slot_assignments');
    }
};
