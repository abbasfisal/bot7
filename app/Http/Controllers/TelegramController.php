<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    const WEATHER = "پیش بینی وضع آب و هوا🌤";
    const ABOUTUS = 'About Us';
    const CONTACTUS = 'Contact Us';

    public function webhook(Request $request)
    {
        $tData = $request->all();
        \Log::info('----- t Data -----', [$tData]);
        $id = $tData['message']['chat']['id'];
        $text = $tData  ['message']['text'];
        $reply_to_message = $tData['message']['reply_to_message'];
        $firstName = $tData['message']['from']['first_name'];
        $botToken = env("TELEGRAM_API");

        $keyboard = json_encode([
            'keyboard'        => [
                [self::WEATHER],
                [self::ABOUTUS, self::CONTACTUS]
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

            case self::WEATHER:
                $replyData = ['text' => 'لطفا نام شهر مورد نظر را وارد نمایید...'];
                break;
            case $reply_to_message:
                $replyData = ['text' => 'weather is go yoho'];
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
