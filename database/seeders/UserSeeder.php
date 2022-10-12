<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'staff_code' => 'SCN1307',
                'name' => 'admin',
                'email' => 'admin@s-connect.net',
                'google_id' => null,
                'email_verified_at' => null,
                'password' => '$2y$10$25mDZbeJJ4forSc8V6EgmOkrVka9O9bcWxjL4SOs5IrZoSHiS8V/m',
                'given_name' => null,
                'family_name' => null,
                'picture' => 'https://1.bigdata-vn.com/wp-content/uploads/2021/10/Top-38-hinh-anh-Rose-Black-Pink-xinh-dep-quyen.jpg',
                'user_token' => null,
                'phone' => null,
                'gender' => 1,
                'status' => 1,
                'permission' => 1,
                'department_id' => 0,
                'position' => 0,
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
