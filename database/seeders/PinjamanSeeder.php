<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PinjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pinjaman')->insert([
            [
                'member_id' => 1,
                'buku_id' => 1,
                'tanggal_pinjam' => now()->toDateString(),
                'status' => 'dipinjam',
                'tanggal_kadaluarsa' => now()->addDays(14)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'member_id' => 2,
                'buku_id' => 2,
                'tanggal_pinjam' => now()->toDateString(),
                'status' => 'dikembalikan',
                'tanggal_kadaluarsa' => now()->addDays(14)->toDateString(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
