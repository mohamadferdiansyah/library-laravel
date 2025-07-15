<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('buku')->insert([
            [
                'judul' => 'Laravel Untuk Pemula',
                'kode_buku' => 'BK001',
                'tahun_terbit' => 2023,
                'penulis' => 'John Doe',
                'penerbit_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Framework PHP Modern',
                'kode_buku' => 'BK002',
                'tahun_terbit' => 2022,
                'penulis' => 'Jane Smith',
                'penerbit_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
