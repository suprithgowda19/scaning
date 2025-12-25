<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {

            $table->id();

            /**
             * External scheduler / festival film identifier
             * Comes from scheduler.film_id
             */
            $table->unsignedInteger('external_film_id')
                  ->nullable()
                  ->unique();

            /**
             * Movie titles
             */
            $table->string('title', 255);              // English / primary title
            $table->string('original_title', 255)
                  ->nullable();                        // Local / festival title

            /**
             * Metadata
             */
            $table->unsignedSmallInteger('duration')   // minutes
                  ->nullable();

            $table->string('language', 100)
                  ->nullable();

            $table->string('country', 100)
                  ->nullable();

            $table->string('year', 10)
                  ->nullable();

            $table->string('director', 255)
                  ->nullable();

            /**
             * Classification (Film, Documentary, etc.)
             */
            $table->string('category', 100)
                  ->nullable();

            /**
             * Audit timestamps
             */
            $table->timestamps();

            /**
             * Performance indexes
             */
            $table->index('title');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
