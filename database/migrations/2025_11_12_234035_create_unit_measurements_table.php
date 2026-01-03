<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_measurements', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Unidade, Quilograma, Metro, Litro
            $table->string('abbreviation', 10); // UN, KG, M, L
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_measurements');
    }
};
