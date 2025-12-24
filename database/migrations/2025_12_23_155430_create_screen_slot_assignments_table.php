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

            // ===============================
            // CORE RELATIONSHIPS
            // ===============================
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

            // ===============================
            // FESTIVAL / SCREENING CONTEXT
            // ===============================
            // Day number (1–7)
            $table->unsignedTinyInteger('day');

            // Runtime status controlled by admin
            // IMPORTANT: default must be INACTIVE
            $table->enum('status', ['active', 'inactive'])
                ->default('inactive');

            $table->timestamps();

            // ===============================
            // HARD CONSTRAINTS
            // ===============================
            // Same screen cannot have two shows
            // at the same slot on the same day
            $table->unique(
                ['screen_id', 'slot_id', 'day'],
                'uniq_screen_day_slot'
            );
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
