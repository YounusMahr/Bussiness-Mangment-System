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
        Schema::create('plot_sale_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plot_sale_id')->constrained('plot_sales')->onDelete('cascade');
            $table->date('date');
            $table->enum('type', ['sale-in', 'sale-out']); // sale-in = Credit, sale-out = Debit
            
            // Sale In fields (Credit - payment received)
            $table->string('installment_no')->nullable();
            $table->decimal('installment_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            
            // Sale Out fields (Debit - payment made/refund)
            $table->decimal('payment_amount', 10, 2)->default(0);
            
            // Balance tracking
            $table->decimal('total_sale_price_before', 10, 2)->default(0);
            $table->decimal('paid_before', 10, 2)->default(0);
            $table->decimal('remaining_before', 10, 2)->default(0);
            $table->decimal('total_sale_price_after', 10, 2)->default(0);
            $table->decimal('paid_after', 10, 2)->default(0);
            $table->decimal('remaining_after', 10, 2)->default(0);
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_sale_transactions');
    }
};
