<?php

namespace App\Jobs;

use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Models\ThreeD\Lotto;
use App\Models\ThreeD\ThreedSetting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckForThreeDWinners implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $three_d_winner;

    public function __construct($three_d_winner)
    {
        $this->three_d_winner = $three_d_winner;
    }

    public function handle()
    {
        //Log::info('CheckFor3DWinners job started');

        $today = Carbon::today();

        // Get the correct bet digit from result number
        $result_number = $this->three_d_winner->result_number;

        //Log::info('Result Number' . $result_number);

        $open_date = ThreedSetting::where('status', 'open')
            ->get();
        // Correctly accumulate IDs into an array
        $dates = []; // Initialize an array
        foreach ($open_date as $date) {
            $dates[] = $date->id; // Add each ID to the array
        }

        //Log::info('Open result date IDs:', ['dates' => $dates]);

        // Check if the $dates array is empty
        if (empty($dates) || ! is_array($dates)) {
            Log::warning('No open result dates found or $dates is not an array');

            return; // Exit the function if no valid open dates
        }
        $draw_date = ThreedSetting::where('status', 'open')->first();
        $start_date = $draw_date->match_start_date;
        Log::info('Match Start Date is : '.$start_date);

        $end_date = $draw_date->result_date;
        Log::info('Result Date is: '.$end_date);
        $winningEntries = LotteryThreeDigitPivot::whereBetween('match_start_date', [$start_date, $end_date])
            ->whereBetween('res_date', [$start_date, $end_date])
            ->where('prize_sent', false)
            ->where('bet_digit', $result_number) // Make sure this is correct
            //->whereDate('created_at', $today)
            ->get(); // Fetch the results
        //Log::info('Winning entries fetched:', ['count' => $winningEntries->count()]);

        foreach ($winningEntries as $entry) {
            //     Log::info('Winning entry details', [
            //     'entry_id' => $entry->id,
            //     'user_id' => $entry->user_id,
            //     'lotto_id' => $entry->lotto_id,
            //     'bet_digit' => $entry->bet_digit,
            //     'created_at' => $entry->created_at
            // ]);
            DB::transaction(function () use ($entry) {
                try {
                    $lottery = Lotto::findOrFail($entry->lotto_id);
                    if (! $lottery) {
                        Log::error("Lotto entry not found for ID: {$entry->lotto_id}");

                        return; // Skip this entry if not found
                    }
                    $user = $lottery->user;

                    $prize = $entry->sub_amount * 700;
                    Log::info('Prize calculated:', ['prize' => $prize, 'user_id' => $user->id]);
                    $user->main_balance += $prize;
                    $user->save();
                    //Log::info('User balance updated:', ['user_id' => $user->id, 'new_balance' => $user->main_balance]);
                    // Now the entry is also an Eloquent model, so this works
                    $entry->prize_sent = true;
                    $entry->save();
                    //Log::info('Prize sent flag updated:', ['entry_id' => $entry->id]);
                } catch (\Exception $e) {
                    Log::error("Error during transaction for entry ID {$entry->id}: ".$e->getMessage());
                    throw $e; // Ensure rollback if needed
                }
            });
        }

        //Log::info('CheckFor3DWinners job completed.');
    }
}
