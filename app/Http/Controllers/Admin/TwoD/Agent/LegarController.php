<?php

namespace App\Http\Controllers\Admin\TwoD\Agent;

use App\Http\Controllers\Controller;
use App\Models\TwoD\TwoDLimit;
use App\Services\AgentEveningLegarService;
use App\Services\AgentMorningHistoryService;
use App\Services\AgentMorningLegarService;
use Illuminate\Http\Request;

class LegarController extends Controller
{
    protected $morningLegarService;

    protected $eveningLegarService;

    public function __construct(AgentMorningLegarService $morningLegarService, AgentEveningLegarService $eveningLegarService)
    {
        $this->morningLegarService = $morningLegarService;
        $this->eveningLegarService = $eveningLegarService;

    }

    public function showMorningLegar()
    {
        $defaultBreak = TwoDLimit::lasted()->first();
        $twoDigitData = $this->morningLegarService->MorningTwoDigitData();

        return view('admin.two_d.agent.morning_legar.index', compact('twoDigitData', 'defaultBreak'));
    }

    public function showEveningLegar()
    {
        $defaultBreak = TwoDLimit::lasted()->first();
        $twoDigitData = $this->eveningLegarService->getTwoDigitData();

        return view('admin.two_d.agent.evening_legar.index', compact('twoDigitData', 'defaultBreak'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'two_d_limit' => 'required|numeric|min:0',
        ]);

        $defaultBreak = TwoDLimit::latest()->first();
        $defaultBreak->update(['two_d_limit' => $request->two_d_limit]);

        return redirect()->route('admin.morningLegar.show')->with('success', 'Default Break updated successfully.');
    }
}
