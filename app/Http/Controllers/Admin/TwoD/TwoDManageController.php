<?php

namespace App\Http\Controllers\Admin\TwoD;

use App\Http\Controllers\Controller;
use App\Models\TwoD\LotteryTwoDigitCopy;
use Illuminate\Http\Request;

class TwoDManageController extends Controller
{
    public function SessionReset()
    {
        LotteryTwoDigitCopy::truncate();
        session()->flash('SuccessRequest', 'Successfully 2D Session Reset.');

        return redirect()->back()->with('message', 'Data reset successfully!');
    }
}
