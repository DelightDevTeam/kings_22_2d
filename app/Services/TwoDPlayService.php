<?php

namespace App\Services;

use App\Helpers\SessionHelper;
use App\Helpers\TwoDSessionHelper;
use App\Models\SlipNumberCounter;
use App\Models\TwoD\Lottery;
use App\Models\TwoD\LotteryTwoDigitPivot;
use App\Models\TwoD\TwoDigit;
use App\Models\TwoD\TwoDLimit;
use App\Models\TwoD\TwodSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TwoDPlayService
{
    public function play($totalAmount, array $amounts)
    {
        if (! Auth::check()) {
            return response()->json([
                'message' => 'You are not authenticated! please login.',
            ], 401);
        }

        $user = Auth::user();

        try {
            DB::beginTransaction();
            // Access `Limit` with error handling
            $limit = $user->limit ?? null;
            Log::info('user limit is '.$limit);
            if ($limit === null) {
                throw new \Exception("'limit' is not set for user.");
            }
            $defaultBreak = TwoDLimit::lasted()->first();
            $user_default_break = $defaultBreak->two_d_limit ?? null;
            if ($user_default_break === null) {
                throw new \Exception("'user's default limit' is not set for user.");
            }

            if ($user->main_balance < $totalAmount) {
                return 'Insufficient funds.';
            }
            $preOver = [];
            foreach ($amounts as $amount) {
                $preCheck = $this->preProcessAmountCheck($amount);
                if (is_array($preCheck)) {
                    $preOver[] = $preCheck[0];
                }
            }
            if (! empty($preOver)) {
                return $preOver;
            }

            // Create a new lottery entry
            $currentDate = Carbon::now()->format('Y-m-d'); // Format the date and time as needed
            $currentTime = Carbon::now()->format('H:i:s');
            $customString = 'shwebo-2d';
            //$randomNumber = rand(1000, 9999); // Generate a random 4-digit number
            //$randomNumber = sprintf('%06d', rand(1, 999999)); // Generate a random 6-digit number with leading zeros

            // Get the current counter or create one if it doesn't exist
            $counter = SlipNumberCounter::firstOrCreate(['id' => 1], ['current_number' => 0]);
            // Increment the counter
            $counter->increment('current_number');
            $randomNumber = sprintf('%06d', $counter->current_number); // Ensure it's a 6-digit number with leading zeros

            $slipNo = $randomNumber.'-'.$customString.'-'.$currentDate.'-'.$currentTime; // Combine date, string, and random number
            $current_session = SessionHelper::getCurrentSession();
            Log::info('today current session is : '.$current_session);

            $lottery = Lottery::create([
                'pay_amount' => $totalAmount,
                'total_amount' => $totalAmount,
                'user_id' => $user->id,
                'slip_no' => $slipNo, // Add the generated slip_no here
                'session' => $current_session,
            ]);

            $over = [];
            foreach ($amounts as $amount) {
                $check = $this->processAmount($amount, $lottery->id);
                if (is_array($check)) {
                    $over[] = $check[0];
                }
            }
            if (! empty($over)) {
                return $over;
            }
            /** @var \App\Models\User $user */
            $user->main_balance -= $totalAmount;

            $user->save();

            DB::commit();

            return 'Bet placed successfully.';

        } catch (ModelNotFoundException $e) {
            DB::rollback();
            Log::error('Model not found in TwoDService play method: '.$e->getMessage());

            return 'Resource not found.';
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in TwoDService play method: '.$e->getMessage());

            return $e->getMessage(); // Handle general exceptions
        }

    }

    protected function preProcessAmountCheck($amount)
    {
        $twoDigit = str_pad($amount['num'], 2, '0', STR_PAD_LEFT); // Ensure two-digit format
        $user = Auth::user();
        $break = $user->limit ?? 0; // Set default value if `limit` is not set
        $defaultBreak = TwoDLimit::lasted()->first();
        $user_default_break = $defaultBreak->two_d_limit;

        //Log::info("User's  limit (limit): {$break}");
        //Log::info("Checking bet_digit: {$twoDigit}");
        //Log::info("User's default break: {$user_default_break}");

        $current_session = SessionHelper::getCurrentSession();
        $current_day = Carbon::now()->format('Y-m-d');

        $totalBetAmountForTwoDigit = DB::table('lottery_two_digit_pivots')
            ->where('res_date', $current_day)
            ->where('session', $current_session)
            ->where('bet_digit', $twoDigit)
            ->sum('sub_amount');

        //Log::info("Total bet amount for {$twoDigit}: {$totalBetAmountForTwoDigit}");

        $subAmount = $amount['amount'];

        if ($totalBetAmountForTwoDigit + $subAmount <= $user_default_break) {
            // Over all limits exceeded, return error
            //return [$amount['num']];
            Log::info('you can play with user break');
        } elseif ($totalBetAmountForTwoDigit + $subAmount <= $break) {
            // User's limit exceeded
            Log::info('you can play with over all break ');
            //Log::info("Bet exceeds user limit for {$twoDigit}");

            //return [$amount['num']];
        } elseif ($totalBetAmountForTwoDigit + $subAmount > $user_default_break && $break) {
            Log::info("Bet exceeds user limit for {$twoDigit}");

            return [$amount['num']];
        } else {
            Log::info('Within both limits, allow the bet');
        }
        // Indicates no over-limit

    }

    protected function processAmount($amount, $lotteryId)
    {
        $twoDigits = TwoDigit::where('two_digit', sprintf('%02d', $amount['num']))->firstOrFail();
        $twoDigit = str_pad($amount['num'], 2, '0', STR_PAD_LEFT); // Ensure two-digit format

        $user = Auth::user();
        $break = $user->limit;
        $defaultBreak = TwoDLimit::lasted()->first();
        $user_default_break = $defaultBreak->two_d_limit;
        $current_session = SessionHelper::getCurrentSession();
        $current_day = Carbon::now()->format('Y-m-d');

        $totalBetAmountForTwoDigit = DB::table('lottery_two_digit_pivots')
            ->where('res_date', $current_day)
            ->where('session', $current_session)
            ->where('bet_digit', $twoDigit)
            ->sum('sub_amount');

        $subAmount = $amount['amount'];

        if ($totalBetAmountForTwoDigit + $subAmount <= $user_default_break) {
            // Within default break, allow the bet
            $this->createLotteryTwoDigitPivot($lotteryId, $twoDigits->id, $amount['num'], $subAmount);
        } elseif ($totalBetAmountForTwoDigit + $subAmount <= $break) {
            // Within user limit, allow the bet
            $this->createLotteryTwoDigitPivot($lotteryId, $twoDigits->id, $amount['num'], $subAmount);
        } else {
            // Exceeds both limits, return error
            return [$amount['num']];
        }
    }

    protected function createLotteryTwoDigitPivot($lotteryId, $twoDigitId, $betDigit, $subAmount)
    {
        $today = Carbon::now()->format('Y-m-d');
        // Retrieve results for today where status is 'open'
        $results = TwodSetting::where('result_date', $today) // Match today's date
            ->where('status', 'open')      // Check if the status is 'open'
            ->first();

        if ($results) {
            $two_id = $results->id;
            Log::info("TwoDSetting id is: {$two_id}");

            $play_date = Carbon::now()->setTimezone('Asia/Yangon')->format('Y-m-d');  // Correct date format
            $play_time = Carbon::now()->setTimezone('Asia/Yangon')->format('H:i:s');  // Correct time format
            $player_id = Auth::user();
            $agent_id = Auth::user();
            $current_session = SessionHelper::getCurrentSession();
            Log::info('session time'.$current_session);
            $pivot = LotteryTwoDigitPivot::create([
                'lottery_id' => $lotteryId,
                'twod_setting_id' => $two_id,
                'two_digit_id' => $twoDigitId,
                'user_id' => $player_id->id,
                'agent_id' => $agent_id->agent_id,
                'bet_digit' => $betDigit,
                'sub_amount' => $subAmount,
                'prize_sent' => false,
                'match_status' => $results->status,
                'res_date' => $results->result_date,
                'res_time' => $results->result_time,
                'session' => $current_session,
                'play_date' => $play_date,
                'play_time' => $play_time,
            ]);

            Log::info("Created LotteryTwoDigitPivot with ID: {$pivot->id}");

        } else {
            Log::error("No TwodSetting found for date: {$today} with status 'open'");
        }
    }

    // protected function createLotteryTwoDigitPivot($lotteryId, $twoDigitId, $betDigit, $subAmount)
    // {
    //     $today = Carbon::now()->format('Y-m-d');
    //     // Retrieve results for today where status is 'open'
    //     $results = TwodSetting::where('result_date', $today) // Match today's date
    //         ->where('status', 'open')      // Check if the status is 'open'
    //         ->first();
    //         $two_id = $results->id;
    //         //Log::info("two d settin id is: {$two_id}");

    //     $play_date = Carbon::now()->format('Y-m-d');  // Correct date format
    //     $play_time = Carbon::now()->format('H:i:s');  // Correct time format
    //     $player_id = Auth::user();
    //     $current_session = SessionHelper::getCurrentSession();

    //     LotteryTwoDigitPivot::create([
    //         'lottery_id' => $lotteryId,
    //         'twod_setting_id' => $two_id,
    //         'two_digit_id' => $twoDigitId,
    //         'user_id' => $player_id->id,
    //         'bet_digit' => $betDigit,
    //         'sub_amount' => $subAmount,
    //         'prize_sent' => false,
    //         'match_status' => $results->status,
    //         'res_date' => $results->result_date,
    //         'res_time' => $results->result_time,
    //         'session' => $current_session,
    //         'admin_log' => $results->admin_log,
    //         'user_log' => $results->user_log,
    //         'play_date' => $play_date,
    //         'play_time' => $play_time,
    //     ]);
    // }

    // protected function preProcessAmountCheck($amount)
    // {
    //     $twoDigit = str_pad($amount['num'], 2, '0', STR_PAD_LEFT); // Ensure two-digit format
    //     $break = Auth::user()->limit ?? 0; // Set default value if `limit` is not set
    //     $defaultBreak = TwoDLimit::lasted()->first();
    //     $user_default_break = $defaultBreak->two_d_limit;
    //     Log::info("User's limit (limit): {$break}");
    //     Log::info("Checking bet_digit: {$twoDigit}");
    //     Log::info("User's default break (default break): {$user_default_break}");

    //     $current_session = SessionHelper::getCurrentSession();
    //     $current_day = Carbon::now()->format('Y-m-d');

    //     $totalBetAmountForTwoDigit = DB::table('lottery_two_digit_pivots')
    //         ->where('res_date', $current_day)
    //         ->where('session', $current_session)
    //         ->where('bet_digit', $twoDigit)
    //         ->sum('sub_amount');

    //     Log::info("Total bet amount for {$twoDigit}: {$totalBetAmountForTwoDigit}");

    //     $subAmount = $amount['amount'];

    //     if ($totalBetAmountForTwoDigit + $subAmount > $user_default_break) {
    //             // over all limits exceeded, return error
    //             return [$amount['num']];
    //     } else {
    //         // Either no user limit is set or the bet doesn't exceed the user limit
    //         if ($totalBetAmountForTwoDigit + $subAmount > $break) {
    //             //Log::info('User's limit exceeded');
    //             return [$amount['num']];
    //         } else {
    //             Log::info('Within both limits, allow the bet');
    //         }
    //     }

    //     // if ($totalBetAmountForTwoDigit + $subAmount > $break) {
    //     //     Log::warning("Bet on {$twoDigit} exceeds limit.");

    //     //     return [$amount['num']]; // Indicates over-limit
    //     // }

    //     // Indicates no over-limit
    // }

    //    protected function processAmount($amount, $lotteryId)
    // {
    //     $twoDigits = TwoDigit::where('two_digit', sprintf('%02d', $amount['num']))->firstOrFail();
    //     $twoDigit = str_pad($amount['num'], 2, '0', STR_PAD_LEFT); // Ensure two-digit format

    //     $user = Auth::user();
    //     $break = $user->limit;
    //     $defaultBreak = TwoDLimit::lasted()->first();
    //     $user_default_break = $defaultBreak->two_d_limit;

    //     $current_session = SessionHelper::getCurrentSession();
    //     $current_day = Carbon::now()->format('Y-m-d');

    //     $totalBetAmountForTwoDigit = DB::table('lottery_two_digit_pivots')
    //         ->where('res_date', $current_day)
    //         ->where('session', $current_session)
    //         ->where('bet_digit', $twoDigit)
    //         ->sum('sub_amount');

    //     $subAmount = $amount['amount'];

    //     if($totalBetAmountForTwoDigit + $subAmount < $user_default_break)
    //     {
    //         $today = Carbon::now()->format('Y-m-d');
    //             // Retrieve results for today where status is 'open'
    //             $results = TwodSetting::where('result_date', $today) // Match today's date
    //                 ->where('status', 'open')      // Check if the status is 'open'
    //                 ->first();
    //             $play_date = Carbon::now()->format('Y-m-d');  // Correct date format
    //             $play_time = Carbon::now()->format('H:i:s');  // Correct time format
    //             $player_id = Auth::user();
    //             LotteryTwoDigitPivot::create([
    //                 'lottery_id' => $lotteryId,
    //                 'twod_setting_id' => $results->id,
    //                 'two_digit_id' => $twoDigits->id,
    //                 'user_id' => $player_id->id,
    //                 'bet_digit' => $amount['num'],
    //                 'sub_amount' => $subAmount,
    //                 'prize_sent' => false,
    //                 'match_status' => $results->status,
    //                 'res_date' => $results->result_date,
    //                 'res_time' => $results->result_time,
    //                 'session' => $current_session,
    //                 'admin_log' => $results->admin_log,
    //                 'user_log' => $results->user_log,
    //                 'play_date' => $play_date,
    //                 'play_time' => $play_time,
    //             ]);
    //     }else{
    //         if($totalBetAmountForTwoDigit + $subAmount < $break)
    //         {
    //             $today = Carbon::now()->format('Y-m-d');
    //             // Retrieve results for today where status is 'open'
    //             $results = TwodSetting::where('result_date', $today) // Match today's date
    //                 ->where('status', 'open')      // Check if the status is 'open'
    //                 ->first();
    //             $play_date = Carbon::now()->format('Y-m-d');  // Correct date format
    //             $play_time = Carbon::now()->format('H:i:s');  // Correct time format
    //             $player_id = Auth::user();
    //             LotteryTwoDigitPivot::create([
    //                 'lottery_id' => $lotteryId,
    //                 'twod_setting_id' => $results->id,
    //                 'two_digit_id' => $twoDigits->id,
    //                 'user_id' => $player_id->id,
    //                 'bet_digit' => $amount['num'],
    //                 'sub_amount' => $subAmount,
    //                 'prize_sent' => false,
    //                 'match_status' => $results->status,
    //                 'res_date' => $results->result_date,
    //                 'res_time' => $results->result_time,
    //                 'session' => $current_session,
    //                 'admin_log' => $results->admin_log,
    //                 'user_log' => $results->user_log,
    //                 'play_date' => $play_date,
    //                 'play_time' => $play_time,
    //             ]);
    //         }
    //     }
    // }

    // protected function processAmount($amount, $lotteryId)
    // {

    //     $twoDigits = TwoDigit::where('two_digit', sprintf('%02d', $amount['num']))->firstOrFail();
    //     $twoDigit = str_pad($amount['num'], 2, '0', STR_PAD_LEFT); // Ensure three-digit format

    //     $break = Auth::user()->limit;

    //     $current_session = SessionHelper::getCurrentSession();
    //     $current_day = Carbon::now()->format('Y-m-d');

    //     $totalBetAmountForTwoDigit = DB::table('lottery_two_digit_pivots')
    //         ->where('res_date', $current_day)
    //         ->where('session', $current_session)
    //         ->where('bet_digit', $twoDigit)
    //         ->sum('sub_amount');
    //     $subAmount = $amount['amount'];
    //     $betDigit = $amount['num'];

    //     if ($totalBetAmountForTwoDigit + $subAmount <= $break) {
    //         $today = Carbon::now()->format('Y-m-d');
    //         // Retrieve results for today where status is 'open'
    //         $results = TwodSetting::where('result_date', $today) // Match today's date
    //             ->where('status', 'open')      // Check if the status is 'open'
    //             ->first();
    //         $play_date = Carbon::now()->format('Y-m-d');  // Correct date format
    //         $play_time = Carbon::now()->format('H:i:s');  // Correct time format
    //         $player_id = Auth::user();
    //         LotteryTwoDigitPivot::create([
    //             'lottery_id' => $lotteryId,
    //             'twod_setting_id' => $results->id,
    //             'two_digit_id' => $twoDigits->id,
    //             'user_id' => $player_id->id,
    //             'bet_digit' => $betDigit,
    //             'sub_amount' => $subAmount,
    //             'prize_sent' => false,
    //             'match_status' => $results->status,
    //             'res_date' => $results->result_date,
    //             'res_time' => $results->result_time,
    //             'session' => $current_session,
    //             'admin_log' => $results->admin_log,
    //             'user_log' => $results->user_log,
    //             'play_date' => $play_date,
    //             'play_time' => $play_time,

    //         ]);
    //     } else {
    //         // Handle the case where the bet exceeds the limit
    //         return [$amount['num']];
    //     }
    // }
}
