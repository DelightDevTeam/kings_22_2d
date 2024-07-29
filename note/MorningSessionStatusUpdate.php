<?php

namespace App\Console\Commands;

use App\Helpers\SessionHelper;
use App\Helpers\TwoDSessionHelper;
use App\Models\TwoD\TwodSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MorningSessionStatusUpdate extends Command
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

        // Get current date in Asia/Yangon timezone
        $currentDate = Carbon::now()->setTimezone('Asia/Yangon')->format('Y-m-d');
        $nextDate = Carbon::now()->setTimezone('Asia/Yangon')->addDay()->toDateString();
        // Update only next day's morning sessions
        TwodSetting::where('result_date', $nextDate)
            ->where('session', $currentSession)
            ->update(['status' => $status]);

        // Update only today's evening sessions
        TwodSetting::where('result_date', $currentDate)
            ->where('session', $currentSession)
            ->update(['status' => $status]);

        $this->info('Session status updated successfully for '.$currentSession.' session.');
    }
}
