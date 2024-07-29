<?php

namespace App\Http\Controllers\Admin\ThreeD;

use App\Http\Controllers\Controller;
use App\Services\FirstWinnerService;
use App\Services\SecondWinnerService;
use App\Services\ThirdWinnerService;
use Illuminate\Http\Request;

class WinnerController extends Controller
{
    protected $lottoService;

    protected $secondWinnerService;

    protected $thirdWinnerService;

    public function __construct(FirstWinnerService $lottoService, SecondWinnerService $secondWinnerService, ThirdWinnerService $thirdWinnerService)
    {
        $this->lottoService = $lottoService;
        $this->secondWinnerService = $secondWinnerService;
        $this->thirdWinnerService = $thirdWinnerService;
    }

    public function ThreeDFirstWinner()
    {
        $data = $this->lottoService->FirstWinnter();

        return view('admin.three_d.winners.first_prize', compact('data'));
    }

    public function ThreeDSecondWinner()
    {
        $data = $this->secondWinnerService->SecondWinnter();

        return view('admin.three_d.winners.second_prize', compact('data'));
    }

    public function ThreeDThirdWinner()
    {
        $data = $this->thirdWinnerService->ThirdWinnter();

        return view('admin.three_d.winners.third_prize', compact('data'));
    }
}
