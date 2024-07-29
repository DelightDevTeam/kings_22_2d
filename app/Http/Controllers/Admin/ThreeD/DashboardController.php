<?php

namespace App\Http\Controllers\Admin\ThreeD;

use App\Models\ThreeD\Lotto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user

        if ($user->hasRole('Admin')) {
            // Retrieve total amounts for different time frames
            $today = now()->today();
            $startOfWeek = now()->startOfWeek();
            $endOfWeek = now()->endOfWeek();

            $dailyTotal = Lotto::whereDate('created_at', $today)->sum('total_amount');
            $weeklyTotal = Lotto::whereBetween('created_at', [$startOfWeek, $endOfWeek])->sum('total_amount');
            $monthlyTotal = Lotto::whereMonth('created_at', '=', now()->month)
                ->whereYear('created_at', '=', now()->year)
                ->sum('total_amount');
            $yearlyTotal = Lotto::whereYear('created_at', '=', now()->year)->sum('total_amount');

            // Return data to admin dashboard
            return view('admin.three_d.dashboard', [
                'dailyTotal' => $dailyTotal,
                'weeklyTotal' => $weeklyTotal,
                'monthlyTotal' => $monthlyTotal,
                'yearlyTotal' => $yearlyTotal,
            ]);
        }
    }
}
