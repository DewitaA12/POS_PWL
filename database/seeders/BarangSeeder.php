<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        $data = [
            [
                'kategori_id' => 'SBA',
                'barang_kode' => 'SBA-001',
                'barang_nama' => 'Beras Premium 5kg',
                'harga_beli' => 50000,
                'harga_jual' => 55000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 'SBA',
                'barang_kode' => 'SBA-002',
                'barang_nama' => 'Minyak Goreng 2L',
                'harga_beli' => 30000,
                'harga_jual' => 33000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 'MNM',
                'barang_kode' => 'MNM-001',
                'barang_nama' => 'Air Mineral 600ml',
                'harga_beli' => 3000,
                'harga_jual' => 3500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 'SNK',
                'barang_kode' => 'SNK-004',
                'barang_nama' => 'Keripik Kentang 100g',
                'harga_beli' => 10000,
                'harga_jual' => 12000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 'SGR',
                'barang_kode' => 'SGR-002',
                'barang_nama' => 'Apel Fuji 1kg',
                'harga_beli' => 25000,
                'harga_jual' => 28000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 'RTG',
                'barang_kode' => 'RTG-006',
                'barang_nama' => 'Sabun Cuci Piring 800ml',
                'harga_beli' => 15000,
                'harga_jual' => 17000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 'MNM',
                'barang_kode' => 'MNM-007',
                'barang_nama' => 'Teh Celup 25pcs',
                'harga_beli' => 8000,
                'harga_jual' => 9000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 'SNK',
                'barang_kode' => 'SNK-008',
                'barang_nama' => 'Roti Tawar Gandum',
                'harga_beli' => 12000,
                'harga_jual' => 14000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 'RTG',
                'barang_kode' => 'RTG-009',
                'barang_nama' => 'Sikat Gigi 2pcs',
                'harga_beli' => 7000,
                'harga_jual' => 8000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori_id' => 'SGR',
                'barang_kode' => 'SGR-010',
                'barang_nama' => 'Jeruk Medan 1kg',
                'harga_beli' => 20000,
                'harga_jual' => 23000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('m_barang')->insert($data);
    }
}
