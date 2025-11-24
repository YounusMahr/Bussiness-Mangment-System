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
        Schema::create('stock_purchase_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_purchase_id')->constrained('stock_purchases')->onDelete('cascade');
            $table->date('date');
            $table->enum('type', ['stock-in', 'stock-out']);

            // Stock In fields
            $table->string('new_goods_name')->nullable();
            $table->decimal('new_goods_total_price', 10, 2)->default(0);
            $table->decimal('new_paid', 10, 2)->default(0);
            $table->decimal('new_interest', 10, 2)->default(0);
            $table->decimal('new_total_stock', 10, 2)->default(0);
            $table->decimal('new_given_stock', 10, 2)->default(0);

            // Stock Out fields
            $table->decimal('return_stock', 10, 2)->default(0);
            $table->decimal('return_payment', 10, 2)->default(0);

            // Balance tracking
            $table->decimal('total_stock_before', 10, 2)->default(0);
            $table->decimal('remaining_stock_before', 10, 2)->default(0);
            $table->decimal('goods_total_price_before', 10, 2)->default(0);
            $table->decimal('paid_before', 10, 2)->default(0);
            $table->decimal('remaining_before', 10, 2)->default(0);
            $table->decimal('interest_before', 10, 2)->default(0);
            $table->decimal('total_remaining_before', 10, 2)->default(0);

            $table->decimal('total_stock_after', 10, 2)->default(0);
            $table->decimal('remaining_stock_after', 10, 2)->default(0);
            $table->decimal('goods_total_price_after', 10, 2)->default(0);
            $table->decimal('paid_after', 10, 2)->default(0);
            $table->decimal('remaining_after', 10, 2)->default(0);
            $table->decimal('interest_after', 10, 2)->default(0);
            $table->decimal('total_remaining_after', 10, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_purchase_transactions');
    }
};
