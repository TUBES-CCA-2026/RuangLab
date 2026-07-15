<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE trx_reservasi MODIFY status ENUM('pending', 'disetujui', 'ditolak', 'sedang_dipakai', 'hangus', 'selesai') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::table('trx_reservasi')->where('status', 'selesai')->update(['status' => 'hangus']);
        DB::statement("ALTER TABLE trx_reservasi MODIFY status ENUM('pending', 'disetujui', 'ditolak', 'sedang_dipakai', 'hangus') NOT NULL DEFAULT 'pending'");
    }
};
