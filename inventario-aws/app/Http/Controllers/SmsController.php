<?php

namespace App\Http\Controllers;

use App\Services\SmsService;

class SmsController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function sendTestSms()
    {
        $phoneNumber = '+34662040002';
        $message = 'This is a test message!';
        $result = $this->smsService->sendSms($phoneNumber, $message);
        return response()->json($result);
    }
}
