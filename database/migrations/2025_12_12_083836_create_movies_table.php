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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();

            $table->string('title')->index();
            $table->string('language', 50)->index();

            // poster image stored in storage/app/public/movies
            $table->string('poster_path')->nullable();

            // movie duration in minutes
            $table->unsignedInteger('duration')->nullable();

            $table->text('description')->nullable();

            // instead of delete, admin can deactivate
            $table->enum('status', ['active', 'inactive'])
                  ->default('active')
                  ->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
