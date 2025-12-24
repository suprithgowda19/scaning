<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delegate_forms', function (Blueprint $table) {

            // IMPORTANT: Match legacy schema
            $table->integer('id')->primary();

            $table->string('form_no', 200)->nullable();
            $table->string('form_nos', 200)->nullable();
            $table->string('reference_id', 200)->nullable();

            $table->string('firstname', 200)->nullable();
            $table->string('lastname', 200)->nullable();

            $table->string('address', 200)->nullable();
            $table->string('city', 200)->nullable();
            $table->string('pincode', 50)->nullable();
            $table->string('country', 50)->nullable();

            $table->string('email', 200)->nullable();
            $table->string('phone', 200)->nullable();

            $table->date('dob')->nullable();
            $table->string('gender', 200)->nullable();
            $table->string('category', 200)->nullable();

            $table->string('image', 200)->nullable();
            $table->string('image_url', 500)->nullable();

            $table->string('id_doc', 200)->nullable();
            $table->string('id_number', 200)->nullable();
            $table->string('doc_proof', 200)->nullable();
            $table->string('identity_proof', 200)->nullable();

            $table->string('pick_location', 200)->nullable();

            $table->boolean('is_agree')->default(false);

            $table->enum('payment', ['pending', 'failed', 'completed'])
                  ->default('pending');

            $table->string('razorpay_payment_id', 300)->nullable();
            $table->decimal('amount', 8, 2)->default(0);

            $table->string('form_status', 200)->default('Registered');
            $table->string('payment_status', 300)->nullable();

            $table->string('uuid', 300)->nullable()->index();
            $table->string('id_card_image', 300)->nullable();
            $table->string('qr_count', 300)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegate_forms');
    }
};
