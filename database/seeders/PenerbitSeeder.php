<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenerbitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('penerbit')->insert([
            [
                'name' => 'Penerbit A',
                'alamat' => 'Jl. Mawar No.1',
                'nomor_telepon' => 81234567890,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penerbit B',
                'alamat' => 'Jl. Melati No.2',
                'nomor_telepon' => 81234567891,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
