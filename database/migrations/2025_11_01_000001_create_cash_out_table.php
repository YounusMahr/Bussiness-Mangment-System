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
        Schema::create('cash_out', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('invest_cash_id')->constrained('invest_cash')->onDelete('cascade');
            $table->decimal('available_balance', 10, 2)->default(0);
            $table->decimal('returned_amount', 10, 2)->default(0);
            $table->decimal('remaining_balance', 10, 2)->default(0);
            $table->enum('status', ['pending', 'Returned'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_out');
    }
};

