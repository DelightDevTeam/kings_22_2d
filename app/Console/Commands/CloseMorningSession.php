<?php

namespace App\Console\Commands;

use App\Models\TwoD\TwodSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CloseMorningSession extends Command
{
    protected $signature = 'session:close-morning';

    protected $description = 'Close open morning sessions at their defined closed time';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get current time in Asia/Yangon timezone
        $currentTime = Carbon::now('Asia/Yangon')->format('H:i:s');

        Log::info("Running CloseMorningSession command at: {$currentTime}");

        // Get all open morning sessions that need to be closed
        $sessionsToClose = TwodSetting::where('status', 'open')
            ->where('session', 'morning')
            ->where('closed_time', '<=', $currentTime)
            ->get();

        Log::info('Sessions to close:', ['sessions' => $sessionsToClose->toArray()]);

        foreach ($sessionsToClose as $session) {
            $session->status = 'closed';
            $session->save();
            Log::info("Closed session ID: {$session->id} scheduled to close at: {$session->closed_time}");
        }

        $this->info('All eligible morning sessions have been closed successfully.');
    }
}
