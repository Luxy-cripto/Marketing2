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
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->bigInteger('harga_satuan')->after('qty');
        });
    }

    public function down()
    {
        Schema::table('detail_transaksis', function (Blueprint $table) {
            $table->dropColumn('harga_satuan');
        });
    }
};
