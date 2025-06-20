<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'level_id' => 1,
                'username' => 'admin',
                'nama' => 'Administrator',
                'foto' => 'uploads/foto_user/default.jpg',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 2,
                'level_id' => 2,
                'username' => 'manager',
                'nama' => 'Manager',
                'foto' => 'uploads/foto_user/default.jpg',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 3,
                'level_id' => 3,
                'username' => 'staff',
                'nama' => 'Staff/Kasir',
                'foto' => 'uploads/foto_user/default.jpg',
                'password' => Hash::make('12345'),
            ],
            [
                'user_id' => 4,
                'level_id' => 1,
                'username' => 'admin123',
                'nama' => 'Harry Potterr',
                'foto' => 'uploads/foto_user/default.jpg',
                'password' => Hash::make('123456'),
            ],
        ];

        DB::table('m_user')->insert($data);
    }
}
