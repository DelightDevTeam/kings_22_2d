<?php

namespace App\Jobs;

use App\Models\TwoD\Lottery;
use App\Models\TwoD\LotteryTwoDigitPivot;
use App\Models\TwoD\TwodGameResult;
use App\Models\TwoD\TwodSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckForEveningWinners implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $twodWiner;

    public function __construct($twodWiner)
    {
        $this->twodWiner = $twodWiner;
    }

    public function handle()
    {
        Log::info('CheckForEveningWinners job started');

        $today = Carbon::today();
        $playDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']; // 'saturday', 'sunday'

        if (! in_array(strtolower($today->isoFormat('dddd')), $playDays)) {
            //Log::info('Today is not a play day: '.$today->isoFormat('dddd'));

            return; // Not a play day
        }

        if ($this->twodWiner->session !== 'evening') {
            // Log::info('Session is not evening, exiting.');

            return; // Not a morning session
        }

        // Get the correct bet digit from result number
        $result_number = $this->twodWiner->result_number;
        $date = Carbon::now()->format('Y-m-d');
        //Log::info('Today Date is '.$date);

        $currentSession = $this->getCurrentSession();
        //Log::info('Today Current Session is '.$currentSession);

        $currentSessionTime = $this->getCurrentSessionTime();
        //Log::info('Current Session Time is '.$currentSessionTime);

        $open_time = TwodSetting::where('prize_status', 'open')->first();

        if (! $open_time || ! is_object($open_time)) {
            //Log::warning('No valid open time found or invalid data structure.');

            return; // Exit early if no valid open time
        }

        if (isset($open_time->id)) {
            //Log::info('Open result date ID:', ['id' => $open_time->id]);

            // Retrieve winning entries using a valid `id`
            $winningEntries = LotteryTwoDigitPivot::where('twod_setting_id', $open_time->id)
                ->where('bet_digit', $result_number)
                ->whereDate('res_date', $date)
                //->whereTime('res_time', $currentSessionTime)
                ->where('session', 'evening')
                ->get();
        } else {
            // Log::warning('Invalid open time, cannot get ID.');

            return; // Exit if no valid ID
        }

        // ထွက်ဂဏန်းထဲ့မည့်အချိန်၌ match_status ဖွင့်ပေးရန်

        foreach ($winningEntries as $entry) {
            DB::transaction(function () use ($entry) {
                try {
                    $lottery = Lottery::findOrFail($entry->lottery_id);
                    $user = $lottery->user;

                    $prize = $entry->sub_amount * 85;
                    $user->main_balance += $prize; // Correct, user is an Eloquent model
                    $user->save();
                    $owner = User::find(1);
                    $owner->main_balance -= $prize;
                    $owner->save(); // Save the owner's new balance
                    $entry->prize_sent = true;
                    $entry->save();
                } catch (\Exception $e) {
                    //Log::error("Error during transaction for entry ID {$entry->id}: ".$e->getMessage());
                    throw $e; // Ensure rollback if needed
                }
            });
        }

        Log::info('CheckForEveningWinners job completed.');
    }

    protected function getCurrentSession()
    {
        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime >= '12:01:00' && $currentTime <= '16:30:00') {
            return 'evening';
        } else {
            return 'closed'; // If outside known session times
        }
    }

    protected function getCurrentSessionTime()
    {
        $currentTime = Carbon::now()->format('H:i:s');

        if ($currentTime >= '12:1:00' && $currentTime <= '16:30:00') {
            return '16:30:00';
        } else {
            return 'closed'; // If outside known session times
        }
    }
}
