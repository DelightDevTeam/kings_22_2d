<?php

namespace App\Services;

use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Models\ThreeD\ThreedSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentLottoHistoryRecordService
{
    public function GetRecord()
    {
        $agent_id = Auth::user();

        $records = LotteryThreeDigitPivot::with('user')
            ->where('agent_id', $agent_id->id)
            ->orderBy('id', 'desc')->get();

        // Calculate the total sub_amount
        $total_sub_amount = $records->sum('sub_amount');

        // Return the records and total sub_amount
        return [
            'records' => $records,
            'total_sub_amount' => $total_sub_amount,
        ];
    }
}
