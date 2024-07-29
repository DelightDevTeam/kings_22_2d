<?php

namespace App\Http\Controllers\Admin\TwoD\Agent;

use App\Http\Controllers\Controller;
use App\Models\TwoD\Lottery;
use App\Models\TwoD\LotteryTwoDigitPivot;
use App\Models\TwoD\TwodSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgentSlipController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $agent_id = $user->id;
        // Get the match start date and result date from TwodSetting
        $draw_date = TwodSetting::where('status', 'open')->first();
        if (! $draw_date) {
            return view('admin.two_d.agent.slip.morning_slip', [
                'records' => collect([]),
                'total_amount' => 0,
                'error' => 'No open draw date found.',
            ]);
        }

        $start_date = $draw_date->result_date;

        // Log the start date and conditions for debugging
        //Log::info('Start date:', ['start_date' => $start_date]);

        // Enable query logging
        //DB::enableQueryLog();

        // Retrieve and group records by user_id within the specified date range
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.agent_id', $agent_id)
            ->where('lottery_two_digit_pivots.res_date', $start_date)
            ->where('lottery_two_digit_pivots.session', 'morning')
            ->select('lottery_two_digit_pivots.user_id', 'lotteries.slip_no', DB::raw('SUM(lottery_two_digit_pivots.sub_amount) as total_sub_amount'))
            ->groupBy('lottery_two_digit_pivots.user_id', 'lotteries.slip_no')
            ->get();

        // Log the retrieved records for debugging
        Log::info('Retrieved records:', ['records' => $records]);

        // Log the actual SQL query executed
        $queries = DB::getQueryLog();
        Log::info('Executed query:', ['queries' => $queries]);

        // Calculate the total amount from the lotteries table within the date range
        //$total_amount = Lottery::whereDate('created_at', $start_date)->sum('total_amount');
        // $total_amount = Lottery::whereDate('created_at', $start_date)
        //     ->where('session', 'morning')
        //     ->sum('total_amount');
        $total_amount = Lottery::whereHas('lotteryTwoDigitPivots', function ($query) use ($agent_id) {
            $query->where('agent_id', $agent_id)
                ->where('session', 'morning');
        })
            ->whereDate('created_at', $start_date)
            ->sum('total_amount');
        // Log the total amount for debugging
        Log::info('Total amount:', ['total_amount' => $total_amount]);

        // Return the records to your view
        return view('admin.two_d.agent.slip.morning_slip', compact('records', 'total_amount'));
    }

    public function show($user_id, $slip_no)
    {
        // Retrieve records for a specific user_id and slip_no
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.user_id', $user_id)
            ->where('lotteries.slip_no', $slip_no)
            ->select('lottery_two_digit_pivots.*', 'lotteries.slip_no')
            ->get();

        // Calculate the total sub_amount for the specific user_id and slip_no
        $total_sub_amount = $records->sum('sub_amount');

        return view('admin.two_d.agent.slip.morning_slip_show', compact('records', 'total_sub_amount', 'slip_no', 'user_id'));
    }

    public function Eveningindex()
    {
        $user = Auth::user();
        $agent_id = $user->id;
        // Get the match start date and result date from TwodSetting
        $draw_date = TwodSetting::where('status', 'open')->first();
        if (! $draw_date) {
            return view('admin.two_d.agent.slip.evening_slip', [
                'records' => collect([]),
                'total_amount' => 0,
                'error' => 'No open draw date found.',
            ]);
        }

        $start_date = $draw_date->result_date;

        // Log the start date and conditions for debugging
        Log::info('Start date:', ['start_date' => $start_date]);

        // Enable query logging
        DB::enableQueryLog();

        // Retrieve and group records by user_id within the specified date range
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.agent_id', $agent_id)
            ->where('lottery_two_digit_pivots.res_date', $start_date)
            ->where('lottery_two_digit_pivots.session', 'evening')
            ->select('lottery_two_digit_pivots.user_id', 'lotteries.slip_no', DB::raw('SUM(lottery_two_digit_pivots.sub_amount) as total_sub_amount'))
            ->groupBy('lottery_two_digit_pivots.user_id', 'lotteries.slip_no')
            ->get();

        // Log the retrieved records for debugging
        Log::info('Retrieved records:', ['records' => $records]);

        // Log the actual SQL query executed
        $queries = DB::getQueryLog();
        Log::info('Executed query:', ['queries' => $queries]);

        $total_amount = Lottery::whereHas('lotteryTwoDigitPivots', function ($query) use ($agent_id) {
            $query->where('agent_id', $agent_id)
                ->where('session', 'evening');
        })
            ->whereDate('created_at', $start_date)
            ->sum('total_amount');
        // Log the total amount for debugging
        Log::info('Total amount:', ['total_amount' => $total_amount]);

        // Return the records to your view
        return view('admin.two_d.agent.slip.evening_slip', compact('records', 'total_amount'));
    }

    public function Eveningshow($user_id, $slip_no)
    {
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')

            ->where('lottery_two_digit_pivots.user_id', $user_id)
            ->where('lotteries.slip_no', $slip_no)
            ->select('lottery_two_digit_pivots.*', 'lotteries.slip_no')
            ->get();

        // Calculate the total sub_amount for the specific user_id and slip_no
        $total_sub_amount = $records->sum('sub_amount');

        return view('admin.two_d.slip.agent.evening_slip_show', compact('records', 'total_sub_amount', 'slip_no', 'user_id'));
    }

    public function AllSlipForMorningindex()
    {
        // Enable query logging
        //DB::enableQueryLog();
        $user = Auth::user();
        $agent_id = $user->id;
        // Retrieve and group records by user_id for the morning session
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.agent_id', $agent_id)
            ->where('lottery_two_digit_pivots.session', 'morning')
            ->select('lottery_two_digit_pivots.user_id', 'lottery_two_digit_pivots.res_date', 'lotteries.slip_no', DB::raw('SUM(lottery_two_digit_pivots.sub_amount) as total_sub_amount'))
            ->groupBy('lottery_two_digit_pivots.user_id', 'lottery_two_digit_pivots.res_date', 'lotteries.slip_no')
            ->get();

        // Log the retrieved records for debugging
        Log::info('Retrieved records:', ['records' => $records]);

        // Log the actual SQL query executed
        $queries = DB::getQueryLog();
        Log::info('Executed query:', ['queries' => $queries]);

        // Calculate the total amount for the morning session from the lotteries table
        // $total_amount = Lottery::where('session', 'morning')
        //     ->sum('total_amount');

        $total_amount = Lottery::whereHas('lotteryTwoDigitPivots', function ($query) use ($agent_id) {
            $query->where('agent_id', $agent_id)
                ->where('session', 'morning');
        })
            ->sum('total_amount');

        // Log the total amount for debugging
        Log::info('Total amount:', ['total_amount' => $total_amount]);

        // Return the records to your view
        return view('admin.two_d.agent.slip.morning_all_slip', compact('records', 'total_amount'));
    }

    public function MorningAllSlipshow($user_id, $slip_no)
    {
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.user_id', $user_id)
            ->where('lotteries.slip_no', $slip_no)
            ->select('lottery_two_digit_pivots.*', 'lotteries.slip_no')
            ->get();

        // Calculate the total sub_amount for the specific user_id and slip_no
        $total_sub_amount = $records->sum('sub_amount');

        return view('admin.two_d.agent.slip.morning_all_slip_show', compact('records', 'total_sub_amount', 'slip_no', 'user_id'));
    }

    public function AllSlipForEveningindex()
    {
        $user = Auth::user();
        $agent_id = $user->id;
        // Enable query logging
        DB::enableQueryLog();

        // Retrieve and group records by user_id for the morning session
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.agent_id', $agent_id)
            ->where('lottery_two_digit_pivots.session', 'evening')
            ->select('lottery_two_digit_pivots.user_id', 'lottery_two_digit_pivots.res_date', 'lotteries.slip_no', DB::raw('SUM(lottery_two_digit_pivots.sub_amount) as total_sub_amount'))
            ->groupBy('lottery_two_digit_pivots.user_id', 'lottery_two_digit_pivots.res_date', 'lotteries.slip_no')
            ->get();

        // Log the retrieved records for debugging
        Log::info('Retrieved records:', ['records' => $records]);

        // Log the actual SQL query executed
        $queries = DB::getQueryLog();
        Log::info('Executed query:', ['queries' => $queries]);

        // Calculate the total amount for the morning session from the lotteries table
        $total_amount = Lottery::whereHas('lotteryTwoDigitPivots', function ($query) use ($agent_id) {
            $query->where('agent_id', $agent_id)
                ->where('session', 'evening');
        })
            ->sum('total_amount');

        // Log the total amount for debugging
        Log::info('Total amount:', ['total_amount' => $total_amount]);

        // Return the records to your view
        return view('admin.two_d.agent.slip.evening_all_slip', compact('records', 'total_amount'));
    }

    public function EveningAllSlipshow($user_id, $slip_no)
    {
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.user_id', $user_id)
            ->where('lotteries.slip_no', $slip_no)
            ->select('lottery_two_digit_pivots.*', 'lotteries.slip_no')
            ->get();

        // Calculate the total sub_amount for the specific user_id and slip_no
        $total_sub_amount = $records->sum('sub_amount');

        return view('admin.two_d.agent.slip.evening_all_slip_show', compact('records', 'total_sub_amount', 'slip_no', 'user_id'));
    }
}
