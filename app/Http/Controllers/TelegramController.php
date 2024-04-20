<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    const WEATHER = "پیش بینی وضع آب و هوا🌤";
    const ABOUTUS = 'About Us';
    const CONTACTUS = 'Contact Us';
    const CITYNAME = 'لطفا نام شهر مورد نظر را وارد نمایید...';

    public function webhook(Request $request)
    {
        $tData = $request->all();
        \Log::info('----- Telegram Data  -----', [$tData]);
        $id = $tData['message']['chat']['id'] ?? null;
        $text = $tData  ['message']['text'] ?? null;
        $reply_to_message = $tData['message']['reply_to_message'] ?? null;
        $reply_text = $reply_to_message['text'] ?? null;

        \Log::info('--------REPLYMESSAGE-----', [$reply_to_message]);
        $firstName = $tData['message']['from']['first_name'] ?? null;
        $botToken = env("TELEGRAM_API");

        $keyboard = json_encode([
            'keyboard'        => [
                [self::WEATHER],
                [self::ABOUTUS, self::CONTACTUS]
            ],
            'resize_keyboard' => true
        ]);

        ///  $replyData = [];
        if ($text == self::CONTACTUS) {
            $replyData = ['text' => 'My Email 📧 : aa@bb.cc'];

        } else if ($text == self::ABOUTUS) {
            $replyData = ['text' => 'First Instagram Bot ✅🤳'];

        } else if ($text == '/start') {
            $replyData = [
                'text'         => 'سلام خوش آمدید',
                'reply_markup' => $keyboard
            ];
        } else if ($text == self::WEATHER) {
            $replyData = ['text' => self::CITYNAME];
        } else if ($reply_text == self::CITYNAME) {

            //check city exist in db
            \Log::info('\n---- city name ----- ', [$text]);

            $replyData = ['text' => 'weather is rainy 🌧'];
        } else {
            $replyData = ['text' => 'undefined command'];
        }

        $response = Http:: post("https://api.telegram.org/bot{$botToken}/sendmessage",
            array_merge(['chat_id' => $id], $replyData)
        );

        \Log::info('---- response ---- ', [$response->json()]);

    }
}
