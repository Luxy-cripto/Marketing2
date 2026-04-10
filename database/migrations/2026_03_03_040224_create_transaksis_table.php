<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('konsumen_id')->constrained()->onDelete('cascade');

            // ❌ HAPUS INI
            // $table->foreignId('produk_id')->constrained()->onDelete('cascade');
            // $table->integer('qty')->default(1);
            // $table->bigInteger('harga_satuan');

            // ✅ GANTI JADI
            $table->bigInteger('total')->default(0);

            $table->date('tanggal_transaksi');

            $table->string('status')->default('Belum Bayar');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
