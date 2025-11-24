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
        Schema::create('udaar_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('udaar_id')->constrained('udaars')->onDelete('cascade');
            $table->date('date');
            $table->enum('type', ['udaar-in', 'udaar-out']);
            
            // Udaar In fields (new purchase)
            $table->decimal('new_udaar_amount', 10, 2)->default(0);
            $table->decimal('interest_amount', 10, 2)->default(0);
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            
            // Udaar Out fields (payment)
            $table->decimal('payment_amount', 10, 2)->default(0);
            
            // Balance tracking
            $table->decimal('paid_amount_before', 10, 2)->default(0);
            $table->decimal('remaining_amount_before', 10, 2)->default(0);
            $table->decimal('paid_amount_after', 10, 2)->default(0);
            $table->decimal('remaining_amount_after', 10, 2)->default(0);
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('udaar_transactions');
    }
};
