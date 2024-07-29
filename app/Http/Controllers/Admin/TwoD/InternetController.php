<?php

namespace App\Http\Controllers\Admin\TwoD;

use App\Http\Controllers\Controller;
use App\Models\TwoD\Internet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InternetController extends Controller
{
    public function index()
    {

        $morningData = Internet::where('session', 'morning')->orderBy('id', 'desc')->first();
        $eveningData = Internet::where('session', 'evening')->orderBy('id', 'desc')->first();

        return view('admin.two_d.internet.prize_index', compact('morningData', 'eveningData'));
    }

    public function store(Request $request)
    {
        // Get the current time in the 'Asia/Yangon' time zone
        $currentTime = Carbon::now('Asia/Yangon')->format('H:i') < '10:00' ? '9:30' : '2:00';

        // Determine the current session based on the current time
        $currentSession = Carbon::now('Asia/Yangon')->format('H:i') < '10:00' ? 'morning' : 'evening';
        $date_format = Carbon::now('Asia/Yangon')->format('Y-m-d-h-i');

        // Create a new TwodWiner entry
        Internet::create([
            'internet_digit' => $request->internet_digit,
            'session' => $currentSession,
            'open_time' => $currentTime,
            'created_at' => $date_format,
            'updated_at' => $date_format,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'ထွက်ဂဏန်းထဲ့သွင်းမှု့အောင်မြင်ပါသည်။');
    }

    public function destroy(string $id)
    {
        $digit = Internet::find($id);
        $digit->delete();
        session()->flash('SuccessRequest', 'Modern Digit Prize Number Deleted Successfully');

        return redirect()->back()->with('success', 'Three Digit Lottery Prize Number Deleted Successfully');
    }
}
