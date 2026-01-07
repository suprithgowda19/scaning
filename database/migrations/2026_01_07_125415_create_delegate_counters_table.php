<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delegate_counters', function (Blueprint $table) {
            $table->id();

            // ORI / KCA / KFC / SCA
            $table->string('pickup_location', 5);

            // e.g. 23 (year)
            $table->string('period', 10);

            $table->unsignedInteger('current_count')->default(0);

            $table->timestamps();

            $table->unique(['pickup_location', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegate_counters');
    }
};
