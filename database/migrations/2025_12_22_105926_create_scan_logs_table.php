<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            /*
             |------------------------------------------------------------
             | Core references (NO FKs by design)
             |------------------------------------------------------------
             */

            // Show identity (source of truth)
            $table->unsignedBigInteger('scheduler_id')->index();

            // Delegate reference
            $table->unsignedInteger('delegate_form_id')->index();

            // Screen where scan occurred
            $table->unsignedBigInteger('screen_id')->index();

            // Staff who scanned
            $table->unsignedBigInteger('scanned_by')->index();

            /*
             |------------------------------------------------------------
             | Snapshot fields (audit-safe)
             |------------------------------------------------------------
             */
            $table->char('uuid', 36)->index();
            $table->string('form_no', 100)->index();
            $table->string('category', 100)->index();

            /*
             |------------------------------------------------------------
             | Timing
             |------------------------------------------------------------
             */
            $table->timestamp('scanned_at')->useCurrent();

            $table->timestamps();

            /*
             |------------------------------------------------------------
             | HARD DB RULE (CRITICAL)
             |------------------------------------------------------------
             */

            // One scan per delegate per show
            $table->unique(
                ['scheduler_id', 'delegate_form_id'],
                'uniq_scan_per_scheduler'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scan_logs');
    }
};
