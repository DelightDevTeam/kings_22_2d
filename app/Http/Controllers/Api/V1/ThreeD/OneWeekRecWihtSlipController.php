<?php

namespace App\Http\Controllers\Api\V1\ThreeD;

use App\Http\Controllers\Controller;
use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Models\ThreeD\Lotto;
use App\Models\ThreeD\ThreedSetting;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OneWeekRecWihtSlipController extends Controller
{
    use HttpResponses;

    public function index()
    {
        try {
            // Ensure the user is authenticated
            if (! auth()->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Get the authenticated user's ID
            $userId = auth()->id();

            // Get the match start date and result date from ThreedSetting
            $draw_date = ThreedSetting::where('status', 'open')->first();
            if (! $draw_date) {
                return response()->json(['error' => 'No open draw date found'], 404);
            }

            $start_date = $draw_date->match_start_date;
            $end_date = $draw_date->result_date;

            // Retrieve and group records by user_id within the specified date range
            $records = LotteryThreeDigitPivot::with('user')
                ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
                ->where('lottery_three_digit_pivots.user_id', $userId)
                ->whereBetween('lottery_three_digit_pivots.match_start_date', [$start_date, $end_date])
                ->whereBetween('lottery_three_digit_pivots.res_date', [$start_date, $end_date])
                ->select('lottery_three_digit_pivots.user_id', 'lottos.slip_no', DB::raw('SUM(lottery_three_digit_pivots.sub_amount) as total_sub_amount'))
                ->groupBy('lottery_three_digit_pivots.user_id', 'lottos.slip_no')
                ->get();

            // Calculate the total amount from the lottos table for the authenticated user within the date range
            $total_amount = Lotto::where('user_id', $userId)
                ->whereBetween('created_at', [$start_date, $end_date])
                ->sum('total_amount');

            $data = [
                'records' => $records,
                'total_amount' => $total_amount,
            ];

            // Success message
            return $this->success($data, 'Records retrieved successfully', 200);
            // return response()->json([
            //     'message' => 'Records retrieved successfully',
            //     'records' => $records,
            //     'total_amount' => $total_amount,
            // ], 200);

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error retrieving records: '.$e->getMessage());

            // Error message
            return response()->json(['error' => 'Failed to retrieve records'], 500);
        }
    }

    public function show($user_id, $slip_no)
    {
        try {
            // Ensure the user is authenticated
            if (! auth()->check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Retrieve records for a specific user_id and slip_no
            $records = LotteryThreeDigitPivot::with('user')
                ->join('lottos', 'lottery_three_digit_pivots.lotto_id', '=', 'lottos.id')
                ->where('lottery_three_digit_pivots.user_id', $user_id)
                ->where('lottos.slip_no', $slip_no)
                ->select('lottery_three_digit_pivots.*', 'lottos.slip_no')
                ->get();

            // Check if records are found
            if ($records->isEmpty()) {
                return response()->json(['error' => 'No records found'], 404);
            }

            // Calculate the total sub_amount for the specific user_id and slip_no
            $total_sub_amount = $records->sum('sub_amount');

            // Return the response with records and total sub_amount
            $data = [
                'records' => $records,
                'total_sub_amount' => $total_sub_amount,
                'user_id' => $user_id,
                'slip_no' => $slip_no,
            ];

            return $this->success($data, 'Records retrieved successfully', 200);
            // return response()->json([
            //     'message' => 'Records retrieved successfully',
            //     'records' => $records,
            //     'total_sub_amount' => $total_sub_amount,
            //     'slip_no' => $slip_no,
            //     'user_id' => $user_id,
            // ], 200);

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error retrieving records for user_id: '.$user_id.' and slip_no: '.$slip_no.'. Error: '.$e->getMessage());

            // Error message
            return response()->json(['error' => 'Failed to retrieve records'], 500);
        }
    }
}
