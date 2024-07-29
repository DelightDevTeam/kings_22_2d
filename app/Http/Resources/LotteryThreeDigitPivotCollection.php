<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LotteryThreeDigitPivotCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function($item) {
                return [
                    'id' => $item->id,
                    //'threed_setting_id' => $item->threed_setting_id,
                    //'lotto_id' => $item->lotto_id,
                   // 'three_digit_id' => $item->three_digit_id,
                   // 'threed_match_time_id' => $item->threed_match_time_id,
                   // 'user_id' => $item->user_id,
                    'user_name' => $item->user_name,
                   // 'agent_id' => $item->agent_id,
                    'bet_digit' => $item->bet_digit,
                    'sub_amount' => $item->sub_amount,
                    'prize_sent' => $item->prize_sent,
                    //'match_status' => $item->match_status,
                    'res_date' => $item->res_date,
                    //'res_time' => $item->res_time,
                    'match_start_date' => $item->match_start_date,
                    //'result_number' => $item->result_number,
                    'win_lose' => $item->win_lose,
                    'play_date' => $item->play_date,
                    //'play_time' => $item->play_time,
                    'running_match' => $item->running_match,
                    'prize_value' => $item->prize_value,
                ];
            }),
            'success' => true,
            'message' => 'Data 3D First Winner History retrieved successfully'
        ];
    }
}
