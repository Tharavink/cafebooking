<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\UserType;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Admin', 'Cafe Owner', 'User'
        ];

        foreach ($types as $key => $type) {
            UserType::create(['name' => $type]);
        }
    }
}
