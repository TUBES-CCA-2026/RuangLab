<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_detail_reservasi', function (Blueprint $table) {
        $table->uuid('id')->primary();

        $table->uuid('id_reservasi');

        $table->uuid('id_ruangan');

        $table->date('tanggal_pakai');

        $table->time('jam_mulai');

        $table->time('jam_selesai');

        $table->timestamps();

        $table->foreign('id_reservasi')
              ->references('id')
              ->on('trx_reservasi')
              ->onDelete('cascade');

        $table->foreign('id_ruangan')
              ->references('id')
              ->on('mst_laboratorium')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_detail_reservasi');
    }
};
