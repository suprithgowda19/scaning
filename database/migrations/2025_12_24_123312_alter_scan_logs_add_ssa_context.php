<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        // 1️⃣ Add new columns safely
        Schema::table('scan_logs', function (Blueprint $table) {

            if (!Schema::hasColumn('scan_logs', 'day')) {
                $table->unsignedTinyInteger('day')
                      ->after('screen_id')
                      ->index();
            }

            if (!Schema::hasColumn('scan_logs', 'slot_id')) {
                $table->unsignedBigInteger('slot_id')
                      ->after('day')
                      ->index();
            }

            if (Schema::hasColumn('scan_logs', 'scanned_by')) {
                $table->dropColumn('scanned_by');
            }
        });

        // 2️⃣ Drop UNIQUE(uuid) ONLY IF IT EXISTS (MySQL-level)
        $indexExists = DB::selectOne("
            SELECT 1
            FROM information_schema.STATISTICS
            WHERE table_schema = DATABASE()
              AND table_name = 'scan_logs'
              AND index_name = 'scan_logs_uuid_unique'
            LIMIT 1
        ");

        if ($indexExists) {
            DB::statement("ALTER TABLE scan_logs DROP INDEX scan_logs_uuid_unique");
        }

        // 3️⃣ Add correct scoped uniqueness
        Schema::table('scan_logs', function (Blueprint $table) {
            $table->unique(
                ['uuid', 'screen_id', 'day', 'slot_id'],
                'uniq_uuid_screen_day_slot'
            );
        });
    }

    public function down(): void
    {
        // Rollback scoped unique
        Schema::table('scan_logs', function (Blueprint $table) {
            $table->dropUnique('uniq_uuid_screen_day_slot');
        });

        // Restore old unique(uuid)
        DB::statement("ALTER TABLE scan_logs ADD UNIQUE scan_logs_uuid_unique (uuid)");

        Schema::table('scan_logs', function (Blueprint $table) {
            $table->dropColumn(['day', 'slot_id']);
        });
    }
};
