<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan kolom untuk fitur QR check-in pada tabel trx_reservasi.
 *
 * Catatan: nama tabel diasumsikan "trx_reservasi" (sesuai model TrxReservasi).
 * Jika di project kamu nama tabelnya berbeda, ganti string di bawah ini.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trx_reservasi', function (Blueprint $table) {
            // Waktu peminjam berhasil scan QR (check-in). Null = belum check-in.
            $table->timestamp('checked_in_at')->nullable()->after('updated_at');
            // Waktu check-out (opsional, kalau mau dicatat juga).
            $table->timestamp('checked_out_at')->nullable()->after('checked_in_at');
        });
    }

    public function down(): void
    {
        Schema::table('trx_reservasi', function (Blueprint $table) {
            $table->dropColumn(['checked_in_at', 'checked_out_at']);
        });
    }
};
