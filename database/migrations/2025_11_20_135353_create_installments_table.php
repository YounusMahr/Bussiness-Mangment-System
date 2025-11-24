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
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('number')->nullable(); // Customer phone number (auto-filled from customer)
            $table->string('vehicle')->nullable(); // Vehicle name/brand
            $table->string('model')->nullable(); // Vehicle model
            $table->text('installment')->nullable(); // Installment details text field
            $table->decimal('car_price', 15, 2)->default(0);
            $table->decimal('paid', 15, 2)->default(0);
            $table->decimal('remaining', 15, 2)->default(0);
            $table->decimal('interest', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->text('note')->nullable();
            $table->string('time_period')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
