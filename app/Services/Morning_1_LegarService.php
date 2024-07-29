<?php

namespace App\Services;

use App\Helpers\SessionHelper;
use App\Models\LotteryTwoDigitPivot;
use App\Models\TwoD\TwoDigit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MorningLegarService
{
    /**
     * Determine if today is a valid play day (Monday to Friday).
     */
    public function isPlayDay(): bool
    {
        $today = Carbon::now()->isoFormat('dddd');
        $playDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return in_array($today, $playDays);
    }

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

            $currentSession = SessionHelper::getCurrentSession();
            if ($currentSession === 'closed') {
                return collect(); // Return empty if the session is closed
            }

            // Retrieve all two-digit numbers with the sum of sub-amounts from the pivot table
            $twoDigitData = TwoDigit::select('two_digit')
                ->leftJoin('lottery_two_digit_pivots', 'two_digits.id', '=', 'lottery_two_digit_pivots.two_digit_id')
                ->where('lottery_two_digit_pivots.session', $currentSession) // Filter by session
                ->groupBy('two_digit') // Group by the two-digit number
                ->selectRaw('two_digit, SUM(lottery_two_digit_pivots.sub_amount) as total_sub_amount') // Sum of sub-amounts
                ->orderBy('two_digit', 'asc') // Sort by the two-digit number
                ->get();

            return $twoDigitData;
        } catch (\Exception $e) {
            Log::error('Error fetching two-digit data: '.$e->getMessage());

            return collect(); // Return empty collection on error
        }
    }
}
