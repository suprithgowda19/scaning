<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delegate_registrations', function (Blueprint $table) {
            $table->id();

            // =========================
            // GENERATED AFTER PAYMENT
            // =========================
            $table->string('registration_code', 32)->nullable()->unique();
            $table->string('reference_id', 32)->nullable()->unique();
            $table->string('form_no', 24)->nullable()->unique();
            $table->uuid('uuid')->nullable()->unique(); // for QR scan

            // =========================
            // PERSONAL DETAILS
            // =========================
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->date('dob');
            $table->unsignedTinyInteger('age');
            $table->enum('gender', ['male', 'female', 'other']);

            // =========================
            // CONTACT (NOT UNIQUE)
            // =========================
            $table->string('email')->index();
            $table->string('country_code', 5);
            $table->string('phone', 15);

            // =========================
            // ADDRESS
            // =========================
            $table->text('address');
            $table->string('city', 50);
            $table->string('pincode', 10);
            $table->string('country', 50);

            // =========================
            // IDENTITY PROOF
            // =========================
            $table->enum('id_type', [
                'voter_id',
                'aadhaar',
                'driving_license',
                'passport'
            ]);
            $table->string('id_number', 30);
            $table->string('id_document_path');

            // =========================
            // CATEGORY & FEES
            // =========================
            $table->enum('category', [
                'delegate',          // DL
                'film_fraternity',   // FF
                'student',           // SD
                'senior_citizen'     // SN
            ]);
            $table->unsignedInteger('fee_amount');

            // =========================
            // PHOTO & ID CARD
            // =========================
            $table->string('photo_path');
            $table->string('id_card_path')->nullable();

            // =========================
            // PASS PICKUP
            // =========================
            $table->enum('pickup_location', ['ORI', 'KCA', 'KFC', 'SCA']);
            $table->date('pickup_date');

            // =========================
            // STATE MACHINE
            // =========================
            $table->enum('status', [
                'draft',     // form submitted
                'pending',   // payment initiated
                'confirmed', // payment success + IDs generated
                'cancelled'
            ])->default('draft');

            $table->timestamps();

            // =========================
            // PARTIAL ABUSE CONTROL
            // =========================
            $table->index(['email', 'status']);
            $table->index(['country_code', 'phone', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegate_registrations');
    }
};
