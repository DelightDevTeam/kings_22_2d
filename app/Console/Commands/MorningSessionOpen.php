<?php

namespace App\Console\Commands;

use App\Helpers\MorningSessionHelper;
use App\Models\TwoD\TwodSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MorningSessionOpen extends Command
{
    protected $signature = 'session:morning-status-open';

    protected $description = 'Update Morning session status based on time of day';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            // Get current date and time in Asia/Yangon timezone
            $currentDateTime = Carbon::now()->setTimezone('Asia/Yangon');
            $currentDate = $currentDateTime->format('Y-m-d');
            $currentTime = $currentDateTime->format('H:i:s');
            // Get the current session
            $currentSession = MorningSessionHelper::getCurrentSession();
            Log::info("Current Date & Time: {$currentDateTime}");
            Log::info("Current session: {$currentSession}");
            Log::info("Current date: {$currentDate}");
            Log::info("Current time: {$currentTime}");
            // Check if any 'open' session should be closed based on close_time
            TwodSetting::where('result_date', $currentDate)
                ->where('session', $currentSession)
                ->where('status', 'closed')
                ->update(['status' => 'open']);
            $this->info('Morning Session status open updated successfully for '.$currentSession.' session.');
            Log::info('Morning Session status open updated successfully for '.$currentSession.' session.');
        } catch (\Exception $e) {
            Log::error('Error in MorningSessionOpen Command: '.$e->getMessage());
        }
    }
}
