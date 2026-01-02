<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_tokens', function (Blueprint $table) {
            $table->id();

            // One token per screen (critical for isolation)
            $table->foreignId('screen_id')
                  ->constrained()
                  ->cascadeOnDelete()
                  ->unique();

            // Random, unguessable token
            $table->string('token', 64)->unique();

            // Optional expiry (festival day / slot based)
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            // Helpful index for middleware lookup
            $table->index(['token', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_tokens');
    }
};
