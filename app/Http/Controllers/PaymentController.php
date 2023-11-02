<?php

namespace App\Http\Controllers;

use App\Enum\GatewayEnum;
use App\Http\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment', [
            'gateways' => GatewayEnum::getDisplay(),
        ]);
    }

    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gateway' => ['required', Rule::in(GatewayEnum::getAll())],
            'fullName' => 'required',
            'cardNumber' => 'required',
            'month' => 'required',
            'year' => 'required',
            'cvv' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('danger', $validator->errors()->first());

            return response()->redirectTo('/');
        }

        $gatewayService = GatewayEnum::mapEnumToGateway($request->get('gateway'));
        [$result, $message] = $gatewayService->charges($validator->validated());
        $request->session()->flash($result, $message);

        return response()->redirectTo('/');
    }
}
