<?php

namespace App\Http\Controllers\Admin\TwoD;

use App\Http\Controllers\Controller;
use App\Models\TwoD\TwoDLimit;
use App\Services\EveningLegarService;
use Illuminate\Http\Request;

class EveningLegarController extends Controller
{
    protected $eveningLegarService;

    public function __construct(EveningLegarService $eveningLegarService)
    {
        $this->eveningLegarService = $eveningLegarService;
    }

    public function showMorningLegar()
    {
        $defaultBreak = TwoDLimit::lasted()->first();
        $twoDigitData = $this->eveningLegarService->getTwoDigitData();

        return view('admin.two_d.evening_legar.index', compact('twoDigitData', 'defaultBreak'));
    }

    public function Eveningupdate(Request $request)
    {
        $request->validate([
            'two_d_limit' => 'required|numeric|min:0',
        ]);

        $defaultBreak = TwoDLimit::latest()->first();
        $defaultBreak->update(['two_d_limit' => $request->two_d_limit]);

        return redirect()->route('admin.eveningLegar.show')->with('success', 'Default Break updated successfully.');
    }
}
