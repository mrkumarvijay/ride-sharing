<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Passenger;

class PassengerSeeder extends Seeder
{
    public function run(): void
    {
        Passenger::create([
            'name' => 'Vijay Kumar',
            'email' => 'vijay@example.com',
            'phone' => '9876543210'
        ]);

        Passenger::create([
            'name' => 'Rahul Sharma',
            'email' => 'rahul@example.com',
            'phone' => '9123456780'
        ]);
    }
}
