<?php

namespace App\Http\Controllers\Admin\TwoD;

use App\Http\Controllers\Controller;
use App\Models\TwoD\TwoDLimit;
use App\Services\MorningLegarService;
use Illuminate\Http\Request;

class MorningLegarController extends Controller
{
    protected $morningLegarService;

    public function __construct(MorningLegarService $morningLegarService)
    {
        $this->morningLegarService = $morningLegarService;
    }

    public function showMorningLegar()
    {
        $defaultBreak = TwoDLimit::lasted()->first();
        $twoDigitData = $this->morningLegarService->getTwoDigitData();

        return view('admin.two_d.morning_legar.index', compact('twoDigitData', 'defaultBreak'));
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

    // updated
}
