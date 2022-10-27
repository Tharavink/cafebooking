<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CafeTableStatus;

class TableStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusses = [
            ['name' => 'Available', 'cssClass' => 'bg-green-300 text-green-700'],
            ['name' => 'Booked', 'cssClass' => 'bg-yellow-300 text-yellow-700'],
            ['name' => 'In-Use', 'cssClass' => 'bg-blue-300 text-blue-700']
        ];

        foreach ($statusses as $key => $status) {
            CafeTableStatus::create($status);
        }
    }
}
