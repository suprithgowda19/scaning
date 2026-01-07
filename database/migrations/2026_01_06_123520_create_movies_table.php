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

            // Core movie identity
            $table->string('original_title', 255)->nullable()->index();
            $table->string('language', 255)->nullable();

            // Duration in minutes (NOT varchar)
            $table->unsignedSmallInteger('duration')->nullable()
                  ->comment('Duration in minutes');

            // Audit
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
