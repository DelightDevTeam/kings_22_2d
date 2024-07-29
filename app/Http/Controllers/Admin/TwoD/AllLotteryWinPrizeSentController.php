<?php

namespace App\Http\Controllers\Admin\TwoD;

use App\Http\Controllers\Controller;
use App\Services\LotteryAllPrizeSentService;

class AllLotteryWinPrizeSentController extends Controller
{
    protected $prizeSentService;

    public function __construct(LotteryAllPrizeSentService $prizeSentService)
    {
        $this->prizeSentService = $prizeSentService;
    }

    public function TwoAllWinHistoryForAdmin()
    {
        try {
            $data = $this->prizeSentService->AllWinPrizeSentForAdmin();

            return view('admin.two_d.all_winner.index', [
                'results' => $data['results'],
                'totalPrizeAmount' => $data['totalPrizeAmount'],
            ]);

        } catch (\Exception $e) {
            return view('admin.two_d.all_winner.index', [
                'error' => 'Failed to retrieve data. Please try again later.',
            ]);
        }
    }
}
