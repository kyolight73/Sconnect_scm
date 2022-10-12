<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PersonalAccessTokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('personal_access_tokens')->truncate();
        DB::table('personal_access_tokens')->insert([
            [
                'tokenable_type' => 'facebook_user_token',
                'tokenable_id' => 2,
                'name' => 'Facebook User Token',
                'token' => 'EAAJnWCTnpesBAPPKBn3XeK1zw3nUf5DKOm9FfHIfetqpMUOdTKm2gEncRXOuXZCMvI99pABNZAJBtlp14reAs5NEdV3YjIKjESAyIH14kDe2MpR3nMwrDW0eLlMBCqdpfwUwNjslp9zVECX5DM967XpuJJgALkdznmGfmWcdW29QF6DwjJLw0lTfywHuIZD',
                'abilities' => 'Get facebook pages, groups, user information',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
