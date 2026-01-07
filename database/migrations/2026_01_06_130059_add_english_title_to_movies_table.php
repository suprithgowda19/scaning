<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table) {

            // English / Display title (optional)
            $table->string('eng_title', 255)
                  ->nullable()
                  ->after('original_title')
                  ->index();

        });
    }

    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {

            $table->dropIndex(['eng_title']);
            $table->dropColumn('eng_title');

        });
    }
};
