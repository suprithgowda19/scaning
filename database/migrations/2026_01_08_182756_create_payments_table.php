<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Link to the delegate
            $table->foreignId('delegate_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Amount stored in paise (60000 = 600.00 INR)
            $table->integer('amount'); 
            $table->string('currency', 3)->default('INR');
            
            // Status: initiated, success, failed
            $table->string('status')->default('initiated');
            
            // Gateway details
            $table->string('gateway')->default('razorpay');
            $table->string('gateway_order_id')->nullable()->index();
            $table->string('gateway_payment_id')->nullable()->index();
            
            // Meta tracking
            $table->text('failure_reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};