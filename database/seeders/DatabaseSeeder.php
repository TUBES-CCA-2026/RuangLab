<?php

namespace Database\Seeders;

use App\Models\MstDay;
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


    $laboranRole = MstRole::firstOrCreate(['nama_role' => 'laboran']);
    $aslabRole   = MstRole::firstOrCreate(['nama_role' => 'aslab']);
    MstRole::firstOrCreate(['nama_role' => 'peminjam']);

    MstUser::firstOrCreate(
        ['email' => 'laboran@ruanglab.test'],
        [
            'nama'     => 'Laboran',
            'password' => Hash::make('password'),
            'no_telp'  => '081234567890',
            'id_role'  => $laboranRole->id,
            'status'   => 1,
        ]
    );

    MstUser::firstOrCreate(
        ['email' => 'aslab@ruanglab.test'],
        [
            'nama'     => 'Asisten Lab',
            'password' => Hash::make('password'),
            'no_telp'  => '081234567891',
            'id_role'  => $aslabRole->id,
            'status'   => 1,
        ]
    );

    foreach (MstDay::URUTAN as $hari) {
        MstDay::firstOrCreate(['nama_hari' => $hari]);
    }
}
    }

