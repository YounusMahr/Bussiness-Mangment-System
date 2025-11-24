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
        Schema::create('stock_purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('goods_name');
            $table->string('seller_name');
            $table->string('contact')->nullable();
            $table->decimal('total_stock', 10, 2)->default(0);
            $table->decimal('given_stock', 10, 2)->default(0);
            $table->decimal('remaining_stock', 10, 2)->default(0);
            $table->string('time_period')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['remaining', 'complete'])->default('remaining');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_purchases');
    }
};
