<?php
namespace App\Core;
use App\Core\Env;
use App\Dto\ResultDto;
use App\Enums\TelegramMethod;

final class Telegram
{
    public function send(TelegramMethod $method, array $data): array
    {
        $uri = $this->getUri($method->value);
        return Curl::post($uri, $data);
    }

    private function getUri(string $method): string
    {
        $url = "https://api.telegram.org/bot";
        $token = Env::get('TG_TOKEN');
        return $url.$token.'/'.$method;
    }
}