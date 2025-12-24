<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scan_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            /*
             |--------------------------------------------------------------------------
             | Reference fields (NO foreign keys – legacy safe)
             |--------------------------------------------------------------------------
             */

            // Legacy delegate_forms.id (soft reference only)
            $table->unsignedInteger('delegate_form_id')->index();

            // Runtime scan authority
            $table->char('uuid', 36)->index();

            // Screen on which scan happened
            $table->unsignedBigInteger('screen_id')->index();

            // Staff user who scanned
            $table->unsignedBigInteger('scanned_by')->index();

            /*
             |--------------------------------------------------------------------------
             | Denormalized snapshot (for reporting & audits)
             |--------------------------------------------------------------------------
             */
            $table->string('form_no', 100)->index();
            $table->string('category', 100)->index();

            /*
             |--------------------------------------------------------------------------
             | Scan state
             |--------------------------------------------------------------------------
             */
            $table->enum('status', ['valid', 'duplicate', 'rejected'])
                  ->default('valid');

            $table->timestamp('scanned_at')->useCurrent();

            $table->timestamps();

            /*
             |--------------------------------------------------------------------------
             | HARD RULES (DB-level safety without FK pain)
             |--------------------------------------------------------------------------
             */

            // Global duplicate prevention (UUID can be scanned only once)
            $table->unique('uuid', 'uniq_scan_uuid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_logs');
    }
};
