<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screens', function (Blueprint $table) {

            $table->id();

            /**
             * Venue this screen belongs to
             */
            $table->foreignId('venue_id')
                ->constrained('venues')
                ->cascadeOnDelete();

            /**
             * Scheduler screen identifier
             * Example: "Audi 9"
             */
            $table->string('name', 100);

            /**
             * Optional human-friendly label
             */
            $table->string('display_name', 150)
                ->nullable();

            /**
             * Physical capacity of the screen
             * (seat-level logic can replace this later)
             */
            $table->unsignedInteger('capacity');

            /**
             * Audit timestamps
             */
            $table->timestamps();

            /**
             * HARD CONSTRAINT
             * Same screen name cannot repeat inside a venue
             */
            $table->unique(
                ['venue_id', 'name'],
                'uniq_venue_screen'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};
