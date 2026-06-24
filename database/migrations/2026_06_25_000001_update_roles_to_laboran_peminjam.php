<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ganti 'admin' -> 'laboran'
        DB::table('mst_roles')
            ->whereRaw('LOWER(nama_role) = ?', ['admin'])
            ->update(['nama_role' => 'laboran', 'updated_at' => now()]);

        // Ambil atau buat role 'peminjam'
        $peminjam = DB::table('mst_roles')
            ->whereRaw('LOWER(nama_role) IN (?, ?, ?)', ['mahasiswa', 'dosen', 'peminjam'])
            ->first();

        if ($peminjam) {
            DB::table('mst_roles')
                ->where('id', $peminjam->id)
                ->update(['nama_role' => 'peminjam', 'updated_at' => now()]);

            // Pindahkan user lama ke role peminjam, lalu hapus role lama
            $oldIds = DB::table('mst_roles')
                ->whereRaw('LOWER(nama_role) IN (?, ?)', ['mahasiswa', 'dosen'])
                ->where('id', '!=', $peminjam->id)
                ->pluck('id');

            if ($oldIds->isNotEmpty()) {
                DB::table('mst_users')
                    ->whereIn('id_role', $oldIds)
                    ->update(['id_role' => $peminjam->id]);

                DB::table('mst_roles')->whereIn('id', $oldIds)->delete();
            }
        } else {
            DB::table('mst_roles')->insert([
                'id'         => \Illuminate\Support\Str::uuid(),
                'nama_role'  => 'peminjam',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('mst_roles')
            ->whereRaw('LOWER(nama_role) = ?', ['laboran'])
            ->update(['nama_role' => 'admin', 'updated_at' => now()]);

        DB::table('mst_roles')
            ->whereRaw('LOWER(nama_role) = ?', ['peminjam'])
            ->update(['nama_role' => 'mahasiswa', 'updated_at' => now()]);
    }
};