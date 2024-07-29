<?php

namespace App\Jobs;

use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Models\ThreeD\Lotto;
use App\Models\ThreeD\ThreedSetting;
use App\Models\ThreeDigit\ResultDate;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckForThreeDWinnersWithPermutations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $threedWinner;

    public function __construct($threedWinner)
    {
        $this->threedWinner = $threedWinner;
    }

    public function handle()
    {
        Log::info('CheckForThreeDWinnersWithPermutations job started');

        $today = Carbon::today(); // Get today's date
        $result_number = $this->threedWinner->result_number ?? null;

        if (is_null($result_number)) {
            Log::info('No result number provided. Exiting job.');

            return;
        }

        // Generate permutations for the three-digit result number, excluding the original
        $permutations = $this->generatePermutationsExcludeOriginal($result_number);

        // Fetch open dates for processing
        $open_dates = ThreedSetting::where('status', 'open')->get();
        if ($open_dates->isEmpty()) {
            Log::warning('No open result dates found.');

            return;
        }

        // Collect open date IDs
        $date_ids = $open_dates->pluck('id')->toArray();

        foreach ($permutations as $permutation) {
            $this->processWinningEntries($permutation, $date_ids);
        }

        Log::info('CheckForThreeDWinnersWithPermutations job completed.');
    }

    protected function processWinningEntries($permutation, array $date_ids)
    {
        $today = Carbon::today(); // Current date
        $draw_date = ThreedSetting::where('status', 'open')->first();
        $start_date = $draw_date->match_start_date;
        $end_date = $draw_date->result_date;
        $winningEntries = LotteryThreeDigitPivot::whereIn('threed_setting_id', $date_ids)
            ->whereBetween('match_start_date', [$start_date, $end_date])
            ->whereBetween('res_date', [$start_date, $end_date])
            ->where('bet_digit', $permutation)
            //->whereDate('created_at', $today)
            ->get();

        foreach ($winningEntries as $entry) {
            DB::transaction(function () use ($entry) {
                try {
                    $lottery = Lotto::findOrFail($entry->lotto_id);
                    $user = $lottery->user;

                    $prize = $entry->sub_amount * 10; // Calculate the prize amount
                    $user->main_balance += $prize; // Update user balance
                    $user->save(); // Save updated user

                    $entry->prize_sent = 2; // Mark as prize sent
                    $entry->save();

                    //Log::info("Prize awarded and prize_sent set to 2 for entry ID {$entry->id}.");
                } catch (\Exception $e) {
                    Log::error("Error during transaction for entry ID {$entry->id}: {$e->getMessage()}");
                    throw $e; // Ensure rollback if needed
                }
            });
        }
    }

    protected function generatePermutationsExcludeOriginal($original)
    {
        $permutations = $this->permutation($original);

        if (($key = array_search($original, $permutations)) !== false) {
            unset($permutations[$key]); // Remove the original from permutations
        }

        return array_values($permutations); // Return the remaining permutations
    }

    protected function permutation($str)
    {
        if (strlen($str) <= 1) {
            return [$str];
        }

        $result = [];
        for ($i = 0; $i < strlen($str); $i++) {
            $char = $str[$i];
            $remainingChars = substr($str, 0, $i).substr($str, $i + 1);

            foreach ($this->permutation($remainingChars) as $subPerm) {
                $result[] = $char.$subPerm;
            }
        }

        return array_unique($result); // Ensure unique permutations
    }
}
