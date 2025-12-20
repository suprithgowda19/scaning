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
        Schema::create('screens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->integer('capacity')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            // unique per venue so names don’t clash inside same venue
            $table->unique(['venue_id', 'name']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screens');
    }
};
