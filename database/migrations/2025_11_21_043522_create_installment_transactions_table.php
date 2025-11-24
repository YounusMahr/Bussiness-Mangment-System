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
        Schema::create('installment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_id')->constrained('installments')->onDelete('cascade');
            $table->date('date');
            $table->enum('type', ['add', 'return']);
            
            // Add fields (new installment or additional payment)
            $table->decimal('new_car_price', 15, 2)->default(0);
            $table->decimal('new_interest', 15, 2)->default(0);
            $table->decimal('new_paid', 15, 2)->default(0);
            $table->decimal('new_total_price', 15, 2)->default(0);
            
            // Return fields (payment return)
            $table->decimal('return_payment', 15, 2)->default(0);
            
            // Balance tracking
            $table->decimal('car_price_before', 15, 2)->default(0);
            $table->decimal('paid_before', 15, 2)->default(0);
            $table->decimal('remaining_before', 15, 2)->default(0);
            $table->decimal('interest_before', 15, 2)->default(0);
            $table->decimal('total_price_before', 15, 2)->default(0);
            
            $table->decimal('car_price_after', 15, 2)->default(0);
            $table->decimal('paid_after', 15, 2)->default(0);
            $table->decimal('remaining_after', 15, 2)->default(0);
            $table->decimal('interest_after', 15, 2)->default(0);
            $table->decimal('total_price_after', 15, 2)->default(0);
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_transactions');
    }
};
