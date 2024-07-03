<?php
namespace App\Core;
use App\Core\Env;
use App\Enums\TelegramMethod;

final class Telegram
{
    public function send(TelegramMethod $method, array $data): array
    {
        $uri = $this->getUri().$method->value;
        return Curl::post($uri, $data, [
            'Content-type: application/json',
            'Accept: application/json',
        ]);
    }

    private function getUri(): string
    {
        $token = Env::get('TG_TOKEN');
        return "https://api.telegram.org/bot$token/";
    }
}