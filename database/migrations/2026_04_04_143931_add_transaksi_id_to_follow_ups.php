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
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->unsignedBigInteger('transaksi_id')->nullable()->after('konsumen_id');

            $table->foreign('transaksi_id')
                ->references('id')
                ->on('transaksis')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropForeign(['transaksi_id']);
            $table->dropColumn('transaksi_id');
        });
    }
};
