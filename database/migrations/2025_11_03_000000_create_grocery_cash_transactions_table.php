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
        Schema::create('grocery_cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->enum('type', ['cash-in', 'cash-out']);
            
            // Cash In fields
            $table->decimal('invest_cash', 10, 2)->default(0);
            $table->decimal('interest', 10, 2)->default(0);
            $table->string('time_period')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('return_amount', 10, 2)->default(0);
            
            // Cash Out fields
            $table->decimal('available_balance', 10, 2)->default(0);
            $table->decimal('returned_amount', 10, 2)->default(0);
            $table->decimal('remaining_balance', 10, 2)->default(0);
            
            // Common fields
            $table->enum('status', ['pending', 'returned'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grocery_cash_transactions');
    }
};

