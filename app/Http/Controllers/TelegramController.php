<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function webhook(Request $r)
    {
        $re = $r->all();
        $id = $re['message']['chat']['id'];

        $botToken = env("TELEGRAM_API");
        $response = Http:: post("https://api.telegram.org/bot{$botToken}/sendmessage",
            [
                'chat_id' => $id,
                'text'    => 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.britannica.com%2Fscience%2Fflower&psig=AOvVaw2TnltX_R2w5o5PUDdq9rP9&ust=1713625184779000&source=images&cd=vfe&opi=89978449&ved=0CBIQjRxqFwoTCPCE-rTFzoUDFQAAAAAdAAAAABAE'
            ]);

        \Log::info('---- response ---- ', [$response->json()]);

    }
}
