<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['entry', 'exit', 'adjustment']);
            $table->decimal('quantity', 12, 2); // Positivo para entrada, negativo para saída
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_value', 12, 2)->nullable();
            $table->string('reference')->nullable(); // Nota fiscal, ordem, etc
            $table->text('notes')->nullable();
            $table->date('movement_date');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index('product_id');
            $table->index('type');
            $table->index('movement_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
