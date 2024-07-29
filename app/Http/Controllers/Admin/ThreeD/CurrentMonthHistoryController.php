<?php

namespace App\Http\Controllers\Admin\ThreeD;

use App\Http\Controllers\Controller;
use App\Models\ThreeD\LotteryThreeDigitPivot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CurrentMonthHistoryController extends Controller
{
    public function getCurrentMonthRunningMatches()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        Log::info('Current Year: '.$currentYear);
        Log::info('Current Month: '.$currentMonth);
        Log::info('Current day: '.$currentDay);

        $currentMonthRunningMatches = LotteryThreeDigitPivot::with('user')
            ->whereYear('running_match', $currentYear)
            ->whereMonth('running_match', $currentMonth)
            ->get();

        Log::info('Matches found: '.$currentMonthRunningMatches->count());

        return response()->json($currentMonthRunningMatches);
    }
}
