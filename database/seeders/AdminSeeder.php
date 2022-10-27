<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin', 
            'email' => 'admin@cafebooking.site', 
            'password' => Hash::make('admin@123'), 
            'email_verified_at' => Carbon::now(),
            'type_id' => 1
        ]);
    }
}
