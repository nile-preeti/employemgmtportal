<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HolidaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
            
                // Array of Indian public holidays for a specific year (2025 example)
                $holidays = [
                    [
                        'date' => Carbon::create(2025, 1, 26), // Republic Day
                        'day' => 'Sunday',
                        'reason' => 'Republic Day',
                        'image' => null, // Optionally add an image URL or path
                    ],
                    [
                        'date' => Carbon::create(2025, 4, 14), // Ambedkar Jayanti
                        'day' => 'Monday',
                        'reason' => 'Ambedkar Jayanti',
                        'image' => null,
                    ],
                    [
                        'date' => Carbon::create(2025, 8, 15), // Independence Day
                        'day' => 'Friday',
                        'reason' => 'Independence Day',
                        'image' => null,
                    ],
                    [
                        'date' => Carbon::create(2025, 10, 2), // Gandhi Jayanti
                        'day' => 'Thursday',
                        'reason' => 'Gandhi Jayanti',
                        'image' => null,
                    ],
                    [
                        'date' => Carbon::create(2025, 11, 14), // Diwali
                        'day' => 'Friday',
                        'reason' => 'Diwali',
                        'image' => null,
                    ],
                    [
                        'date' => Carbon::create(2025, 12, 25), // Christmas
                        'day' => 'Thursday',
                        'reason' => 'Christmas',
                        'image' => null,
                    ],
                    // Add more holidays here as needed
                ];
        
                // Insert data into the holidays table
                DB::table('holidays')->insert($holidays);
    }
}
