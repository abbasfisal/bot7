<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $tData = $request->all();
        $id = $tData['message']['chat']['id'];
        $text = $tData  ['message']['text'];
        $firstName = $tData['message']['from']['first_name'];
        $botToken = env("TELEGRAM_API");

        switch ($text) {
            case 'hi':
                $replyData = ['text' => ' Hi How Are U :)'];
                break;
            default :
                $replyData = ['text' => "Hi $firstName , Welcome To Instagram Robot ;0)"];

        }
        $response = Http:: post("https://api.telegram.org/bot{$botToken}/sendmessage",
            array_merge(['chat_id' => $id], $replyData)
        );

        \Log::info('---- response ---- ', [$response->json()]);

    }
}
