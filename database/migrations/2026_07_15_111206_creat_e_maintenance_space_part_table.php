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
        Schema::create('maintenance_space_part', function (Blueprint $table) {
            $table->id();

            $table->foreignId('maintenance_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('space_part_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2)->nullable(); // سعر القطعة وقت الصيانة

            $table->timestamps();

            $table->unique(['maintenance_id', 'space_part_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_space_part');
    }
};
