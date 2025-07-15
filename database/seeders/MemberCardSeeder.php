<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MemberCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('member_card')->insert([
            [
                'member_id' => 1,
                'nomor_kartu' => 100001,
                'tanggal_aktif' => now()->toDateString(),
                'tanggal_kadaluarsa' => now()->addYear()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'member_id' => 2,
                'nomor_kartu' => 100002,
                'tanggal_aktif' => now()->toDateString(),
                'tanggal_kadaluarsa' => now()->addYear()->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
