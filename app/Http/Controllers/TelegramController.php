<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        \Log::info('request log ', $request->all());
        $token = env('TELEGRAM_API');
        $chat_id = env('USER_ID');
        $data = Student::query()->first()->toArray();
        var_dump($data);
        $u = sprintf("https://api.telegram.org/bot%s/sendmessage?chat_id=%s&text=%s", $token, $chat_id, json_encode($data));
        var_dump($u);

        $result = Http::post('https://api.telegram.org/bot' . $token . '/sendmessage', ['chat_id' => $chat_id, 'text' => json_encode($data)]);
        return $result;

    }
}
