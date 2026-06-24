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
        Schema::create('trx_jadwal_kuliah', function (Blueprint $table) {
            $table->uuid('id')->primary();

        $table->uuid('id_matkul');

        $table->uuid('id_lab');

        $table->uuid('id_day');

        $table->time('jam_mulai');

        $table->time('jam_selesai');

        $table->timestamps();
        $table->softDeletes();

        $table->foreign('id_matkul')
              ->references('id')
              ->on('mst_mata_kuliah')
              ->onDelete('cascade');

        $table->foreign('id_lab')
              ->references('id')
              ->on('mst_laboratorium')
              ->onDelete('cascade');

        $table->foreign('id_day')
              ->references('id')
              ->on('mst_day')
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
        Schema::dropIfExists('trx_jadwal_kuliah');
    }
};
