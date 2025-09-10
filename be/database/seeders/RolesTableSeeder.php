<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['Role_ID' => 1, 'Name' => 'Admin', 'Description' => 'Quản trị viên'],
            ['Role_ID' => 2, 'Name' => 'Customer', 'Description' => 'Khách hàng'],
            ['Role_ID' => 3, 'Name' => 'Staff', 'Description' => 'Nhân viên'],
        ]);
    }
}
