<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MorningSessionHelper
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
        $morningSessionStart = Carbon::createFromTimeString('6:00:00', 'Asia/Yangon');
        $morningSessionEnd = Carbon::createFromTimeString('11:50:00', 'Asia/Yangon')->addDay();
        if ($currentTime->between($morningSessionStart, $morningSessionEnd)) {
            Log::info('Session is morning');

            return 'morning';
        } else {
            Log::info('Session is closed');

            return 'closed';
        }
    }
}
