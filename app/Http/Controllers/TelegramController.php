<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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
        Log::info("\n \t\t---- telegram incoming data ---- \n", [$tData]);

        if (isset($tData['callback_query'])) {
            $data = $tData['callback_query']['data'];
            $messageId = $tData['callback_query']['message']['message_id'];
            $chatId = $tData['callback_query']['message']['chat']['id'];
        } else {
            $message = $tData['message']['text'] ?? ' ';
            $messageId = $tData['message']['message_id'];
            $chatId = $tData['message']['chat'] ['id'];
        }

        //check member
        $res =$this->getChatMember('@instagrampro2024' , $chatId);
        Log::info('\n\t\t ---- channel member check -----\n' , [$res]);
        //-- keyboard
        $keyboard = $this->keyboard('button one');
        //----
        $this->sendMessage($chatId, 'button1', $keyboard);

        //--- inline keyboard
        $inlineButton = [
            $this->inlineButton('update me', 'update'),
            $this->inlineButton('delete me', 'delete'),
        ];
        $inlineKeyboard = $this->inlineKeyboard($inlineButton);

        if (isset($data)) {
            if ($data == 'update') {
                $this->editMessage($chatId, $messageId, 'updated ', $inlineKeyboard);
            }
            if ($data == 'delete') {
                $this->deleteMessage($chatId, $messageId);
            }
        }
        //----
        if (isset($message)) {
            if ($message == '/start') {
                $this->sendMessage($chatId, 'welcome to your bot ;)', $inlineKeyboard);
            }
        }

    }

    public function getChatMember($channelId, $userId)
    {
        return $this->callBot('getChatMember', [
            'chat_id' => $channelId,
            'user_id' => $userId
        ]);
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
        Log::info("\n \t\t --- response $methodName ----\n", [$response->json()]);
        return $response->json();
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
            'chat_id'      => $chatId,
            'message_id'   => $messageId,
            'text'         => $text,
            'reply_markup' => $reply_markup
        ]);
    }

    public function deleteMessage($chatId, $messageId)
    {
        $this->callBot('deleteMessage', [
            'chat_id'    => $chatId,
            'message_id' => $messageId,
        ]);
    }

    public function logs()
    {
        $path = base_path() . "/storage/logs/laravel.log";
        $file = File::get($path);
        return ($file);
    }

}
