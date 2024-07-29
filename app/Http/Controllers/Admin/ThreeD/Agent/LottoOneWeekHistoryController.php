<?php

namespace App\Http\Controllers\Admin\ThreeD\Agent;

use App\Http\Controllers\Controller;
use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Models\ThreeD\Lotto;
use App\Models\ThreeD\ThreedSetting;
use App\Services\AgentLottoHistoryRecordService;
use App\Services\AgentLottoOneWeekRecordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LottoOneWeekHistoryController extends Controller
{
    protected $lottoService;

    protected $agentLotto;

    public function __construct(AgentLottoOneWeekRecordService $lottoService, AgentLottoHistoryRecordService $agentLotto)
    {
        $this->lottoService = $lottoService;
        $this->agentLotto = $agentLotto;
    }

    public function AgentRecordsForOneWeek()
    {
        $data = $this->lottoService->GetRecordForOneWeek();
        //$total_sub_amount = $this->lottoService->GetRecordForOneWeek();

        return view('admin.three_d.agent.records.one_week_rec', compact('data'));
    }

    public function showRecords()
    {
        $data = $this->agentLotto->GetRecord();
        //$total_sub_amount = $this->lottoService->GetRecordForOneWeek();

        return view('admin.three_d.agent.history.all_history', compact('data'));
    }

    public function index()
    {
        $agent_id = Auth::user();

        // Get the match start date and result date from ThreedSetting
        $draw_date = ThreedSetting::where('status', 'open')->first();
        $start_date = $draw_date->match_start_date;
        $end_date = $draw_date->result_date;

        // Retrieve and group records by user_id within the specified date range
        $records = LotteryThreeDigitPivot::with('user')
            ->where('agent_id', $agent_id->id)
            ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
            ->whereBetween('lottery_three_digit_pivots.match_start_date', [$start_date, $end_date])
            ->whereBetween('lottery_three_digit_pivots.res_date', [$start_date, $end_date])
            ->select('lottery_three_digit_pivots.user_id', 'lottos.slip_no', DB::raw('SUM(lottery_three_digit_pivots.sub_amount) as total_sub_amount'))
            ->groupBy('lottery_three_digit_pivots.user_id', 'lottos.slip_no')
            ->get();
        // Calculate the total amount from the lottos table within the date range
        $total_amount = Lotto::whereBetween('created_at', [$start_date, $end_date])
            ->sum('total_amount');
        //return response()->json($records, $total_amount);

        // You can return the records to your view
        return view('admin.three_d.agent.records.one_week_slip', compact('records', 'total_amount'));
    }

    public function show($user_id, $slip_no)
    {
        // Retrieve records for a specific user_id and slip_no
        $records = LotteryThreeDigitPivot::with('user')
            ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
            ->where('lottery_three_digit_pivots.user_id', $user_id)
            ->where('lottos.slip_no', $slip_no)
            ->select('lottery_three_digit_pivots.*', 'lottos.slip_no')
            ->get();

        // Calculate the total sub_amount for the specific user_id and slip_no
        $total_sub_amount = $records->sum('sub_amount');

        return view('admin.three_d.agent.records.slip_show', compact('records', 'total_sub_amount', 'slip_no', 'user_id'));
    }

    public function Agentindex()
    {
        // try {
        $agent_id = Auth::user();
        // Retrieve records with the user's name
        $records = LotteryThreeDigitPivot::with('user')
            ->where('agent_id', $agent_id->id)
            ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
            ->select('lottery_three_digit_pivots.user_id', 'lottos.slip_no', DB::raw('SUM(lottery_three_digit_pivots.sub_amount) as total_sub_amount'))
            ->groupBy('lottery_three_digit_pivots.user_id', 'lottos.slip_no')
            ->get();

        // Check if records are found
        if ($records->isEmpty()) {
            return response()->json(['error' => 'No records found'], 404);
        }

        // Calculate the total amount from the lottos table
        $total_amount = Lotto::sum('total_amount');

        // Return the view with records and total amount
        return view('admin.three_d.agent.history.index', [
            'records' => $records,
            'total_amount' => $total_amount,
        ]);

        // } catch (\Exception $e) {
        //     // Log the exception
        //     Log::error('Error retrieving records. Error: '.$e->getMessage());

        //     // Error message
        //     return response()->json(['error' => 'Failed to retrieve records'], 500);
        // }
    }

    public function Agentshow($user_id, $slip_no)
    {
        try {

            // Retrieve records for a specific user_id and slip_no with the user's name
            $records = LotteryThreeDigitPivot::with('user')
                ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
                ->where('lottery_three_digit_pivots.user_id', $user_id)
                ->where('lottos.slip_no', $slip_no)
                ->select('lottery_three_digit_pivots.*', 'lottos.slip_no', 'users.name as user_name')
                ->join('users', 'lottery_three_digit_pivots.user_id', '=', 'users.id')
                ->get();

            // Check if records are found
            if ($records->isEmpty()) {
                return response()->json(['error' => 'No records found'], 404);
            }

            // Calculate the total sub_amount for the specific user_id and slip_no
            $total_sub_amount = $records->sum('sub_amount');

            // Return the view with records and total sub_amount
            return view('admin.three_d.agent.history.show', [
                'records' => $records,
                'total_sub_amount' => $total_sub_amount,
                'slip_no' => $slip_no,
                'user_id' => $user_id,
            ]);

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error retrieving records for user_id: '.$user_id.' and slip_no: '.$slip_no.'. Error: '.$e->getMessage());

            // Error message
            return response()->json(['error' => 'Failed to retrieve records'], 500);
        }
    }
}
