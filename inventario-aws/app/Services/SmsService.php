<?php

namespace App\Services;

use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

class SmsService
{
    protected $sns;

    public function __construct()
    {
        $this->sns = new SnsClient([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    public function sendSms($phoneNumber, $message)
    {
        try {
            $result = $this->sns->publish([
                'Message' => $message,
                'PhoneNumber' => $phoneNumber,
            ]);
            return $result;
        } catch (AwsException $e) {
            return $e->getMessage();
        }
    }
}
