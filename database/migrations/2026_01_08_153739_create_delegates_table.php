<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delegates', function (Blueprint $table) {
            $table->id();

            /* =========================
             | Personal Information
             ========================= */
            $table->string('first_name');
            $table->string('last_name')->nullable();

            $table->string('email')->index();
            $table->string('phone', 20)->index();
            $table->string('country_code', 5);

            $table->date('dob');
            $table->unsignedTinyInteger('age');

            $table->enum('gender', ['male', 'female', 'other']);

            /* =========================
             | Identity Information
             ========================= */
            $table->enum('id_type', ['VOTER', 'AADHAR', 'DL', 'PASSPORT']);
            $table->string('id_number', 30);

            /* =========================
             | Address Information
             ========================= */
            $table->text('address');
            $table->string('city');
            $table->string('pincode', 10);
            $table->string('country', 2); // ISO country code (IN, US, etc.)

            /* =========================
             | Registration Meta
             ========================= */
            $table->enum('category', [
                'Delegate',
                'Film Fraternity',
                'Senior Citizen',
                'Student'
            ]);

            $table->enum('pickup_location', ['KCA', 'SCA', 'ORI']);

            /* =========================
             | Document Storage (MVP)
             ========================= */
            $table->string('id_document_path')->nullable();
            $table->string('id_document_mime', 50)->nullable();
            $table->unsignedInteger('id_document_size')->nullable();

            $table->string('photo_path')->nullable();
            $table->string('photo_mime', 50)->nullable();
            $table->unsignedInteger('photo_size')->nullable();

            /* =========================
             | Lifecycle & Payment State
             ========================= */
            $table->enum('status', [
                'draft',           // form submitted
                'payment_pending', // redirected to gateway
                'paid',            // payment success
                'approved',
                'rejected'
            ])->default('draft')->index();

            /* =========================
             | Post-Payment Identifiers
             | (NULL until payment success)
             ========================= */
            $table->uuid('uuid')->nullable()->unique();
            $table->string('form_no')->nullable()->unique();
            $table->string('reference_id')->nullable()->unique();

            /* ========================= */
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegates');
    }
};
