<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\BookingStatus;

class BookingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statusses = [
            'Booked', 'In-Use', 'Completed', 'Expired', 'Cancelled'
        ];

        foreach ($statusses as $key => $status) {
            BookingStatus::create(['status' => $status]);
        }
    }
}
