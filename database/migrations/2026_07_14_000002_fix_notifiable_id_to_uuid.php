<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * notifiable_id dibuat sebagai bigint (default Laravel morphs()), padahal
 * MstUser pakai UUID string sebagai primary key — akibatnya setiap kali
 * notify() dipanggil, insert ke tabel notifications selalu gagal
 * (Incorrect integer value) dan tertelan oleh catch(\Exception) di semua
 * pemanggil, sehingga notifikasi tidak pernah benar-benar tersimpan.
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_notifiable_type_notifiable_id_index');
        });

        DB::statement('ALTER TABLE notifications MODIFY notifiable_id CHAR(36) NOT NULL');

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_type_notifiable_id_index');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_notifiable_type_notifiable_id_index');
        });

        DB::statement('ALTER TABLE notifications MODIFY notifiable_id BIGINT UNSIGNED NOT NULL');

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['notifiable_type', 'notifiable_id'], 'notifications_notifiable_type_notifiable_id_index');
        });
    }
};
