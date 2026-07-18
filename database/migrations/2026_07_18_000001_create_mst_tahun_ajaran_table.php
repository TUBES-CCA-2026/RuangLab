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
        // Tabel ini sudah ada di sebagian database (dibuat manual sebelum migration ini
        // ditulis) dengan skema yang sama persis, sehingga hanya dibuat kalau belum ada.
        if (Schema::hasTable('mst_tahun_ajaran')) {
            return;
        }

        Schema::create('mst_tahun_ajaran', function (Blueprint $table) {
        $table->uuid('id')->primary();

        $table->string('nama');

        $table->enum('semester', ['ganjil', 'genap']);

        $table->string('tahun_ajaran');

        $table->date('tanggal_mulai');

        $table->date('tanggal_selesai');

        $table->boolean('is_aktif')->default(false);

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
        Schema::dropIfExists('mst_tahun_ajaran');
    }
};
