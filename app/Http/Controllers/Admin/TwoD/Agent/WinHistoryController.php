<?php

namespace App\Http\Controllers\Admin\TwoD\Agent;

use App\Http\Controllers\Controller;
use App\Services\AgentAllWinPrizeSentService;
use App\Services\AgentEveningPrizeSentService;
use App\Services\AgentMorningPrizeSentService;
use Illuminate\Http\Request;

class WinHistoryController extends Controller
{
    protected $prizeSentService;

    protected $adminEveningPrizeSentService;

    protected $all_winner;

    public function __construct(AgentMorningPrizeSentService $prizeSentService, AgentEveningPrizeSentService $adminEveningPrizeSentService, AgentAllWinPrizeSentService $all_winner)
    {
        $this->prizeSentService = $prizeSentService;
        $this->adminEveningPrizeSentService = $adminEveningPrizeSentService;
        $this->all_winner = $all_winner;

    }

    public function MorningWinHistoryForAgent()
    {
        try {
            $data = $this->prizeSentService->MorningPrizeSentForAgent();

            return view('admin.two_d.agent.morning_winner.index', [
                'results' => $data['results'],
                'totalPrizeAmount' => $data['totalPrizeAmount'],
            ]);

        } catch (\Exception $e) {
            return view('admin.two_d.agent.morning_winner.index', [
                'error' => 'Failed to retrieve data. Please try again later.',
            ]);
        }
    }

    public function EveningWinHistoryForAgent()
    {
        try {
            $data = $this->adminEveningPrizeSentService->EveningPrizeSentForAgent();

            return view('admin.two_d.agent.evening_winner.index', [
                'results' => $data['results'],
                'totalPrizeAmount' => $data['totalPrizeAmount'],
            ]);

        } catch (\Exception $e) {
            return view('admin.two_d.agent.evening_winner.index', [
                'error' => 'Failed to retrieve data. Please try again later.',
            ]);
        }
    }

    public function TwoAllWinHistoryForAgent()
    {
        try {
            $data = $this->all_winner->AllWinPrizeSentForAgent();

            return view('admin.two_d.agent.all_winner.index', [
                'results' => $data['results'],
                'totalPrizeAmount' => $data['totalPrizeAmount'],
            ]);

        } catch (\Exception $e) {
            return view('admin.two_d.agent.all_winner.index', [
                'error' => 'Failed to retrieve data. Please try again later.',
            ]);
        }
    }
}
