<?php

namespace App\Services;

use App\Helpers\DrawDateHelper;
use App\Helpers\MatchTimeHelper;
use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Models\ThreeD\Lotto;
use App\Models\ThreeD\LottoSlipNumberCounter;
use App\Models\ThreeD\ThreeDigit;
use App\Models\ThreeD\ThreeDLimit;
use App\Models\ThreeD\ThreedSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LottoPlayService
{
    public function play($totalAmount, $amounts)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'You are not authenticated! please login.'], 401);
        }

        $user = Auth::user();
        Log::info('Auth user is: '.$user->name);

        try {
            DB::beginTransaction();

            $limit = $user->limit ?? null;
            Log::info('User limit is: '.$limit);

            if ($limit === null) {
                throw new \Exception("'limit' is not set for user.");
            }

            $defaultBreak = ThreeDLimit::latest()->first();
            $user_default_break = $defaultBreak->three_d_limit ?? null;

            if ($user_default_break === null) {
                throw new \Exception("'user's default limit' is not set.");
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
            $currentDate = Carbon::now()->format('Y-m-d'); // Format the date and time as needed
            $currentTime = Carbon::now()->format('H:i:s');
            $customString = 'shwebo-3d';
            //$randomNumber = rand(1000, 9999); // Generate a random 4-digit number
            //$slipNo = $randomNumber.'-'.$customString.'-'.$currentDate.'-'.$currentTime; // Combine date, string, and random number
            $counter = LottoSlipNumberCounter::firstOrCreate(['id' => 1], ['current_number' => 0]);
            // Increment the counter
            $counter->increment('current_number');
            $randomNumber = sprintf('%06d', $counter->current_number); // Ensure it's a 6-digit number with leading zeros

            $slipNo = $randomNumber.'-'.$customString.'-'.$currentDate.'-'.$currentTime; // Combine date, string, and random number
            $lottery = Lotto::create([
                'total_amount' => $totalAmount,
                'user_id' => $user->id,
                'slip_no' => $slipNo,
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

            $user->decrement('main_balance', $totalAmount);

            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollback();
            Log::error('Model not found in LottoService play method: '.$e->getMessage());

            return 'Resource not found.';
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in LottoService play method: '.$e->getMessage());

            return $e->getMessage(); // Handle general exceptions
        }
    }

    protected function preProcessAmountCheck($item)
    {
        $num = str_pad($item['num'], 3, '0', STR_PAD_LEFT);
        $sub_amount = $item['amount'];

        $draw_date = DrawDateHelper::getResultDate();
        $start_date = $draw_date['match_start_date'];
        $end_date = $draw_date['result_date'];

        $totalBetAmount = DB::table('lottery_three_digit_pivots')
            ->where('match_start_date', $start_date)
            ->where('res_date', $end_date)
            ->where('bet_digit', $num)
            ->sum('sub_amount');

        $break = ThreeDLimit::latest()->first()->three_d_limit;

        if ($totalBetAmount + $sub_amount > $break) {
            return [$item['num']];
        }
    }

    protected function processAmount($item, $lotteryId)
    {
        $num = str_pad($item['num'], 3, '0', STR_PAD_LEFT);
        $sub_amount = $item['amount'];

        $threeDigits = ThreeDigit::where('three_digit', $num)->firstOrFail();

        $draw_date = DrawDateHelper::getResultDate();
        $start_date = $draw_date['match_start_date'];
        $end_date = $draw_date['result_date'];

        $totalBetAmount = DB::table('lottery_three_digit_pivots')
            ->where('match_start_date', $start_date)
            ->where('res_date', $end_date)
            ->where('bet_digit', $num)
            ->sum('sub_amount');

        $break = ThreeDLimit::latest()->first()->three_d_limit;

        if ($totalBetAmount + $sub_amount <= $break) {
            $results = ThreedSetting::where('status', 'open')
                ->whereBetween('result_date', [$start_date, $end_date])
                ->first();

            if ($results && $results->status == 'closed') {
                return response()->json(['message' => '3D game does not open for this time']);
            }

            $play_date = Carbon::now()->setTimezone('Asia/Yangon')->format('Y-m-d');
            $play_time = Carbon::now()->setTimezone('Asia/Yangon')->format('H:i:s');
            $player_id = Auth::user()->id;
            $agent_id = Auth::user();
            $matchTimes = MatchTimeHelper::getCurrentYearAndMatchTimes();

            if (empty($matchTimes['currentMatchTime'])) {
                return response()->json(['message' => 'No current match time available']);
            }

            $currentMatchTime = $matchTimes['currentMatchTime'];
            //Log::info('Running Match Time ID: ' . $currentMatchTime['id'] . ' - Time: ' . $currentMatchTime['match_time']);

            $pivot = new LotteryThreeDigitPivot([
                'threed_setting_id' => $results->id,
                'lotto_id' => $lotteryId,
                'three_digit_id' => $threeDigits->id,
                'threed_match_time_id' => $currentMatchTime['id'],
                'user_id' => $player_id,
                'agent_id' => $agent_id->agent_id,
                'bet_digit' => $num,
                'sub_amount' => $sub_amount,
                'prize_sent' => false,
                'match_status' => $results->status,
                'play_date' => $play_date,
                'play_time' => $play_time,
                'res_date' => $results->result_date,
                'res_time' => $results->result_time,
                'match_start_date' => $start_date,
                'running_match' => $currentMatchTime['match_time'],
            ]);

            $pivot->save();
        } else {
            throw new \Exception('The bet amount exceeds the limit.');
        }
    }
}
