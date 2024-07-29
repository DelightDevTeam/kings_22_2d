<?php

namespace App\Http\Controllers\Admin\TwoD;

use App\Http\Controllers\Controller;
use App\Models\TwoD\Lottery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoDDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user

        if ($user->hasRole('Admin')) {
            // Retrieve total amounts for different time frames
            $today = now()->today();
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $dailyTotal = Lottery::whereDate('created_at', $today)->sum('total_amount');
            $weeklyTotal = Lottery::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_amount');
            $monthlyTotal = Lottery::whereMonth('created_at', '=', now()->month)
                ->whereYear('created_at', '=', now()->year)
                ->sum('total_amount');
            $yearlyTotal = Lottery::whereYear('created_at', '=', now()->year)->sum('total_amount');

            // Return data to admin dashboard
            return view('admin.two_d.dashboard', [
                'dailyTotal' => $dailyTotal,
                'weeklyTotal' => $weeklyTotal,
                'monthlyTotal' => $monthlyTotal,
                'yearlyTotal' => $yearlyTotal,
            ]);
        }
    }
}
