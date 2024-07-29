<?php

namespace App\Http\Controllers\Admin\ThreeD;

use App\Http\Controllers\Controller;
use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Models\ThreeD\Permutation;
use App\Models\ThreeD\Prize;
use App\Models\ThreeD\ThreedSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    public function index()
    {
        // Get the current year and month
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Get result dates for the current month
        $currentMonthResultDates = ThreedSetting::whereYear('result_date', $currentYear)
            ->whereMonth('result_date', $currentMonth)
            ->get();

        // Determine the next month and year
        $nextMonth = ($currentMonth % 12) + 1; // Calculate the next month (1-12, looping back to 1 after 12)
        $nextMonthYear = ($nextMonth == 1) ? $currentYear + 1 : $currentYear; // Increment year if it's January

        // Get the first result date of the next month
        $firstResultDateNextMonth = ThreedSetting::whereYear('result_date', $nextMonthYear)
            ->whereMonth('result_date', $nextMonth)
            ->orderBy('result_date', 'asc')
            ->first();

        // Merge the current month results with the first result date of the next month
        $results = $currentMonthResultDates->merge(collect([$firstResultDateNextMonth]));

        // Log the result dates for debugging
        //Log::info('Result dates including the current month and the first game of the next month:', ['resultDates' => $results]);

        //Get the latest prize where status is open
        $lasted_prizes = ThreedSetting::where('status', 'open')
            ->orderBy('result_date', 'desc') // Ensure to get the latest result date
            ->first();
        // Retrieve permutation digits and the latest prize
        $permutation_digits = Permutation::all();
        $three_digits_prize = Prize::orderBy('id', 'desc')->first();

        // Return the view with the required data
        return view('admin.three_d.setting.index', compact('results', 'lasted_prizes', 'permutation_digits', 'three_digits_prize'));
    }

    public function getCurrentMonthResultsSetting()
    {
        $currentMonthStart = Carbon::now()->startOfMonth();

        // Get the end of the next month
        $nextMonthEnd = Carbon::now()->addMonth()->endOfMonth();

        // Retrieve all records within the current month and next month
        $results = ThreedSetting::whereBetween('result_date', [$currentMonthStart, $nextMonthEnd])
            ->orderBy('result_date', 'asc') // Optional: order by date
            ->get();

        // Return the data to the view or as a JSON response
        return view('admin.three_d.setting.more_setting', ['results' => $results]);
    }

    public function updateStatus(Request $request, $id)
    {
        // Get the new status with a fallback default
        $newStatus = $request->input('status', 'closed'); // Default to 'closed' if not provided

        // Find the existing record and update the status
        $result = ThreedSetting::findOrFail($id);

        // Ensure the status is not NULL before updating
        if (is_null($newStatus)) {
            return redirect()->back()->with('error', 'Status cannot be null');
        }

        $result->status = $newStatus;
        $result->save();

        return redirect()->back()->with('success', "Status changed to '{$newStatus}' successfully.");
    }

    public function updatePrizeStatus(Request $request, $id)
    {
        // Get the new status with a fallback default
        $newStatus = $request->input('prize_status', 'closed'); // Default to 'closed' if not provided

        // Find the existing record and update the status
        $result = ThreedSetting::findOrFail($id);

        // Ensure the status is not NULL before updating
        if (is_null($newStatus)) {
            return redirect()->back()->with('error', 'prize_status cannot be null');
        }

        $result->prize_status = $newStatus;
        $result->save();

        return redirect()->back()->with('success', "prize_status changed to '{$newStatus}' successfully.");
    }

    public function updateResultNumber(Request $request, $id)
    {
        $result_number = $request->input('result_number'); // The new status

        // Find the result by ID
        $result = ThreedSetting::findOrFail($id);

        // Update the status
        $result->result_number = $result_number;
        $result->save();

        $draw_date = ThreedSetting::where('status', 'open')->first();
        $start_date = $draw_date->match_start_date;
        $end_date = $draw_date->result_date;
        $today = Carbon::today();

        $three_digits = LotteryThreeDigitPivot::whereBetween('match_start_date', [$start_date, $end_date])
            ->whereBetween('res_date', [$start_date, $end_date])
            ->get();
        foreach ($three_digits as $digit) {
            $digit->update(['win_lose' => 1]);
        }

        // Return a response (like a JSON object)
        return redirect()->back()->with('success', 'Result number updated successfully.'); // Redirect back with success message
    }

    // public function UpdateCloseSessionTime(Request $request, $id)
    // {
    //     $closed_time = $request->input('closed_time'); // The new closed time

    //     // Find the result by ID
    //     $result = ThreedSetting::findOrFail($id);

    //     // Update the closed time
    //     $result->closed_time = $closed_time;
    //     $result->save();

    //     // Fetch today's date and current session
    //     $today = Carbon::today('Asia/Yangon');

    //     // Update closed time for all twod_data in the current session
    //     $twod_data = ThreedSetting::where('result_date', $today)->get();
    //     foreach ($twod_data as $twod) {
    //         $twod->closed_time = $closed_time;
    //         $twod->save();
    //     }

    //     // Return a response (like a JSON object)
    //     return redirect()->back()->with('success', 'Closed time updated successfully.');
    // }

    public function UpdateCloseSessionTime(Request $request, $id)
    {
        $closed_time = $request->input('closed_time');

        $this->updateThreedSetting($id, $closed_time);
        $this->updateTwodDataClosedTime($closed_time);

        return redirect()->back()->with('success', 'Closed time updated successfully.');
    }

    private function updateThreedSetting($id, $closed_time)
    {
        $result = ThreedSetting::findOrFail($id);
        $result->closed_time = $closed_time;
        $result->save();
    }

    private function updateTwodDataClosedTime($closed_time)
    {
        $data = ThreedSetting::whereIn('status', ['open', 'closed'])->get();

        foreach ($data as $obj) {
            $obj->closed_time = $closed_time;
            $obj->save();
        }
    }

    public function PermutationStore(Request $request)
    {
        // Logic to store permutations in the database
        if ($request->has('permutations')) {
            foreach ($request->permutations as $permutation) {
                Permutation::create(['digit' => $permutation]);
            }

            return redirect()->back()->with('success', 'Permutations stored successfully.');
        } else {
            return redirect()->back()->with('error', 'No permutations to store.');
        }
    }

    // deletePermutation
    public function deletePermutation($id)
    {
        $permutation = Permutation::find($id);
        if ($permutation) {
            $permutation->delete();

            return redirect()->back()->with('success', 'Permutation deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Permutation not found.');
        }
    }

    public function PermutationReset()
    {
        Permutation::truncate();
        session()->flash('SuccessRequest', 'Successfully 3D Permutation Reset.');

        return redirect()->back()->with('message', 'Data reset successfully!');
    }

    public function store(Request $request)
    {
        //
        //$currentSession = date('H') < 12 ? 'morning' : 'evening';  // before 1 pm is morning

        Prize::create([
            'prize_one' => $request->prize_one,
            'prize_two' => $request->prize_two,
            //'session' => $currentSession,
        ]);
        session()->flash('SuccessRequest', 'Three Digit Lottery Prize Number Created Successfully');

        return redirect()->back()->with('success', 'Three Digit Lottery Prize Number Created Successfully');
    }

    public function destroy(string $id)
    {
        $digit = Prize::find($id);
        $digit->delete();
        session()->flash('SuccessRequest', 'Three Digit Lottery Prize Number Deleted Successfully');

        return redirect()->back()->with('success', 'Three Digit Lottery Prize Number Deleted Successfully');
    }
}
