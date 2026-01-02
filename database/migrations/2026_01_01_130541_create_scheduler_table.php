<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedulers', function (Blueprint $table) {
            $table->id();

            // Explicit ownership
            $table->foreignId('venue_id')
                ->constrained('venues')
                ->restrictOnDelete();

            $table->foreignId('screen_id')
                ->constrained('screens')
                ->restrictOnDelete();

            // Snapshot content (exactly one required)
            $table->string('movie_title')->nullable();
            $table->string('event_title')->nullable();

            $table->string('language')->nullable();
            $table->unsignedSmallInteger('duration')->nullable(); // minutes

            // Mandatory timing
            $table->date('show_date');
            $table->time('start_time');

            // Operational control
            $table->boolean('is_active')->default(true);

            // Prevent duplicate starts per screen/day
            $table->unique(
                ['screen_id', 'show_date', 'start_time'],
                'uniq_screen_date_time'
            );

            // Indexes
            $table->index(['venue_id', 'show_date']);
            $table->index(['screen_id', 'show_date']);
            $table->index('movie_title');
            $table->index('event_title');

            $table->timestamps();
        });

        /**
         * Enforce: exactly one of movie_title OR event_title
         */
        DB::statement("
            ALTER TABLE schedulers
            ADD CONSTRAINT chk_movie_or_event
            CHECK (
                (movie_title IS NOT NULL AND event_title IS NULL)
                OR
                (movie_title IS NULL AND event_title IS NOT NULL)
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('schedulers');
    }
};
