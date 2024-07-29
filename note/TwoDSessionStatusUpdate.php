<?php

namespace App\Console\Commands;

use App\Helpers\TwoDSessionHelper;
use App\Models\TwoD\TwodSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TwoDSessionStatusUpdate extends Command
{
    protected $signature = 'session:twod-update-status';

    protected $description = 'Update session status based on time of day';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentSession = TwoDSessionHelper::getCurrentSession();
        $status = ($currentSession == 'closed') ? 'closed' : 'open';

        // Get current date and next date in Asia/Yangon timezone
        $currentDate = Carbon::now('Asia/Yangon')->format('Y-m-d');
        $nextDate = Carbon::now('Asia/Yangon')->addDay()->format('Y-m-d');

        Log::info("Current session: {$currentSession}");
        Log::info("Current date: {$currentDate}");
        Log::info("Next date: {$nextDate}");

        // Update only next day's morning sessions if the session is morning
        if ($currentSession == 'morning') {
            $affectedRows = TwodSetting::where('result_date', $nextDate)
                ->where('session', 'morning')
                ->update(['status' => $status]);
            Log::info("Morning sessions updated: {$affectedRows}");
        }

        // Update only today's evening sessions if the session is evening
        if ($currentSession == 'evening') {
            $affectedRows = TwodSetting::where('result_date', $currentDate)
                ->where('session', 'evening')
                ->update(['status' => $status]);
            Log::info("Evening sessions updated: {$affectedRows}");
        }

        // If the session is closed, update both today's and tomorrow's sessions
        if ($currentSession == 'closed') {
            $affectedRowsToday = TwodSetting::where('result_date', $currentDate)
                ->update(['status' => $status]);
            Log::info("Today's sessions updated: {$affectedRowsToday}");

            $affectedRowsTomorrow = TwodSetting::where('result_date', $nextDate)
                ->update(['status' => $status]);
            Log::info("Tomorrow's sessions updated: {$affectedRowsTomorrow}");
        }

        $this->info('Session status updated successfully for '.$currentSession.' session.');
    }

    // public function handle()
    // {
    //     $currentSession = TwoDSessionHelper::getCurrentSession();
    //     $status = ($currentSession == 'closed') ? 'closed' : 'open';

    //     // Get current date and next date in Asia/Yangon timezone
    //     $currentDate = Carbon::now('Asia/Yangon')->format('Y-m-d');
    //     $nextDate = Carbon::now('Asia/Yangon')->addDay()->format('Y-m-d');

    //     Log::info("Current session: {$currentSession}");
    //     Log::info("Current date: {$currentDate}");
    //     Log::info("Next date: {$nextDate}");

    //     // Update only next day's morning sessions if the session is morning
    //     if ($currentSession == 'morning') {
    //         $affectedRows = TwodSetting::where('result_date', $nextDate)
    //             ->where('session', 'morning')
    //             ->update(['status' => $status]);
    //         Log::info("Morning sessions updated: {$affectedRows}");
    //     }

    //     // Update only today's evening sessions if the session is evening
    //     if ($currentSession == 'evening') {
    //         $affectedRows = TwodSetting::where('result_date', $currentDate)
    //             ->where('session', 'evening')
    //             ->update(['status' => $status]);
    //         Log::info("Evening sessions updated: {$affectedRows}");
    //     }

    //     $this->info('Session status updated successfully for ' . $currentSession . ' session.');
    // }
}
