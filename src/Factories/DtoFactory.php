<?php

namespace App\Factories;

use App\Interfaces\DtoMessage;
use App\Dto\CallbackDto;
use App\Dto\TextDto;
use Exception;
use Pecee\Http\Request;

class DtoFactory
{
    /**
     * @throws Exception
     */
    public static function make(Request $request): DtoMessage
    {
        $body = (array) $request->getInputHandler()->all();
        return match (true) {
            self::isTextMessage($body) => new TextDto($body),
            self::isCallback($body) => new CallbackDto($body),
            default => throw new Exception(),
        };
    }

    private static function isTextMessage(array $body): bool
    {
        return !empty($body['message'])
            && !empty($body['message']['chat']['id'])
            && !empty($body['message']['text']);
    }

    private static function isCallback(array $body): bool
    {
        return !empty($body['callback_query'])
            && !empty($body['callback_query']['from']['id'])
            && !empty($body['callback_query']['data'])
            && !empty($body['callback_query']['message']['message_id'])
            && !empty($body['callback_query']['id']);
    }
}