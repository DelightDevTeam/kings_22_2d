<?php

namespace App\Http\Controllers\Api\V1\TwoD;

use App\Http\Controllers\Controller;
use App\Models\TwoD\Lottery;
use App\Models\TwoD\LotteryTwoDigitPivot;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiSlipController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $currentDate = Carbon::now('Asia/Yangon')->format('Y-m-d');
        $userId = auth()->id();

        // Retrieve and group records by user_id within the specified date range
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.user_id', $userId)
            ->where('lottery_two_digit_pivots.res_date', $currentDate)
            ->where('lottery_two_digit_pivots.session', 'morning')
            ->select('lottery_two_digit_pivots.user_id', 'lotteries.slip_no', DB::raw('SUM(lottery_two_digit_pivots.sub_amount) as total_sub_amount'))
            ->groupBy('lottery_two_digit_pivots.user_id', 'lotteries.slip_no')
            ->get();

        // Calculate the total amount from the lotteries table within the date range
        $total_amount = Lottery::whereDate('created_at', $currentDate)
            ->where('session', 'morning')
            ->sum('total_amount');
        $data = [
            'records' => $records,
            'total_amount' => $total_amount,
        ];

        // Return the records as a JSON response
        return $this->success($data, 'Records retrieved successfully', 200);

        // return response()->json([
        //     'records' => $records,
        //     'total_amount' => $total_amount
        // ]);
    }

    public function show($slip_no)
    {
        $userId = auth()->id();
        // Retrieve records for a specific user_id and slip_no
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.user_id', $userId)
            ->where('lotteries.slip_no', $slip_no)
            ->select('lottery_two_digit_pivots.*', 'lotteries.slip_no')
            ->get();

        // Calculate the total sub_amount for the specific user_id and slip_no
        $total_sub_amount = $records->sum('sub_amount');
        $data = [
            'records' => $records,
            'total_sub_amount' => $total_sub_amount,
            'slip_no' => $slip_no,
            'user_id' => $userId,
        ];

        // Return the records and total sub_amount as a JSON response
        //return response()->json();
        return $this->success($data, 'Records retrieved successfully', 200);

    }

    public function Eveningindex()
    {
        $currentDate = Carbon::now('Asia/Yangon')->format('Y-m-d');
        $userId = auth()->id();

        // Retrieve and group records by user_id within the specified date range
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.user_id', $userId)
            ->where('lottery_two_digit_pivots.res_date', $currentDate)
            ->where('lottery_two_digit_pivots.session', 'evening')
            ->select('lottery_two_digit_pivots.user_id', 'lotteries.slip_no', DB::raw('SUM(lottery_two_digit_pivots.sub_amount) as total_sub_amount'))
            ->groupBy('lottery_two_digit_pivots.user_id', 'lotteries.slip_no')
            ->get();

        // Calculate the total amount from the lotteries table within the date range
        $total_amount = Lottery::whereDate('created_at', $currentDate)
            ->where('session', 'evening')
            ->sum('total_amount');
        $data = [
            'records' => $records,
            'total_amount' => $total_amount,
        ];

        // Return the records as a JSON response
        return $this->success($data, 'Records retrieved successfully', 200);

        // return response()->json([
        //     'records' => $records,
        //     'total_amount' => $total_amount
        // ]);
    }

    public function Eveningshow($slip_no)
    {
        $userId = auth()->id();
        // Retrieve records for a specific user_id and slip_no
        $records = LotteryTwoDigitPivot::with('user')
            ->join('lotteries', 'lottery_two_digit_pivots.lottery_id', '=', 'lotteries.id')
            ->where('lottery_two_digit_pivots.user_id', $userId)
            ->where('lotteries.slip_no', $slip_no)
            ->select('lottery_two_digit_pivots.*', 'lotteries.slip_no')
            ->get();

        // Calculate the total sub_amount for the specific user_id and slip_no
        $total_sub_amount = $records->sum('sub_amount');
        $data = [
            'records' => $records,
            'total_sub_amount' => $total_sub_amount,
            'slip_no' => $slip_no,
            'user_id' => $userId,
        ];

        // Return the records and total sub_amount as a JSON response
        //return response()->json();
        return $this->success($data, 'Records retrieved successfully', 200);

    }
}
