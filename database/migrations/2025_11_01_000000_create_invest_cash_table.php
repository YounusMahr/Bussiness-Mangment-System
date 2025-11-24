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
        Schema::create('invest_cash', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('customer_name');
            $table->string('customer_number')->nullable();
            $table->decimal('invest_cash', 10, 2)->default(0);
            $table->decimal('interest', 10, 2)->default(0);
            $table->string('time_period')->nullable(); // e.g., "3 months", "1 year"
            $table->date('due_date')->nullable();
            $table->decimal('return_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'returned'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invest_cash');
    }
};

