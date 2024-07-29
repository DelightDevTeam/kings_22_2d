<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgentAllWinPrizeSentService
{
    public function AllWinPrizeSentForAgent()
    {
        $user = Auth::user();
        $agent_id = $user->id;
        try {
            $results = DB::table('lottery_two_digit_pivots')
                ->join('users', 'lottery_two_digit_pivots.user_id', '=', 'users.id')
                ->select(
                    'users.name as user_name',
                    'users.phone as user_phone',
                    'lottery_two_digit_pivots.bet_digit',
                    'lottery_two_digit_pivots.res_date',
                    'lottery_two_digit_pivots.sub_amount',
                    'lottery_two_digit_pivots.session',
                    'lottery_two_digit_pivots.res_time',
                    'lottery_two_digit_pivots.prize_sent',
                    'lottery_two_digit_pivots.agent_id'
                )
                ->where('lottery_two_digit_pivots.agent_id', $agent_id)
                ->where('lottery_two_digit_pivots.prize_sent', true)
                ->get();

            // Calculate total prize amount
            $totalPrizeAmount = 0;
            foreach ($results as $result) {
                $prizeAmount = $result->sub_amount * 85; // Prize multiplier
                $totalPrizeAmount += $prizeAmount;
            }

            return ['results' => $results, 'totalPrizeAmount' => $totalPrizeAmount];

        } catch (\Exception $e) {
            Log::error('Error retrieving prize_sent data: '.$e->getMessage());

            return ['results' => collect([]), 'totalPrizeAmount' => 0];
        }
    }
}
