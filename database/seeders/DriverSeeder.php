<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example driver data
        $drivers = [
            [
                'name' => 'Ravi Singh',
                'email' => 'ravi@example.com',
                'phone' => '9988776655',
                'latitude' => 28.6328,
                'longitude' => 77.2197,
                'is_available' => true,
            ],
            [
                'name' => 'Amit Kumar',
                'email' => 'amit@example.com',
                'phone' => '9876543210',
                'latitude' => 28.6250,
                'longitude' => 77.2100,
                'is_available' => true,
            ],
            // Add more drivers if needed
        ];

        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}
