<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mst_users', function (Blueprint $table) {
            $table->foreign('id_role')->references('id')->on('mst_roles');
        });
    }

    public function down()
    {
        Schema::table('mst_users', function (Blueprint $table) {
            $table->dropForeign(['id_role']);
        });
    }
};