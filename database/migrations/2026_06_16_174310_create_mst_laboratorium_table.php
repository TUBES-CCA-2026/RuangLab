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
        Schema::create('mst_laboratorium', function (Blueprint $table) {
        $table->uuid('id')->primary();

        $table->string('nama_lab');

        $table->uuid('penanggung_jawab');

        $table->string('image')->nullable();

        $table->boolean('status')->default(true);

        $table->text('fasilitas')->nullable();

        $table->integer('kapasitas');

        $table->timestamps();
        $table->softDeletes();

        $table->foreign('penanggung_jawab')
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
        Schema::dropIfExists('mst_laboratorium');
    }
};
