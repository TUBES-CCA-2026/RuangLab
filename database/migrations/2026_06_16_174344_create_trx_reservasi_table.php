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
        Schema::create('trx_reservasi', function (Blueprint $table) {
        $table->uuid('id')->primary();

        $table->uuid('id_user');

        $table->string('kode_reservasi')->unique();

        $table->string('kode_checkin')->unique();

        $table->timestamp('batas_checkin')->nullable();

        $table->timestamp('waktu_checkin')->nullable();

        $table->date('tanggal_pengajuan');

        $table->string('keperluan');

        $table->enum('status', [
            'pending',
            'disetujui',
            'ditolak',
            'sedang_dipakai',
            'hangus'
        ])->default('pending');

        $table->text('catatan_admin')->nullable();

        $table->timestamps();
        $table->softDeletes();

        $table->foreign('id_user')
              ->references('id')
              ->on('mst_users')
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
        Schema::dropIfExists('trx_reservasi');
    }
};
