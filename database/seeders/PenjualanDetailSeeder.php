<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $penjualanIds = range(1, 10);
        $barangIds = range(1, 10);

        foreach ($penjualanIds as $penjualanId) {
            $barangTerjual = array_rand(array_flip($barangIds), 3); // Pilih 3 barang acak

            foreach ($barangTerjual as $barangId) {
                // Ambil harga jual dari tabel m_barang
                $hargaJual = DB::table('m_barang')->where('barang_id', $barangId)->value('harga_jual');

                $data[] = [
                    'penjualan_id' => $penjualanId,
                    'barang_id' => $barangId,
                    'harga' => $hargaJual, 
                    'jumlah' => 1, 
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('t_penjualan_detail')->insert($data);
    }
}
