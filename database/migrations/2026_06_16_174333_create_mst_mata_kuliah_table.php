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
        Schema::create('mst_mata_kuliah', function (Blueprint $table) {
        $table->uuid('id')->primary();

        $table->string('nama_dosen');
        $table->string('nama_matkul');

        $table->integer('sks');

        $table->integer('frekuensi');

        $table->text('catatan_admin')->nullable();

        $table->timestamps();
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_mata_kuliah');
    }
};
