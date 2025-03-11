<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = DB::table('m_user')->pluck('user_id')->toArray(); // Ambil semua user_id

        DB::table('t_penjualan')->insert([
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 1',
                'penjualan_kode' => 'PJL-001',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 2',
                'penjualan_kode' => 'PJL-002',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 3',
                'penjualan_kode' => 'PJL-003',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 4',
                'penjualan_kode' => 'PJL-004',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 5',
                'penjualan_kode' => 'PJL-005',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 6',
                'penjualan_kode' => 'PJL-006',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 7',
                'penjualan_kode' => 'PJL-007',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 8',
                'penjualan_kode' => 'PJL-008',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 9',
                'penjualan_kode' => 'PJL-009',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userIds[array_rand($userIds)],
                'pembeli' => 'Pembeli 10',
                'penjualan_kode' => 'PJL-010',
                'penjualan_tanggal' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
