<?php

namespace App\Services;

use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Models\ThreeD\ThreedSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentLottoOneWeekRecordService
{
    public function GetRecordForOneWeek()
    {
        // Get the match start date and result date from ThreedSetting
        $draw_date = ThreedSetting::where('status', 'open')->first();
        $start_date = $draw_date->match_start_date;
        $end_date = $draw_date->result_date;
        $agent_id = Auth::user();

        // Retrieve records within the specified date range and include user information
        $records = LotteryThreeDigitPivot::with('user')
            ->whereBetween('match_start_date', [$start_date, $end_date])
            ->whereBetween('res_date', [$start_date, $end_date])
            ->where('agent_id', $agent_id->id)
            ->get();

        // Calculate the total sub_amount
        $total_sub_amount = $records->sum('sub_amount');

        // Return the records and total sub_amount
        return [
            'records' => $records,
            'total_sub_amount' => $total_sub_amount,
        ];
    }
}
