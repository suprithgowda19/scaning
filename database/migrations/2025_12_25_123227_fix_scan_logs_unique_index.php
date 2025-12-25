<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('scan_logs', function (Blueprint $table) {
            // ❌ REMOVE GLOBAL UUID UNIQUE
            $table->dropUnique('uniq_scan_uuid');

            // ✅ ADD SHOW-SCOPED UNIQUE
            $table->unique(
                ['uuid', 'screen_id', 'day', 'slot_id'],
                'uniq_scan_per_show'
            );
        });
    }

    public function down(): void
    {
        Schema::table('scan_logs', function (Blueprint $table) {
            $table->dropUnique('uniq_scan_per_show');
            $table->unique('uuid', 'uniq_scan_uuid');
        });
    }
};
