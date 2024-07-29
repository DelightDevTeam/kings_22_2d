<?php

namespace App\Console\Commands;

use App\Helpers\SessionHelper;
use App\Models\TwoD\TwodSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateSessionStatus extends Command
{
    protected $signature = 'session:update-status';

    protected $description = 'Update session status based on time of day';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $currentSession = SessionHelper::getCurrentSession();
        $status = ($currentSession == 'closed') ? 'closed' : 'open';

        // Get current date in Asia/Yangon timezone
        $currentDate = Carbon::now()->setTimezone('Asia/Yangon')->format('Y-m-d');

        // Update only today's sessions
        TwodSetting::where('result_date', $currentDate)
            ->where('session', $currentSession)
            ->update(['status' => $status]);

        $this->info('Session status updated successfully for '.$currentSession.' session.');
    }
}
