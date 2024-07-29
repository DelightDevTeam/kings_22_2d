<?php

namespace App\Http\Controllers\Admin\TwoD\Agent;

use App\Http\Controllers\Controller;
use App\Services\AgentEveningHistoryService;
use App\Services\AgentMorningHistoryService;
use Illuminate\Http\Request;

class TwoDHistoryController extends Controller
{
    protected $adminMorningHistoryService;

    protected $eveningHistory;

    public function __construct(AgentMorningHistoryService $adminMorningHistoryService, AgentEveningHistoryService $eveningHistory)
    {
        $this->adminMorningHistoryService = $adminMorningHistoryService;
        $this->eveningHistory = $eveningHistory;
    }

    /**
     * Display the morning history in a view.
     *
     * @return \Illuminate\View\View
     */
    public function showMorningHistory()
    {
        $history = $this->adminMorningHistoryService->getMorningHistory();
        $data = $history['data'];
        $totalSubAmount = $history['total_sub_amount'];

        return view('admin.two_d.agent.history.morning_history', compact('data', 'totalSubAmount'));
    }

    public function showEveningHistory()
    {
        $history = $this->eveningHistory->getEveningHistory();
        $data = $history['data'];
        $totalSubAmount = $history['total_sub_amount'];

        return view('admin.two_d.agent.history.evening_history', compact('data', 'totalSubAmount'));
    }
}
