<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentTypeRequest;
use App\Http\Requests\UserPaymentRequest;
use App\Models\Admin\Bank;
use App\Models\Admin\UserPayment;
use App\Models\PaymentType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentTypes = UserPayment::with('paymentType')->where('user_id', Auth::id())->get();

        return view('admin.userPayment.index', compact('paymentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paymentType = PaymentType::all();

        return view('admin.userPayment.create', compact('paymentType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserPaymentRequest $request)
    {
        $param = array_merge($request->validated(), ['user_id' => Auth::id()]);

        UserPayment::create($param);

        return redirect(route('admin.userPayment.index'))->with('success', 'New userPayment Added.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $userPayment = UserPayment::where('id', $id)->where('user_id', Auth::id())->first();

        $paymentType = PaymentType::all();

        return view('admin.userPayment.edit', compact('userPayment', 'paymentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserPaymentRequest $request, $id)
    {

        $param = array_merge($request->validated());

        $userPayment = UserPayment::where('id', $id)->where('user_id', Auth::id())->first();

        $userPayment->update($param);

        return redirect(route('admin.userPayment.index'))->with('success', 'Bank Image Updated.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $userPayment = UserPayment::where('id', $id)->where('user_id', Auth::id())->first();
        $userPayment->delete();

        return redirect()->back()->with('success', 'Payment Type Deleted.');
    }
}
