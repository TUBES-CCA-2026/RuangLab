<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mst_laboratorium_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_laboratorium');
            $table->string('image_path');
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->foreign('id_laboratorium')
                  ->references('id')
                  ->on('mst_laboratorium')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mst_laboratorium_images');
    }
};
