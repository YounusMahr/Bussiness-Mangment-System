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
        Schema::table('udaars', function (Blueprint $table) {
            $table->string('time_period')->nullable()->after('due_date');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null')->after('time_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('udaars', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['time_period', 'product_id']);
        });
    }
};
