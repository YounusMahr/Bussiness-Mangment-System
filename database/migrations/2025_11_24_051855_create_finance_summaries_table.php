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
        Schema::create('finance_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('summary_date')->unique();
            $table->string('system_type')->default('combined'); // combined, grocery, car-installment
            
            // Grocery System Totals
            $table->decimal('grocery_revenue', 15, 2)->default(0);
            $table->integer('grocery_sales_count')->default(0);
            $table->decimal('grocery_udhaar', 15, 2)->default(0);
            $table->integer('grocery_products_count')->default(0);
            
            // Car-Installment System Totals
            $table->decimal('car_installment_revenue', 15, 2)->default(0);
            $table->integer('car_installment_sales_count')->default(0);
            $table->decimal('car_installment_remaining', 15, 2)->default(0);
            
            // Combined Totals
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->integer('total_sales')->default(0);
            $table->decimal('total_udhaar', 15, 2)->default(0);
            $table->integer('total_customers')->default(0);
            
            $table->timestamps();
            
            $table->index('summary_date');
            $table->index('system_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_summaries');
    }
};
