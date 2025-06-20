<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    
    public function run(): void
    {
        DB::table('m_supplier')->insert([
            [
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'CV Maju Jaya',
                'alamat' => 'Jl. Merdeka No. 1, Malang',
            ],
            [
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'PT Sumber Rejeki',
                'alamat' => 'Jl. Soekarno Hatta No. 12, Malang',
            ],
            [
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'Toko Serba Ada',
                'alamat' => 'Jl. Ciliwung No. 88, Malang',
            ],
        ]);
    }
}
