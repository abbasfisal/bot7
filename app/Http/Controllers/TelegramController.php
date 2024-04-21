<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function __construct(public ?string $token, public ?string $url)
    {
        $this->token = env('TELEGRAM_API');
        $this->url = "https://api.telegram.org/bot$this->token/";
    }

    public function webhook(Request $request)
    {
        $tData = $request->json();
        Log::info('-- telegram data --- ', [$tData]);
    }

    public function sendMessage($chatId, $text)
    {

    }
}
