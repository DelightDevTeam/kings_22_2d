<?php

namespace App\Http\Controllers\Admin\ThreeD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LottoWinnerHistoryController extends Controller
{
    public function getGroupedByRunningMatch()
    {
        $reports = DB::table('lottery_three_digit_pivots')
            ->select('running_match', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(sub_amount) as total_amount'))
            ->where('prize_sent', true) // Filter where prize_sent is true
            ->groupBy('running_match')
            ->orderByDesc('running_match') // Optional: order by running_match descending
            ->get();

        return view('admin.three_d.report.first_winner.index', compact('reports'));

        //return response()->json($results);
    }

    public function getFirstPrizeDetailsByRunningMatch($running_match)
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
            ->where('lottery_three_digit_pivots.prize_sent', true)
            ->orderByDesc('lottery_three_digit_pivots.created_at')
            ->get();

        return view('admin.three_d.report.first_winner.show', compact('reports'));

        //return response()->json($details);
    }

    public function getSecondPrizeGroupedByRunningMatch()
    {
        $reports = DB::table('lottery_three_digit_pivots')
            ->select('running_match', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(sub_amount) as total_amount'))
            ->where('prize_sent', 2) // Filter where prize_sent is true
            ->groupBy('running_match')
            ->orderByDesc('running_match') // Optional: order by running_match descending
            ->get();

        return view('admin.three_d.report.second_winner.index', compact('reports'));

        //return response()->json($results);
    }

    public function getSecondPrizeDetailsByRunningMatch($running_match)
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
            ->where('lottery_three_digit_pivots.prize_sent', true)
            ->orderByDesc('lottery_three_digit_pivots.created_at')
            ->get();

        return view('admin.three_d.report.second_winner.show', compact('reports'));

        //return response()->json($details);
    }

    public function getThirdPrizeGroupedByRunningMatch()
    {
        $reports = DB::table('lottery_three_digit_pivots')
            ->select('running_match', DB::raw('COUNT(*) as total_records'), DB::raw('SUM(sub_amount) as total_amount'))
            ->where('prize_sent', 3) // Filter where prize_sent is true
            ->groupBy('running_match')
            ->orderByDesc('running_match') // Optional: order by running_match descending
            ->get();

        return view('admin.three_d.report.third_winner.index', compact('reports'));

        //return response()->json($results);
    }

    public function getThirdPrizeDetailsByRunningMatch($running_match)
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
            ->where('lottery_three_digit_pivots.prize_sent', true)
            ->orderByDesc('lottery_three_digit_pivots.created_at')
            ->get();

        return view('admin.three_d.report.third_winner.show', compact('reports'));

        //return response()->json($details);
    }
}
