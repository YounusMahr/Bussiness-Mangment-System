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
        Schema::table('udaar_transactions', function (Blueprint $table) {
            $table->string('time_period')->nullable()->after('product_id');
            $table->date('due_date')->nullable()->after('time_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('udaar_transactions', function (Blueprint $table) {
            $table->dropColumn(['time_period', 'due_date']);
        });
    }
};
