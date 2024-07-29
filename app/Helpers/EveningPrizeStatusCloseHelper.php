<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EveningPrizeStatusCloseHelper
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
        $eveningSessionStart = Carbon::createFromTimeString('17:31:00', 'Asia/Yangon');
        $eveningSessionEnd = Carbon::createFromTimeString('18:30:00', 'Asia/Yangon')->addDay();
        if ($currentTime->between($eveningSessionStart, $eveningSessionEnd)) {
            Log::info('Session is evening');

            return 'evening';
        } else {
            Log::info('Session is closed');

            return 'closed';
        }
    }
}
