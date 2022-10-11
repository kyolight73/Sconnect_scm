<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'display_name'=> 'Quản trị',
                'created_at'=>now(),
                'updated_at'=>now(),

            ],
            [
                'name' => 'SX',
                'display_name'=> 'Sản xuất',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'QTK',
                'display_name'=> 'Quản trị kênh',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name' => 'MKT',
                'display_name'=> 'Marketing',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],

        ]);
    }
}
