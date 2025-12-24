<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('delegate_forms', function (Blueprint $table) {

            // ADD ONLY IF NOT EXISTS (manual guard)
            $indexes = collect(DB::select("SHOW INDEX FROM delegate_forms"))
                ->pluck('Key_name')
                ->toArray();

            if (! in_array('delegate_forms_form_no_index', $indexes)) {
                $table->index('form_no');
            }

            if (! in_array('delegate_forms_reference_id_index', $indexes)) {
                $table->index('reference_id');
            }

            if (! in_array('delegate_forms_category_index', $indexes)) {
                $table->index('category');
            }

            if (! in_array('delegate_forms_payment_index', $indexes)) {
                $table->index('payment');
            }

            if (! in_array('delegate_forms_form_status_index', $indexes)) {
                $table->index('form_status');
            }

            if (! in_array('delegate_forms_created_at_index', $indexes)) {
                $table->index('created_at');
            }
        });
    }

    public function down(): void
    {
        // intentionally empty — legacy table, no rollback risk
    }
};
