<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screen_slot_assignments', function (Blueprint $table) {

            $table->id();

            /**
             * Physical screen where the show runs
             */
            $table->foreignId('screen_id')
                ->constrained('screens')
                ->cascadeOnDelete();

            /**
             * Global time slot (start_time + end_time)
             */
            $table->foreignId('slot_id')
                ->constrained('slots')
                ->cascadeOnDelete();

            /**
             * Movie being screened
             */
            $table->foreignId('movie_id')
                ->constrained('movies')
                ->cascadeOnDelete();

            /**
             * Actual calendar date of the show
             * Comes from scheduler.date
             */
            $table->date('show_date');

            /**
             * Audit timestamps
             */
            $table->timestamps();

            /**
             * HARD CONSTRAINT
             * One screen can have only ONE movie per slot per date
             */
            $table->unique(
                ['screen_id', 'slot_id', 'show_date'],
                'uniq_screen_slot_date'
            );

            /**
             * PERFORMANCE INDEXES
             */
            $table->index(['show_date', 'screen_id'], 'idx_date_screen');
            $table->index(['movie_id', 'show_date'], 'idx_movie_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screen_slot_assignments');
    }
};
