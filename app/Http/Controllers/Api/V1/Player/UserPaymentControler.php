<?php

namespace App\Http\Controllers\Api\V1\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentTypeRequest;
use App\Models\Admin\UserPayment;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserPaymentControler extends Controller
{
    use HttpResponses;

    public function index()
    {
        $data = UserPayment::with('paymentType')->where('user_id', Auth::id())->latest()->first();

        return $this->success($data, 'User Payment List');
    }

    public function create(PaymentTypeRequest $request)
    {
        $inputs = $request->validated();
        $params = array_merge($inputs, ['user_id' => Auth::id()]);
        $data = UserPayment::where('user_id', Auth::id())->where('payment_type_id', $request->payment_type_id)->first();

        if ($data) {
            return $this->error('', 'Already Exist Account', 401);
        }
        if (Hash::check($request->password, Auth::user()->password)) {
            $data = UserPayment::create($params);

            return $this->success($data, 'User Payment Create');
        } else {
            return $this->error('', 'လျို့ဝှက်နံပါတ် ကိုက်ညီမှု မရှိပါ။ ထပ်မံကြိုးစားပါ။', 401);
        }
    }

    public function agentPayment()
    {
        $player = Auth::user();

        $data = UserPayment::with('paymentType', 'paymentType.paymentImages')->where('user_id', $player->agent_id)->get();

        return $this->success($data, 'User Payment List');

    }
}
