<?php
namespace App\Core;
use App\Core\Env;
use App\Enums\TelegramMethod;

final class Telegram
{
    private const string TG_URL = 'https://api.telegram.org/bot';

    public function send(TelegramMethod $method, array $data): array
    {
        $uri = $this->getUri().$method->value;
        return Curl::post($uri, $data, [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ]);
    }

    private function getUri(): string
    {
        $token = Env::get('TG_TOKEN');
        return self::TG_URL.$token.'/';
    }
}