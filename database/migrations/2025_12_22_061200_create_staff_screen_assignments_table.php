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
             * Staff user (role = staff)
             */
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /**
             * Venue (explicit, avoids joins at runtime)
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
             * Whether this assignment is active
             * (staff dashboard uses only active ones)
             */
            $table->boolean('active')
                ->default(true)
                ->index();

            /**
             * Audit timestamps
             */
            $table->timestamps();

            /* -----------------------------------------
             | HARD CONSTRAINTS (IMPORTANT)
             |------------------------------------------*/

            /**
             * 1. Prevent duplicate assignment of same screen
             *    to the same staff
             */
            $table->unique(
                ['user_id', 'screen_id'],
                'uniq_staff_screen'
            );

            /**
             * 2. A staff can have ONLY ONE active screen
             *    (critical for scanning & dashboard)
             */
            $table->unique(
                ['user_id'],
                'uniq_staff_one_screen'
            );

            /* -----------------------------------------
             | PERFORMANCE INDEXES
             |------------------------------------------*/
            $table->index(['screen_id', 'active']);
            $table->index(['venue_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_screen_assignments');
    }
};
