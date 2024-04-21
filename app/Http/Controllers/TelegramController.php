<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function __construct(public string $token = '', public string $url = '')
    {
        $this->token = env('TELEGRAM_API');
        $this->url = "https://api.telegram.org/bot$this->token/";
    }

    public function webhook(Request $request)
    {
        $tData = $request->all();
        Log::info('---- telegram incoming data ----', [$tData]);

        $message = $tData['message']['text'] ?? '-';
        $chatId = $tData['message']['chat'] ['id'];


        //-- keyboard
        $keyboard = $this->keyboard('button one');
        //----
        //--- inline keyboard
        $inlineKeyboard = $this->inlineKeyboard();
        //----
        $this->sendMessage($chatId, 'welcome to your bot ;)', $inlineKeyboard);

    }

    public function sendMessage($chatId, $text, $keyboard = '')
    {
        $this->callBot('sendMessage', [
            'chat_id'      => $chatId,
            'text'         => $text,
            'reply_markup' => $keyboard
        ]);
    }

    public function callBot(string $methodName, array $data)
    {
        $response = Http::post($this->url . $methodName, $data);
        Log::info('--- response ----', [$response->json()]);
    }


    public function keyboard(string $text): string
    {
        $btn = [
            [
                $text
            ]
        ];

        $key = [
            'keyboard'          => $btn,
            'resize_keyboard'   => true,
            'one_time_keyboard' => false,
            'selective'         => true,
        ];

        return json_encode($key, true);
    }


    public function inlineKeyboard()
    {
        return json_encode([
            'inline_keyboard' => [
                ['option A', 'callbackA']
            ]
        ]);
    }

}
