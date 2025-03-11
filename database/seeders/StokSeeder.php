<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 2,
                'user_id' => 2,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 3,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 4,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 5,
                'user_id' => 2,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 6,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 7,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 8,
                'user_id' => 2,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 9,
                'user_id' => 3,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'barang_id' => 10,
                'user_id' => 1,
                'stok_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'stok_jumlah' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('t_stok')->insert($data);
    }
}
