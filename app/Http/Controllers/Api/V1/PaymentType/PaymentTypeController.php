<?php

namespace App\Http\Controllers\Api\V1\PaymentType;

use App\Http\Controllers\Controller;
use App\Models\Admin\UserPayment;
use App\Models\PaymentType;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class PaymentTypeController extends Controller
{
    use HttpResponses;

    public function all()
    {
        $data = PaymentType::all();

        return $this->success($data);
    }

    public function agentPayment()
    {

    }
}
