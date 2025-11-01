<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_rent_udaars', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('booking_id')->nullable()->constrained('vehicle_bookings')->onDelete('set null');
            $table->string('customer'); // From booking
            $table->decimal('total_amount', 10, 2); // From booking
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('udaar_amount', 10, 2)->default(0); // Auto-calculated: total - paid
            $table->enum('status', ['pending', 'paid', 'unpaid'])->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_rent_udaars');
    }
};

