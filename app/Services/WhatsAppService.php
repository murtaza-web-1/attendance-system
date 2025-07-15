<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendMessage($to, $message)
    {
        try {
            $this->twilio->messages->create("whatsapp:$to", [
                'from' => config('services.twilio.from'),
                'body' => $message,
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp Send Failed: ' . $e->getMessage());
        }
    }
}
