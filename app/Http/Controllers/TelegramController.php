<?php

namespace App\Http\Controllers;

use App\Models\City;
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

        //callback query
        $callback_query = $tData['callback_query'] ?? null;
        $callback_chat_id = $callback_query['message']['chat']['id'] ?? null;
        $callback_data = $callback_query['data']??null;

        \Log::info('\n--------REPLYMESSAGE-----', [$reply_to_message]);
        echo '\n';
        echo '\n';
        \Log::info('\n--------call back query-----', ['callback_query' => $callback_query, 'callback_chat_id ' => $callback_chat_id, 'callback_data' => $callback_data]);
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
            $city = City::query()->where('name', $text)->get();
            if ($city->count() == 0) {
                //city not found
                $replyData = ['text' => 'City Not Found :o'];
            } else {
                \Log::inf('*** city found ***', []);
                if ($callback_query) {
                    \Log::inf('*** inside call back query ***', []);

                    $weatherToken = env('weather_token');
                    if ($callback_data == 'today') {
                        $url = "https://api.openweathermap.org/data/2.5/weather?q=$text&appid=$weatherToken&units=metric&lang=fa";
                        $result = Http::post($url);
                        \Log::info('========= RESULT =======', [$result]);
                    }
                }

                \Log::info('\n---- city name ----- ', [$text]);

                $inlineKeyboard = [
                    [
                        ['text' => 'امروز', 'callback_data' => 'today'],
                        ['text' => 'چهار روز آینده', 'callback_data' => '4day'],
                        ['text' => 'شانزده روز آینده', 'callback_data' => '16day'],
                    ]
                ];
                $replyData = [
                    'text'         => 'بازی زمانی مورد نظر را انتخاب نمایید🌓',
                    'reply_markup' => json_encode(['inline_keyboard' => $inlineKeyboard])
                ];
            }
        } else {
            $replyData = ['text' => 'undefined command'];
        }

        $response = Http:: post("https://api.telegram.org/bot{$botToken}/sendmessage",
            array_merge(['chat_id' => $id], $replyData)
        );

        \Log::info('---- response ---- ', [$response->json()]);

    }
}
