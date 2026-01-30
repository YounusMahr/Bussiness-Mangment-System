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
        Schema::create('plot_purchase_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plot_purchase_id')->constrained('plot_purchases')->onDelete('cascade');
            $table->date('date');
            $table->enum('type', ['purchase-in', 'purchase-out']); // purchase-in = Credit, purchase-out = Debit
            
            // Purchase In fields (Credit - payment received)
            $table->string('installment_no')->nullable();
            $table->decimal('installment_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            
            // Purchase Out fields (Debit - payment made)
            $table->decimal('payment_amount', 10, 2)->default(0);
            
            // Balance tracking
            $table->decimal('plot_price_before', 10, 2)->default(0);
            $table->decimal('plot_price_after', 10, 2)->default(0);
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_purchase_transactions');
    }
};
