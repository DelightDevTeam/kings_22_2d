<?php

namespace App\Services;

use App\Models\TwoD\LotteryTwoDigitPivot;
use Illuminate\Support\Facades\Auth;

class AgentMorningHistoryService
{
    /**
     * Fetches bet_digit and related data for the morning session, including the total sub_amount.
     *
     * @return array
     */
    public function getMorningHistory()
    {
        $today = now()->format('Y-m-d'); // Get today's date
        $session = 'morning'; // Define session
        $user = Auth::user();
        $agent_id = $user->id;
        $data = LotteryTwoDigitPivot::with(['lottery', 'user'])
            ->where('res_date', $today)
            ->where('agent_id', $agent_id)
            ->where('session', $session)
            ->get()
            ->map(function ($pivot) {
                return [
                    'lottery_id' => $pivot->lottery_id,
                    'twod_setting_id' => $pivot->twod_setting_id,
                    'two_digit_id' => $pivot->two_digit_id,
                    'bet_digit' => $pivot->bet_digit,
                    'sub_amount' => $pivot->sub_amount,
                    'match_status' => $pivot->match_status,
                    'play_date' => $pivot->play_date,
                    'play_time' => $pivot->play_time,
                    'res_date' => $pivot->res_date,
                    'res_time' => $pivot->res_time,
                    'session' => $pivot->session,
                    'admin_log' => $pivot->admin_log,
                    'user_log' => $pivot->user_log,
                    'user_name' => $pivot->user->name,
                    'user_phone' => $pivot->user->phone,
                    'lottery_slip_no' => $pivot->lottery->slip_no,
                    'lottery_total_amount' => $pivot->lottery->total_amount,
                ];
            });

        $totalSubAmount = $data->sum('sub_amount');

        return [
            'total_sub_amount' => $totalSubAmount,
            'data' => $data,
        ];
    }
}
