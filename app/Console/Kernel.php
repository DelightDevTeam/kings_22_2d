<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\App;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\PullReport::class,
        Commands\MorningSessionOpen::class,
        Commands\MorningPrizeStatusOpen::class,
        Commands\EveningPrizeStatusOpen::class,
        Commands\MorningPrizeStatusClose::class,
        Commands\EveningSessionOpen::class,
        Commands\EveningPrizeStatusClose::class,
        Commands\CloseMorningSession::class,
        Commands\EveningSessionClose::class,
        // Commands\UpdateMatchStatus::class, // 3d
        Commands\ThreeDMatchStatusOpen::class, // 3d
        Commands\ThreeDMatchStatusClose::class, // 3d
    ];

    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('make:pull-report')->everyFiveSeconds();
        //$schedule->command('session:update-status')->everyFiveMinutes();
        //$schedule->command('session:twod-update-status')->oneDay();
        //$schedule->command('match:update-status')->daily();
        //$schedule->command('session:morning-status-open')->daily();
        $schedule->command('session:morning-status-open')->dailyAt('01:00')->timezone('Asia/Yangon'); // Assuming this is when you want it to run
        $schedule->command('session:morning-prize-status-open')->dailyAt('12:01')->timezone('Asia/Yangon'); // Set a specific time
        $schedule->command('session:morning-prize-status-close')->dailyAt('12:50')->timezone('Asia/Yangon'); // Set a specific time
        $schedule->command('session:evening-status-open')->dailyAt('12:02')->timezone('Asia/Yangon'); // Assuming this is when you want it to run
        $schedule->command('session:evening-prize-status-open')->dailyAt('16:31')->timezone('Asia/Yangon'); // Set a specific time
        $schedule->command('session:evening-prize-status-close')->dailyAt('20:00')->timezone('Asia/Yangon'); // Set a specific time
        $schedule->command('session:close-morning')->dailyAt('11:45')->timezone('Asia/Yangon'); // Set a specific time
        $schedule->command('session:close-evening')->dailyAt('23:00')->timezone('Asia/Yangon'); // Set a specific time
        //3d
        //$schedule->command('match:update-status')->dailyAt('14:00'); // 3d
        $schedule->command('app:three-d-match-status-open')
            ->dailyAt('00:00')->timezone('Asia/Yangon'); //3d match status open
        $schedule->command('app:three-d-match-status-close')
            ->dailyAt('14:00')->timezone('Asia/Yangon'); //3d match status close

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
