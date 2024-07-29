<?php

namespace App\Http\Controllers\Admin\ThreeD\Agent;

use App\Http\Controllers\Controller;
use App\Services\AgentAllThreeDWinnerService;
use App\Services\AgentFirstWinnerService;
use App\Services\AgentSecondWinnerService;
use App\Services\AgentThirdWinnerService;
use Illuminate\Http\Request;

class WinnerHistoryController extends Controller
{
    protected $lottoService;

    protected $secondWinnerService;

    protected $thirdWinnerService;

    protected $all_winnerService;

    public function __construct(AgentFirstWinnerService $lottoService, AgentSecondWinnerService $secondWinnerService, AgentThirdWinnerService $thirdWinnerService, AgentAllThreeDWinnerService $all_winnerService)
    {
        $this->lottoService = $lottoService;
        $this->secondWinnerService = $secondWinnerService;
        $this->thirdWinnerService = $thirdWinnerService;
        $this->all_winnerService = $all_winnerService;

    }

    public function ThreeDFirstWinner()
    {
        $data = $this->lottoService->AgentFirstWinner();

        return view('admin.three_d.agent.winners.first_prize', compact('data'));
    }

    public function ThreeDSecondWinner()
    {
        $data = $this->secondWinnerService->AgentSecondWinner();

        return view('admin.three_d.agent.winners.second_prize', compact('data'));
    }

    public function ThreeDThirdWinner()
    {
        $data = $this->thirdWinnerService->AgentThirdWinner();

        return view('admin.three_d.agent.winners.third_prize', compact('data'));
    }

    public function AllWinner()
    {
        $data = $this->all_winnerService->AgentAllWinner();

        return view('admin.three_d.agent.winners.all_first_prize', compact('data'));
    }
}
