<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('Vehicle_name');
            $table->string('model');
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->string('image')->nullable(); // stored path in public disk
            $table->text('description')->nullable();
            $table->json('features')->nullable(); // aircon, gps, etc
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};


