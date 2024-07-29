<?php

namespace App\Services;

use App\Models\TwoD\LotteryTwoDigitPivot;
use App\Models\TwoD\TwoDigit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AgentEveningLegarService
{
    /**
     * Determine if today is a valid play day (Monday to Friday).
     */
    public function isPlayDay(): bool
    {
        $today = Carbon::now()->isoFormat('dddd');
        $playDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return in_array($today, $playDays);
    }

    /**
     * Determine the current session based on time.
     *
     * @return string
     */
    // public function getCurrentSession(): string
    // {
    //     $currentTime = Carbon::now()->format('H:i:s');

    //     if ($currentTime >= '04:00:00' && $currentTime <= '12:00:00') {
    //         return 'morning';
    //     } elseif ($currentTime >= '12:01:00' && $currentTime <= '16:30:00') {
    //         return 'evening';
    //     } else {
    //         return 'closed';
    //     }
    // }

    /**
     * Retrieve all two-digit numbers with their related sub-amounts for the current session and play day.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTwoDigitData()
    {
        try {
            if (! $this->isPlayDay()) {
                return collect(); // Return empty if today is not a play day
            }

            $currentSession = 'evening';
            if ($currentSession === 'closed') {
                return collect(); // Return empty if the session is closed
            }

            $today = Carbon::now()->format('Y-m-d');
            $user = Auth::user();
            $agent_id = $user->id;
            // Retrieve all two-digit numbers from the TwoDigit model
            $twoDigitNumbers = TwoDigit::all()->pluck('two_digit');

            // Retrieve the sub-amount totals for each bet_digit from the LotteryTwoDigitPivot model
            $subAmounts = LotteryTwoDigitPivot::where('res_date', $today)
                ->where('agent_id', $agent_id)
                ->where('session', $currentSession)
                ->select('bet_digit', DB::raw('SUM(sub_amount) as total_sub_amount'))
                ->groupBy('bet_digit')
                ->get()
                ->pluck('total_sub_amount', 'bet_digit');

            // Combine the two-digit numbers with their sub-amounts
            $twoDigitData = $twoDigitNumbers->map(function ($digit) use ($subAmounts) {
                return [
                    'digit' => $digit,
                    'total_sub_amount' => $subAmounts->get($digit, 0),
                ];
            });

            return $twoDigitData;
        } catch (\Exception $e) {
            Log::error('Error fetching two-digit data: '.$e->getMessage());

            return collect(); // Return empty collection on error
        }
    }
}
