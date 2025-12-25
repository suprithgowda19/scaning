<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->id();

            // Global time definition
            $table->time('start_time');
            $table->time('end_time');

            $table->timestamps();

            // Prevent duplicate slot definitions
            $table->unique(
                ['start_time', 'end_time'],
                'uniq_slots_time_range'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
