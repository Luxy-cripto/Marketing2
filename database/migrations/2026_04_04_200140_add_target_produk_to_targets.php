<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->foreignId('produk_id')
                  ->nullable() // 🔥 INI YANG PENTING
                  ->constrained()
                  ->onDelete('cascade');

            $table->integer('target_produk')->default(0);
        });
    }

    public function down()
    {
        Schema::table('targets', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->dropColumn(['produk_id', 'target_produk']);
        });
    }
};
