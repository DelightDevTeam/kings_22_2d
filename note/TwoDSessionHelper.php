<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TwoDSessionHelper
{
    /**
     * Determine the current session based on the time of day.
     *
     * @return string
     */
    public static function getCurrentSession()
    {
        // Create a Carbon instance and set the desired time zone
        $currentTime = Carbon::now('Asia/Yangon');
        $currentHour = $currentTime->format('H:i:s');
        Log::info("Current time is: {$currentHour}");

        // Define time ranges for morning and evening sessions
        $morningSessionStart = Carbon::createFromTimeString('00:00:00', 'Asia/Yangon'); // Adjusted for clarity
        //dd($morningSessionStart);
        $morningSessionEnd = Carbon::createFromTimeString('11:50:00', 'Asia/Yangon')->addDay();
        $eveningSessionStart = Carbon::createFromTimeString('12:00:00', 'Asia/Yangon');
        $eveningSessionEnd = Carbon::createFromTimeString('16:30:00', 'Asia/Yangon');

        if ($currentTime->between($morningSessionStart, $morningSessionEnd)) {
            Log::info('Session is morning');

            return 'morning';
        } elseif ($currentTime->between($eveningSessionStart, $eveningSessionEnd)) {
            Log::info('Session is evening');

            return 'evening';
        } else {
            Log::info('Session is closed');

            return 'closed';
        }
    }
    // public static function getCurrentSession()
    // {
    //     // Create a Carbon instance and set the desired time zone
    //     $currentTime = Carbon::now('Asia/Yangon');
    //     $currentHour = $currentTime->format('H:i:s');
    //     Log::info("Current time is: {$currentHour}");

    //     // Define time ranges for morning and evening sessions
    //     $morningSessionStart = Carbon::createFromTimeString('17:00:00', 'Asia/Yangon');
    //     $morningSessionEnd = Carbon::createFromTimeString('11:50:00', 'Asia/Yangon')->addDay();
    //     $eveningSessionStart = Carbon::createFromTimeString('12:2:00', 'Asia/Yangon');
    //     $eveningSessionEnd = Carbon::createFromTimeString('15:50:00', 'Asia/Yangon');

    //     if ($currentTime->between($morningSessionStart, $morningSessionEnd)) {
    //         Log::info('Session is morning');

    //         return 'morning';
    //     } elseif ($currentTime->between($eveningSessionStart, $eveningSessionEnd)) {
    //         Log::info('Session is evening');

    //         return 'evening';
    //     } else {
    //         Log::info('Session is closed');

    //         return 'closed';
    //     }
    // }
}
