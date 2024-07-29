<?php

namespace App\Http\Controllers\Admin\ThreeD;

use App\Http\Controllers\Controller;
use App\Models\ThreeD\ThreeDigit;
use App\Models\ThreeD\ThreeDLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetLottoDataByRunningMatchController extends Controller
{
    public function getDetailedGroupedByRunningMatch()
    {
        $results = DB::table('lottery_three_digit_pivots')
            ->join('users', 'lottery_three_digit_pivots.user_id', '=', 'users.id')
            ->select(
                'lottery_three_digit_pivots.running_match',
                'users.user_name',
                'users.phone',
                'lottery_three_digit_pivots.agent_id',
                'lottery_three_digit_pivots.bet_digit',
                'lottery_three_digit_pivots.sub_amount',
                'lottery_three_digit_pivots.res_date',
                'lottery_three_digit_pivots.res_time',
                'lottery_three_digit_pivots.play_date',
                'lottery_three_digit_pivots.play_time',
                'lottery_three_digit_pivots.match_start_date',
                'lottery_three_digit_pivots.match_status',
                'lottery_three_digit_pivots.win_lose',
                'lottery_three_digit_pivots.prize_sent',
                DB::raw('COUNT(*) as total_records'),
                DB::raw('SUM(lottery_three_digit_pivots.sub_amount) as total_amount')
            )
            ->groupBy(
                'lottery_three_digit_pivots.running_match',
                'users.user_name',
                'users.phone',
                'lottery_three_digit_pivots.agent_id',
                'lottery_three_digit_pivots.bet_digit',
                'lottery_three_digit_pivots.sub_amount',
                'lottery_three_digit_pivots.res_date',
                'lottery_three_digit_pivots.res_time',
                'lottery_three_digit_pivots.play_date',
                'lottery_three_digit_pivots.play_time',
                'lottery_three_digit_pivots.match_start_date',
                'lottery_three_digit_pivots.match_status',
                'lottery_three_digit_pivots.win_lose',
                'lottery_three_digit_pivots.prize_sent'
            )
            ->get();

        return response()->json($results);
    }

    public function index(Request $request)
    {
        $reports = DB::table('lottery_three_digit_pivots')
            ->join('users', 'lottery_three_digit_pivots.user_id', '=', 'users.id')
            ->select(
                'lottery_three_digit_pivots.running_match',
                'users.user_name',
                'users.phone',
                'lottery_three_digit_pivots.agent_id',
                'lottery_three_digit_pivots.bet_digit',
                'lottery_three_digit_pivots.sub_amount',
                'lottery_three_digit_pivots.res_date',
                'lottery_three_digit_pivots.res_time',
                'lottery_three_digit_pivots.play_date',
                'lottery_three_digit_pivots.play_time',
                'lottery_three_digit_pivots.match_start_date',
                'lottery_three_digit_pivots.match_status',
                'lottery_three_digit_pivots.win_lose',
                'lottery_three_digit_pivots.prize_sent',
                DB::raw('COUNT(*) as total_records'),
                DB::raw('SUM(lottery_three_digit_pivots.sub_amount) as total_amount')
            )
            ->groupBy(
                'lottery_three_digit_pivots.running_match',
                'users.user_name',
                'users.phone',
                'lottery_three_digit_pivots.agent_id',
                'lottery_three_digit_pivots.bet_digit',
                'lottery_three_digit_pivots.sub_amount',
                'lottery_three_digit_pivots.res_date',
                'lottery_three_digit_pivots.res_time',
                'lottery_three_digit_pivots.play_date',
                'lottery_three_digit_pivots.play_time',
                'lottery_three_digit_pivots.match_start_date',
                'lottery_three_digit_pivots.match_status',
                'lottery_three_digit_pivots.win_lose',
                'lottery_three_digit_pivots.prize_sent'
            )
            ->orderByDesc('lottery_three_digit_pivots.running_match')
            ->get();

        return view('admin.three_d.report.index', compact('reports'));
    }

    public function getGroupedByRunningMatch()
    {
        $reports = DB::table('lottery_three_digit_pivots')
            ->select('running_match', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(sub_amount) as total_amount'))
            ->groupBy('running_match')
            ->get();

        return view('admin.three_d.report.index', compact('reports'));

        //return response()->json($results);
    }

    public function getDetailsByRunningMatch($running_match)
    {
        $reports = DB::table('lottery_three_digit_pivots')
            ->join('users', 'lottery_three_digit_pivots.user_id', '=', 'users.id')
            ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
            ->select(
                'users.user_name',
                'users.phone',
                'lottery_three_digit_pivots.agent_id',
                'lottery_three_digit_pivots.bet_digit',
                'lottery_three_digit_pivots.sub_amount',
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
            ->orderByDesc('lottery_three_digit_pivots.created_at')
            ->get();

        return view('admin.three_d.report.show', compact('reports'));

        //return response()->json($details);
    }

    public function LottoLegar($running_match)
    {
        $defaultBreak = ThreeDLimit::lasted()->first();

        // Retrieve all three-digit numbers from the ThreeDigit model
        $threeDigitNumbers = ThreeDigit::all()->pluck('three_digit');

        $reports = DB::table('lottery_three_digit_pivots')
            ->join('users', 'lottery_three_digit_pivots.user_id', '=', 'users.id')
            ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
            ->select(
                'users.user_name',
                'users.phone',
                'lottery_three_digit_pivots.agent_id',
                'lottery_three_digit_pivots.bet_digit',
                'lottery_three_digit_pivots.sub_amount',
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
            ->orderByDesc('lottery_three_digit_pivots.created_at')
            ->get();

        // Calculate the sub amounts for each digit
        $subAmounts = DB::table('lottery_three_digit_pivots')
            ->select(DB::raw('LEFT(bet_digit, 3) as three_digit'), DB::raw('SUM(sub_amount) as total_sub_amount'))
            ->where('running_match', $running_match)
            ->groupBy(DB::raw('LEFT(bet_digit, 3)'))
            ->pluck('total_sub_amount', 'three_digit');

        // Combine the threeDigitNumbers numbers with their sub-amounts
        $twoDigitData = $threeDigitNumbers->map(function ($digit) use ($subAmounts) {
            return [
                'digit' => $digit,
                'total_sub_amount' => $subAmounts->get(substr($digit, 0, 3), 0),
            ];
        });

        return view('admin.three_d.report.lager_show', compact('reports', 'twoDigitData', 'defaultBreak'));
    }
}
