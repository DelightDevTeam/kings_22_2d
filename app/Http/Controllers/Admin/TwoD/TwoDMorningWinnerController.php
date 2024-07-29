<?php

namespace App\Http\Controllers\Admin\TwoD;

use App\Http\Controllers\Controller;
use App\Models\Admin\Lottery;
use App\Models\Admin\TwodWiner;
use App\Services\AdminEveningPrizeSentService;
use App\Services\AdminMorningPrizeSentService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TwoDMorningWinnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // for two digit early morning
    protected $prizeSentService;

    protected $adminEveningPrizeSentService;

    public function __construct(AdminMorningPrizeSentService $prizeSentService, AdminEveningPrizeSentService $adminEveningPrizeSentService)
    {
        $this->prizeSentService = $prizeSentService;
        $this->adminEveningPrizeSentService = $adminEveningPrizeSentService;
    }

    public function MorningWinHistoryForAdmin()
    {
        try {
            $data = $this->prizeSentService->MorningPrizeSentForAdmin();

            return view('admin.two_d.morning_winner.index', [
                'results' => $data['results'],
                'totalPrizeAmount' => $data['totalPrizeAmount'],
            ]);

        } catch (\Exception $e) {
            return view('admin.two_d.morning_winner.index', [
                'error' => 'Failed to retrieve data. Please try again later.',
            ]);
        }
    }

    public function EveningWinHistoryForAdmin()
    {
        try {
            $data = $this->adminEveningPrizeSentService->EveningPrizeForAdmin();

            return view('admin.two_d.evening_winner.index', [
                'results' => $data['results'],
                'totalPrizeAmount' => $data['totalPrizeAmount'],
            ]);

        } catch (\Exception $e) {
            return view('admin.two_d.evening_winner.index', [
                'error' => 'Failed to retrieve data. Please try again later.',
            ]);
        }
    }

    // public function TwoDMorningWinner()
    // {
    //     $lotteries = Lottery::with('twoDigitsMorning')->get();

    //     $prize_no_morning = TwodWiner::whereDate('created_at', Carbon::today())
    //                                 ->whereBetween('created_at', [Carbon::now()->startOfDay()->addHours(6), Carbon::now()->startOfDay()->addHours(12)])
    //                                 ->orderBy('id', 'desc')
    //                                 ->first();

    //                                 $prize_no = TwodWiner::whereDate('created_at', Carbon::today())->orderBy('id', 'desc')->first();
    //     return view('admin.two_d.morining_winner', compact('lotteries', 'prize_no_morning', 'prize_no'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
