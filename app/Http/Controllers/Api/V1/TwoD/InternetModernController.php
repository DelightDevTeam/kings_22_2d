<?php

namespace App\Http\Controllers\Api\V1\TwoD;

use App\Http\Controllers\Controller;
use App\Models\TwoD\Internet;
use App\Models\TwoD\Modern;
use App\Models\TwoD\TwodSetting;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InternetModernController extends Controller
{
    use HttpResponses;

    public function CurrentPrizeindex()
    {
        // Get today's date
        $today = Carbon::now()->format('Y-m-d');

        // Retrieve the latest result for today's morning session
        $morningSession = TwodSetting::where('result_date', $today)
            ->where('session', 'morning')
            ->first();

        // Retrieve the latest result for today's evening session
        $eveningSession = TwodSetting::where('result_date', $today)
            ->where('session', 'evening')
            ->first();

        return response()->json([
            'morning_prize' => $morningSession,
            'evening_prize' => $eveningSession,
        ]);
    }

    public function index()
    {
        $morningData = Internet::where('session', 'morning')->orderBy('id', 'desc')->first();
        $eveningData = Internet::where('session', 'evening')->orderBy('id', 'desc')->first();

        // if (!$morningData || !$eveningData) {
        //     return response()->json([
        //         'error' => 'Data not found for one or both sessions',
        //     ], 404);
        // }
        return $this->success([
            'morningData' => $morningData,
            'eveningData' => $eveningData,
        ]);

        // return response()->json([
        //     'internet_morningData' => $morningData,
        //     'internet_eveningData' => $eveningData,
        // ]);
    }

    public function Modernindex()
    {
        $modernMorningData = Modern::where('session', 'morning')->orderBy('id', 'desc')->first();
        $modernEveningData = Modern::where('session', 'evening')->orderBy('id', 'desc')->first();

        // if (!$morningData || !$eveningData) {
        //     return response()->json([
        //         'error' => 'Data not found for one or both sessions',
        //     ], 404);
        // }
        return $this->success([
            'modern_morningData' => $modernMorningData,
            'modern_eveningData' => $modernEveningData,
        ]);

        // return response()->json([
        //     'modern_morningData' => $modernMorningData,
        //     'modern_eveningData' => $modernEveningData,
        // ]);
    }
}
