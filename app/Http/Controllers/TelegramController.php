<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $token = env('TELEGRAM_API');
        $chat_id = env('USER_ID');
        $result = Http::get('https://api.telegram.org/bot' . $token . '/sendmessage?chat_id=' . $chat_id . '&text=hiii back ');
        return $result;
    }
}
