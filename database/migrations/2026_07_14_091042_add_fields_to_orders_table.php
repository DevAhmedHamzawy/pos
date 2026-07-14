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
        Schema::table('orders', function (Blueprint $table) {
            $table->double('start', 8, 2)->after('total_price')->nullable();
            $table->double('benefit', 8, 2)->after('start')->nullable();
            $table->double('installment_number', 8, 2)->after('benefit')->nullable();
            $table->double('total_after_benefit', 8, 2)->after('installment_number')->nullable();
            $table->double('installment_value', 8, 2)->after('total_after_benefit')->nullable();
            $table->enum('installment_status', ['active', 'late', 'completed'])->default('active')->after('installment_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('start');
            $table->dropColumn('benefit');
            $table->dropColumn('installment_number');
            $table->dropColumn('total_after_benefit');
            $table->dropColumn('installment_value');
        });
    }
};
