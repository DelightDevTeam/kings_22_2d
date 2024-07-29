<?php

namespace App\Http\Controllers\Admin\ThreeD;

use App\Helpers\MatchTimeHelper;
use App\Http\Controllers\Controller;
use App\Models\ThreeD\ThreedMatchTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ThreedMatchTimeController extends Controller
{
    public function getCurrentYearAndMatchTimes()
    {
        $currentYear = Carbon::now()->year;
        $currentDate = Carbon::now()->toDateString();

        //logger()->info("Fetching match times for year: $currentYear and date: $currentDate");

        // Get all match times for the current year
        $yearMatchTimes = ThreedMatchTime::whereYear('result_date', $currentYear)->get();

        // Get the match times for the current date
        $currentMatchTimes = ThreedMatchTime::whereDate('result_date', $currentDate)->get();

        //logger()->info('Current match times count: '.$currentMatchTimes->count());

        // If no match times for the current date, fetch the first match time of the next month
        if ($currentMatchTimes->isEmpty()) {
            $nextMonth = Carbon::now()->addMonth()->month;
            $nextMonthMatchTimes = ThreedMatchTime::whereYear('result_date', $currentYear)
                ->whereMonth('result_date', $nextMonth)
                ->orderBy('result_date', 'asc')
                ->first();

            $currentMatchTimes = collect([$nextMonthMatchTimes]);
        }
        $matchTimes = MatchTimeHelper::getCurrentYearAndMatchTimes();

        return view('admin.three_d.match_time.index', [
            'currentYear' => $currentYear,
            'yearMatchTimes' => $yearMatchTimes,
            'currentMatchTimes' => $currentMatchTimes,
            'matchTimes' => $matchTimes,
        ]);

        // return response()->json([
        //     'year_match_times' => $yearMatchTimes,
        //     'current_match_times' => $currentMatchTimes,
        // ]);
    }

    public function getCurrentMatchTimes()
    {
        $currentYear = Carbon::now()->year;
        $currentDate = Carbon::now()->toDateString();

        // Define the match dates for each month
        $matchDates = [
            'January' => $currentYear.'-01-16',
            'February' => $currentYear.'-02-01',
            'February_16' => $currentYear.'-02-16',
            'March' => $currentYear.'-03-01',
            'March_16' => $currentYear.'-03-16',
            'April' => $currentYear.'-04-01',
            'April_16' => $currentYear.'-04-16',
            'May' => $currentYear.'-05-02',
            'May_16' => $currentYear.'-05-16',
            'June' => $currentYear.'-06-01',
            'June_16' => $currentYear.'-06-16',
            'July' => $currentYear.'-07-01',
            'July_16' => $currentYear.'-07-16',
            'August' => $currentYear.'-08-01',
            'August_16' => $currentYear.'-08-16',
            'September' => $currentYear.'-09-01',
            'September_16' => $currentYear.'-09-16',
            'October' => $currentYear.'-10-01',
            'October_16' => $currentYear.'-10-16',
            'November' => $currentYear.'-11-01',
            'November_16' => $currentYear.'-11-16',
            'December' => $currentYear.'-12-01',
            'December_16' => $currentYear.'-12-16',
            'December_31' => $currentYear.'-12-31',
        ];

        // Initialize an empty array to hold match times
        $currentMatchTimes = [];

        // Loop through each month and fetch the match time for the defined date
        foreach ($matchDates as $month => $date) {
            $matchTime = ThreedMatchTime::whereDate('result_date', $date)->first();
            if ($matchTime) {
                $currentMatchTimes[$month] = $matchTime;
            }
        }

        // Log info
        logger()->info("Fetching match times for year: $currentYear and date: $currentDate");

        // Get all match times for the current year
        $yearMatchTimes = ThreedMatchTime::whereYear('result_date', $currentYear)->get();

        // If no match times for the current date, fetch the first match time of the next month
        if (empty($currentMatchTimes)) {
            $nextMonth = Carbon::now()->addMonth()->month;
            $nextMonthMatchTimes = ThreedMatchTime::whereYear('result_date', $currentYear)
                ->whereMonth('result_date', $nextMonth)
                ->orderBy('result_date', 'asc')
                ->first();

            $currentMatchTimes = [$nextMonthMatchTimes];
        }
        $matchTimes = MatchTimeHelper::getCurrentYearAndMatchTimes();

        // Log current match times count
        logger()->info('Current match times count: '.count($currentMatchTimes));

        return response()->json([
            'year_match_times' => $yearMatchTimes,
            'current_match_times' => $currentMatchTimes,
            'match_time' => $matchTimes,
        ]);
    }

    public function CurrentMatchTimes()
    {
        $matchTimes = MatchTimeHelper::getCurrentYearAndMatchTimes();
        //Log::info('Session Data', session()->all());

        return response()->json($matchTimes);
    }
}
