<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::create('konsumen_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konsumen_id')->constrained()->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsumen_produk');
    }
};
