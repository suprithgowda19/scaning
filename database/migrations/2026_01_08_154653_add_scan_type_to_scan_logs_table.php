<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scan_logs', function (Blueprint $table) {
            // Entry / Exit scanning
            $table->enum('scan_type', ['entry', 'exit'])
                ->default('entry')
                ->after('category');

            // Optional but strongly recommended for analytics
            $table->timestamp('exited_at')
                ->nullable()
                ->after('scanned_at');
        });

        /*
         |--------------------------------------------------------------------------
         | Indexes (CRITICAL for performance)
         |--------------------------------------------------------------------------
         | These support:
         | - live count calculations
         | - category stats
         | - per-scheduler scanning
         | - exit validation
         */

        Schema::table('scan_logs', function (Blueprint $table) {
            $table->index(['scheduler_id', 'scan_type'], 'scan_logs_scheduler_scan_type_idx');
            $table->index(['scheduler_id', 'category', 'scan_type'], 'scan_logs_scheduler_category_scan_type_idx');
            $table->index(['delegate_form_id', 'scheduler_id'], 'scan_logs_delegate_scheduler_idx');
        });
    }

    public function down(): void
    {
        Schema::table('scan_logs', function (Blueprint $table) {
            $table->dropIndex('scan_logs_scheduler_scan_type_idx');
            $table->dropIndex('scan_logs_scheduler_category_scan_type_idx');
            $table->dropIndex('scan_logs_delegate_scheduler_idx');

            $table->dropColumn(['scan_type', 'exited_at']);
        });
    }
};
