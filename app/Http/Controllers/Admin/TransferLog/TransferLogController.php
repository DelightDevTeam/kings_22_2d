<?php

namespace App\Http\Controllers\Admin\TransferLog;

use App\Http\Controllers\Controller;
use App\Models\Admin\TransferLog;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferLogController extends Controller
{
    public function index()
    {
        $this->authorize('transfer_log', User::class);

        $transferLogs = TransferLog::with('fromUser', 'toUser')->where('from_user_id', Auth::id())->orWhere('to_user_id', Auth::id())->latest()->paginate();

        return view('admin.trans_log.index', compact('transferLogs'));
    }
}
