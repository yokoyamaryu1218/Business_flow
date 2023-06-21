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
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'employee_number' => 1000,
                    'name' => '管理者',
                    'password' => Hash::make('password123'),
                    'role' => 1,
                ],
                [
                    'employee_number' => 1001,
                    'name' => '権限1',
                    'password' => Hash::make('password123'),
                    'role' => 5,
                ],
                [
                    'employee_number' => 1002,
                    'name' => '権限2',
                    'password' => Hash::make('password123'),
                    'role' => 5,
                ],
                [
                    'employee_number' => 1003,
                    'name' => '権限3',
                    'password' => Hash::make('password123'),
                    'role' => 5,
                ],
                [
                    'employee_number' => 1004,
                    'name' => '一般社員',
                    'password' => Hash::make('password123'),
                    'role' => 9,
                ],
            ],
        );
    }
}
