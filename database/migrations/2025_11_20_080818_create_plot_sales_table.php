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
        Schema::create('plot_sales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('plot_purchase_id')->constrained('plot_purchases')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_number');
            $table->text('installments')->nullable();
            $table->decimal('interest', 12, 2)->nullable();
            $table->decimal('total_sale_price', 12, 2);
            $table->decimal('paid', 12, 2);
            $table->decimal('remaining', 12, 2);
            $table->string('time_period')->nullable();
            $table->enum('status', ['paid', 'remaining'])->default('remaining');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_sales');
    }
};
