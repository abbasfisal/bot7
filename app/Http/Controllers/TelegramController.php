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

        $message = $tData['message']['text'] ?? $tData['callback_query']['message']['text'];
        $messageId = $tData['message']['message_id'] ?? $tData['callback_query']['message']['message_id'];
        $chatId = $tData['message']['chat'] ['id'] ?? $tData['callback_query']['message']['chat']['id'];

        $this->editMessage($chatId, $messageId, 'updated', '');

        //-- keyboard
        $keyboard = $this->keyboard('button one');
        //----
        //--- inline keyboard
        $inlineButton = [
            $this->inlineButton('option 1', 'callback'),
            $this->inlineButton('option 2', 'callback'),
        ];
        $inlineKeyboard = $this->inlineKeyboard($inlineButton);
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
        Log::info(" --- response $methodName ----", [$response->json()]);
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


    public function inlineButton($buttonText, $callBackData): array
    {
        return [
            'text'          => $buttonText,
            'callback_data' => $callBackData
        ];
    }

    public function inlineKeyboard(array $inlineButton)
    {
        return json_encode([
            'inline_keyboard' => [$inlineButton]
        ]);
    }

    public function editMessage($chatId, $messageId, $text, $reply_markup)
    {
        $this->callBot('editMessageText', [
            'chat_id'    => $chatId,
            'message_id' => $messageId,
            'text'       => $text
        ]);
    }

}
