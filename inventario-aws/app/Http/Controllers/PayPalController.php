<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayPalService;
use App\Models\Order;

class PayPalController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function success(Request $request)
    {
        $orderId = $request->input('token');
        $response = $this->paypalService->captureOrder($orderId);

        if ($response && $response->result->status == 'COMPLETED') {
            $order = Order::find($orderId);
            $order->status = 'completed';
            $order->save();

            $order->sendStatusChangeEmail();

            return redirect()->route('orders.index')->with('success', 'Pago realizado con éxito.');
        }

        return redirect()->route('orders.index')->with('error', 'El pago no se pudo completar.');
    }

    public function cancel()
    {
        return redirect()->route('orders.index')->with('error', 'El pago fue cancelado.');
    }
}
