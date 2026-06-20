<?php

namespace Database\Seeders;

use App\Models\MstRole;
use App\Models\MstUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Buat role dasar
        $adminRole = MstRole::firstOrCreate(['nama_role' => 'admin']);
        MstRole::firstOrCreate(['nama_role' => 'dosen']);
        MstRole::firstOrCreate(['nama_role' => 'mahasiswa']);

        // Buat akun admin default
        MstUser::firstOrCreate(
            ['email' => 'admin@ruanglab.test'],
            [
                'nama' => 'Laboran',
                'password' => Hash::make('password'),
                'no_telp' => '081234567890',
                'id_role' => $adminRole->id,
                'status' => 1,
            ]
        );
    }
}
