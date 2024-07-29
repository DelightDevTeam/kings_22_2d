<?php

namespace App\Http\Controllers\Api\V1\ThreeD;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Http\Resources\LotteryThreeDigitPivotCollection;

class LottoPrizeWinnerHistoryController extends Controller
{
    public function FirstPrizeWinnerApi()
    {
        $user_id = Auth()->id();
        $reports = DB::table('lottery_three_digit_pivots')
            ->select('running_match', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(sub_amount) as total_amount'))
            ->where('user_id', $user_id) // Filter where user id is auth id
            ->where('prize_sent', true) // Filter where prize_sent is true
            ->groupBy('running_match')
            ->orderByDesc('running_match') // Optional: order by running_match descending
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $reports,
        ]);
    }

    public function getFirstPrizeShowByRunningMatch($running_match)
    {
        $user_id = Auth()->id();
        $reports = DB::table('lottery_three_digit_pivots')
            ->join('users', 'lottery_three_digit_pivots.user_id', '=', 'users.id')
            ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
            ->select(
                'users.user_name',
                'users.phone',
                'lottery_three_digit_pivots.agent_id',
                'lottery_three_digit_pivots.bet_digit',
                'lottery_three_digit_pivots.sub_amount',
                DB::raw('lottery_three_digit_pivots.sub_amount * 600 as total_prize_amount'),
                'lottery_three_digit_pivots.res_date',
                'lottery_three_digit_pivots.res_time',
                'lottery_three_digit_pivots.play_date',
                'lottery_three_digit_pivots.play_time',
                'lottery_three_digit_pivots.match_start_date',
                'lottery_three_digit_pivots.match_status',
                'lottery_three_digit_pivots.win_lose',
                'lottery_three_digit_pivots.prize_sent',
                'lottos.slip_no'
            )
            ->where('lottery_three_digit_pivots.running_match', $running_match)
            ->where('lottery_three_digit_pivots.user_id', $user_id) // Filter where user id is auth id
            ->where('lottery_three_digit_pivots.prize_sent', true)
            ->orderByDesc('lottery_three_digit_pivots.created_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $reports,
        ]);
    }

    public function SecondPrizeWinnerApi()
    {
        $user_id = Auth()->id();
        $reports = DB::table('lottery_three_digit_pivots')
            ->select('running_match', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(sub_amount) as total_amount'))
            ->where('user_id', $user_id) // Filter where user id is auth id
            ->where('prize_sent', 2) // Filter where prize_sent is true
            ->groupBy('running_match')
            ->orderByDesc('running_match') // Optional: order by running_match descending
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $reports,
        ]);
    }

    public function getSecondPrizeShowByRunningMatch($running_match)
    {
        $user_id = Auth()->id();
        $reports = DB::table('lottery_three_digit_pivots')
            ->join('users', 'lottery_three_digit_pivots.user_id', '=', 'users.id')
            ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
            ->select(
                'users.user_name',
                'users.phone',
                'lottery_three_digit_pivots.agent_id',
                'lottery_three_digit_pivots.bet_digit',
                'lottery_three_digit_pivots.sub_amount',
                DB::raw('lottery_three_digit_pivots.sub_amount * 10 as total_prize_amount'),
                'lottery_three_digit_pivots.res_date',
                'lottery_three_digit_pivots.res_time',
                'lottery_three_digit_pivots.play_date',
                'lottery_three_digit_pivots.play_time',
                'lottery_three_digit_pivots.match_start_date',
                'lottery_three_digit_pivots.match_status',
                'lottery_three_digit_pivots.win_lose',
                'lottery_three_digit_pivots.prize_sent',
                'lottos.slip_no'
            )
            ->where('lottery_three_digit_pivots.running_match', $running_match)
            ->where('lottery_three_digit_pivots.user_id', $user_id) // Filter where user id is auth id
            ->where('lottery_three_digit_pivots.prize_sent', 2)
            ->orderByDesc('lottery_three_digit_pivots.created_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $reports,
        ]);
    }

    public function ThirdPrizeWinnerApi()
    {
        $user_id = Auth()->id();
        $reports = DB::table('lottery_three_digit_pivots')
            ->select('running_match', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(sub_amount) as total_amount'))
            ->where('user_id', $user_id) // Filter where user id is auth id
            ->where('prize_sent', 3) // Filter where prize_sent is true
            ->groupBy('running_match')
            ->orderByDesc('running_match') // Optional: order by running_match descending
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $reports,
        ]);
    }

    public function getThirdPrizeShowByRunningMatch($running_match)
    {
        $user_id = Auth()->id();
        $reports = DB::table('lottery_three_digit_pivots')
            ->join('users', 'lottery_three_digit_pivots.user_id', '=', 'users.id')
            ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
            ->select(
                'users.user_name',
                'users.phone',
                'lottery_three_digit_pivots.agent_id',
                'lottery_three_digit_pivots.bet_digit',
                'lottery_three_digit_pivots.sub_amount',
                DB::raw('lottery_three_digit_pivots.sub_amount * 10 as total_prize_amount'),
                'lottery_three_digit_pivots.res_date',
                'lottery_three_digit_pivots.res_time',
                'lottery_three_digit_pivots.play_date',
                'lottery_three_digit_pivots.play_time',
                'lottery_three_digit_pivots.match_start_date',
                'lottery_three_digit_pivots.match_status',
                'lottery_three_digit_pivots.win_lose',
                'lottery_three_digit_pivots.prize_sent',
                'lottos.slip_no'
            )
            ->where('lottery_three_digit_pivots.running_match', $running_match)
            ->where('lottery_three_digit_pivots.user_id', $user_id) // Filter where user id is auth id
            ->where('lottery_three_digit_pivots.prize_sent', 3)
            ->orderByDesc('lottery_three_digit_pivots.created_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $reports,
        ]);
    }

    public function ThreeDFirstWinnerHistory(): JsonResponse
    {
        $results = LotteryThreeDigitPivot::where('prize_sent', 1)
            ->select('lottery_three_digit_pivots.*', 'users.name as user_name', DB::raw('sub_amount * 550 as prize_value'))
            ->join('users', 'lottery_three_digit_pivots.user_id', '=', 'users.id')
            ->orderByDesc('prize_value')
            ->get();

        return (new LotteryThreeDigitPivotCollection($results))
            ->response()
            ->setStatusCode(200);
    }

    // first winner history 
//     public function ThreeDFirstWinnerHistory(): JsonResponse
// {
//     $results = LotteryThreeDigitPivot::where('prize_sent', 1)
//         ->select('*', DB::raw('sub_amount * 550 as prize_value'))
//         ->orderByDesc('prize_value')
//         ->get();

//     return response()->json([
//         'success' => true,
//         'message' => 'Data 3D First Winner History retrieved successfully',
//         'data' => $results
//     ]);
// }

}
