<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('member')->insert([
            [
                'nama' => 'Andi',
                'alamat' => 'Jl. Kenanga No.5',
                'email' => 'andi@example.com',
                'nomor_handphone' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi',
                'alamat' => 'Jl. Anggrek No.8',
                'email' => 'budi@example.com',
                'nomor_handphone' => '081234567891',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
