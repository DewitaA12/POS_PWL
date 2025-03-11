<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_kode' => 'SBA',
                'kategori_nama' => 'Sembako & Bahan Pokok', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'MNM',
                'kategori_nama' => 'Minuman & Aneka Jus', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'SNK',
                'kategori_nama' => 'Camilan & Makanan Ringan', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'SGR',
                'kategori_nama' => 'Sayuran & Buah Segar', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_kode' => 'RTG',
                'kategori_nama' => 'Rumah Tangga & Kebersihan', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('m_kategori')->insert($data);
    }
}
