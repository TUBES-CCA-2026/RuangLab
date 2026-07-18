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
        Schema::table('trx_detail_reservasi', function (Blueprint $table) {
            $table->uuid('id_matkul')->nullable()->after('id_ruangan');

            $table->foreign('id_matkul')
                  ->references('id')
                  ->on('mst_mata_kuliah')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_detail_reservasi', function (Blueprint $table) {
            $table->dropForeign(['id_matkul']);
            $table->dropColumn('id_matkul');
        });
    }
};
