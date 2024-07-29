<?php

namespace Database\Seeders;

use App\Models\TwoD\TwodSetting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TwodSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set the starting date to today's date
        $currentDate = Carbon::now();

        // Iterate over the next 10 years
        for ($year = 0; $year < 5; $year++) {
            // Iterate over each month in the year
            for ($month = 1; $month <= 12; $month++) {
                // Determine the number of days in the month
                $daysInMonth = Carbon::create($currentDate->year + $year, $month)->daysInMonth;

                // Iterate over each day in the month
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    // Calculate the date
                    $date = Carbon::create($currentDate->year + $year, $month, $day);

                    // Set status to 'open' for today's sessions, 'closed' otherwise
                    $morningStatus = $date->isToday() ? 'open' : 'closed';
                    $eveningStatus = $date->isToday() ? 'open' : 'closed';

                    // Morning session
                    TwodSetting::create([
                        'result_date' => $date->format('Y-m-d'),
                        'result_time' => '12:01:00', // Morning open time
                        'session' => 'morning',
                        //'status' => $morningStatus,
                        'status' => 'closed',

                    ]);

                    // Evening session
                    TwodSetting::create([
                        'result_date' => $date->format('Y-m-d'),
                        'result_time' => '16:30:00', // Evening open time
                        'session' => 'evening',
                        //'status' => $eveningStatus,
                        'status' => 'closed',
                    ]);
                }
            }
        }
    }
    // public function run(): void
    // {
    //     // Set the starting date to today's date
    //     $currentDate = Carbon::now();

    //     // Iterate over the next 10 years
    //     for ($year = 0; $year < 5; $year++) {
    //         // Iterate over each month in the year
    //         for ($month = 1; $month <= 12; $month++) {
    //             // Determine the number of days in the month
    //             $daysInMonth = Carbon::create($currentDate->year + $year, $month)->daysInMonth;

    //             // Iterate over each day in the month
    //             for ($day = 1; $day <= $daysInMonth; $day++) {
    //                 // Calculate the date
    //                 $date = Carbon::create($currentDate->year + $year, $month, $day);

    //                 // Set status to 'open' for today's sessions, 'closed' otherwise
    //                 $morningStatus = $date->isToday() ? 'open' : 'closed';
    //                 $eveningStatus = $date->isToday() ? 'open' : 'closed';

    //                 // Morning session
    //                 TwodSetting::create([
    //                     'result_date' => $date->format('Y-m-d'),
    //                     'result_time' => '12:01:00', // Morning open time
    //                     'session' => 'morning',
    //                     'status' => $morningStatus,
    //                 ]);

    //                 // Evening session
    //                 TwodSetting::create([
    //                     'result_date' => $date->format('Y-m-d'),
    //                     'result_time' => '16:30:00', // Evening open time
    //                     'session' => 'evening',
    //                     'status' => $eveningStatus,
    //                 ]);
    //             }
    //         }
    //     }
    // }
    // public function run(): void
    // {
    //     // Set the starting date to today's date
    //     $currentDate = Carbon::now();

    //     // Find the closest Monday (today if it's Monday, or the next Monday)
    //     $startDate = $currentDate->copy()->next(Carbon::MONDAY);
    //     //$startDate = $currentDate->copy()->next(Carbon::SATURDAY);

    //     // Iterate over the next 10 years
    //     for ($year = 0; $year < 10; $year++) {
    //         // Iterate over each month in the year
    //         for ($month = 0; $month < 12; $month++) {
    //             // Iterate over each week in the month
    //             for ($week = 0; $week < 4; $week++) {
    //                 // Monday to Friday (5 days)
    //                 for ($day = 0; $day < 7; $day++) {
    //                     // Calculate the exact date based on week, month, and year
    //                     $date = $startDate->copy()
    //                         ->addYears($year) // Move through each year
    //                         ->addMonths($month)
    //                         ->addWeeks($week)
    //                         ->addDays($day);

    //                     // Determine if the calculated date is today's date
    //                     $isCurrentDay = $date->isSameDay($currentDate);

    //                     // Set status to 'open' for today's sessions, 'closed' otherwise
    //                     $morningStatus = $isCurrentDay ? 'open' : 'closed';
    //                     $eveningStatus = $isCurrentDay ? 'open' : 'closed';

    //                     // Morning session
    //                     TwodSetting::create([
    //                         'result_date' => $date->format('Y-m-d'),
    //                         'result_time' => '12:01:00', // Morning open time
    //                         'session' => 'morning',
    //                         'status' => $morningStatus,
    //                     ]);

    //                     // Evening session
    //                     TwodSetting::create([
    //                         'result_date' => $date->format('Y-m-d'),
    //                         'result_time' => '16:30:00', // Evening open time
    //                         'session' => 'evening',
    //                         'status' => $eveningStatus,
    //                     ]);
    //                 }
    //             }
    //         }
    //     }
    // }
}
