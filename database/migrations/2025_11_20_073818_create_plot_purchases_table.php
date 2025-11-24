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
        Schema::create('plot_purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('plot_area');
            $table->decimal('plot_price', 12, 2);
            $table->text('installments')->nullable();
            $table->text('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plot_purchases');
    }
};
