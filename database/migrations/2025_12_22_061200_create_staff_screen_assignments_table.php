<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_screen_assignments', function (Blueprint $table) {

            $table->id();

            /**
             * Staff user
             */
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /**
             * Venue (explicit for safety & filtering)
             */
            $table->foreignId('venue_id')
                ->constrained('venues')
                ->cascadeOnDelete();

            /**
             * Screen assigned to staff
             */
            $table->foreignId('screen_id')
                ->constrained('screens')
                ->cascadeOnDelete();

            /**
             * Assignment status
             */
            $table->boolean('active')
                ->default(true)
                ->index();

            /**
             * Audit timestamps
             */
            $table->timestamps();

            /**
             * HARD CONSTRAINTS
             * 1. Prevent duplicate assignment
             * 2. Ensure uniqueness per venue
             */
            $table->unique(
                ['user_id', 'venue_id', 'screen_id'],
                'uniq_staff_venue_screen'
            );

            /**
             * Performance indexes
             */
            $table->index(['user_id', 'active']);
            $table->index(['screen_id', 'active']);
            $table->index(['venue_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_screen_assignments');
    }
};
