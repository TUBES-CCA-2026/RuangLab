<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $exists = DB::table('mst_roles')->whereRaw('LOWER(nama_role) = ?', ['aslab'])->exists();
        if (! $exists) {
            DB::table('mst_roles')->insert([
                'id'         => \Illuminate\Support\Str::uuid(),
                'nama_role'  => 'aslab',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('mst_roles')->whereRaw('LOWER(nama_role) = ?', ['aslab'])->delete();
    }
};
