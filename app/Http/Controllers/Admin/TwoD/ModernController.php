<?php

namespace App\Http\Controllers\Admin\TwoD;

use App\Http\Controllers\Controller;
use App\Models\TwoD\Modern;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ModernController extends Controller
{
    public function index()
    {

        $morningData = Modern::where('session', 'morning')->orderBy('id', 'desc')->first();
        $eveningData = Modern::where('session', 'evening')->orderBy('id', 'desc')->first();

        return view('admin.two_d.modern.prize_index', compact('morningData', 'eveningData'));
    }

    public function store(Request $request)
    {
        // Get the current time in the 'Asia/Yangon' time zone
        $currentTime = Carbon::now('Asia/Yangon')->format('H:i') < '10:00' ? '9:30' : '2:00';

        // Determine the current session based on the current time
        $currentSession = Carbon::now('Asia/Yangon')->format('H:i') < '12:30' ? 'morning' : 'evening';
        $date_format = Carbon::now('Asia/Yangon')->format('Y-m-d-h-i');

        // Create a new TwodWiner entry
        Modern::create([
            'modern_digit' => $request->modern_digit,
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
        $digit = Modern::find($id);
        $digit->delete();
        session()->flash('SuccessRequest', 'Modern Digit Prize Number Deleted Successfully');

        return redirect()->back()->with('success', 'Three Digit Lottery Prize Number Deleted Successfully');
    }
}
