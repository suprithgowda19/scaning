<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('delegate_registration_id')
                ->constrained('delegate_registrations')
                ->cascadeOnDelete();

            // =========================
            // GATEWAY DATA
            // =========================
            $table->string('gateway', 20)->default('razorpay');
            $table->string('order_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('signature')->nullable();

            // =========================
            // MONEY
            // =========================
            $table->unsignedInteger('amount');

            // =========================
            // STATUS
            // =========================
            $table->enum('status', [
                'created',
                'paid',
                'failed'
            ])->default('created');

            // =========================
            // RAW PAYLOAD (AUDIT)
            // =========================
            $table->json('gateway_payload')->nullable();

            $table->timestamps();

            // =========================
            // IDEMPOTENCY
            // =========================
            $table->unique(['gateway', 'payment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
