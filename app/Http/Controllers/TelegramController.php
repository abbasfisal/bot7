<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $tData = $request->all();
        \Log::info('----- t Data -----', [$tData]);
        $id = $tData['message']['chat']['id'];
        $text = $tData  ['message']['text'];
        $firstName = $tData['message']['from']['first_name'];
        $botToken = env("TELEGRAM_API");

        $keyboard = json_encode([
            'keyboard'        => [
                ["پیش بینی وضع آب و هوا🌤"],
                ["aboutUs", "ContactUs"]
            ],
            'resize_keyboard' => true
        ]);
        switch ($text) {
            case '/start' :
                $replyData = [
                    'text'         => 'سلام خوش آمدید',
                    'reply_markup' => $keyboard
                ];

                break;
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
