<?php

namespace App\Console\Commands;

use App\Models\ThreeD\ThreedSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ThreeDMatchStatusClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:three-d-match-status-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today('Asia/Yangon');
        $currentTime = Carbon::now('Asia/Yangon')->toTimeString();

        // Get all open sessions that need to be closed
        $sessionsToClose = ThreedSetting::where('status', 'open')
            ->where('result_date', $today)
            ->where('closed_time', '<=', $currentTime)
            ->get();

        // Update status to closed for the selected sessions
        foreach ($sessionsToClose as $session) {
            $session->status = 'closed';
            $session->save();

            // Log the closure
            Log::info("Closed session ID: {$session->id} scheduled to close at: {$session->closed_time}");
        }

        $this->info('ThreedSettings status closed successfully.');
    }
}
