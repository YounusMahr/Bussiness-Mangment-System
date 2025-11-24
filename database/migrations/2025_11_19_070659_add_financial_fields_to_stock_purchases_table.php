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
        Schema::table('stock_purchases', function (Blueprint $table) {
            $table->decimal('goods_total_price', 10, 2)->default(0)->after('contact');
            $table->decimal('paid', 10, 2)->default(0)->after('goods_total_price');
            $table->decimal('remaining', 10, 2)->default(0)->after('paid');
            $table->decimal('interest', 10, 2)->default(0)->after('remaining');
            $table->decimal('total_remaining', 10, 2)->default(0)->after('interest');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_purchases', function (Blueprint $table) {
            $table->dropColumn(['goods_total_price', 'paid', 'remaining', 'interest', 'total_remaining']);
        });
    }
};
