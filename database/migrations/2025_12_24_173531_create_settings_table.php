<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Canonical key, e.g. scan_grace_before
            $table->string('key', 100)->unique();

            // Store as string; cast at runtime
            $table->string('value', 255);

            // Optional: human context (admin UI later)
            $table->string('description', 255)->nullable();

            $table->timestamps();

            // Explicit index for hot path reads (even though unique creates one)
            $table->index('key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
