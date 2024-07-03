<?php

namespace App\Core;

final class Api
{
    private static string $API_URL = "https://cabinet.api.alivewater.online/";

    public static function get(string $route, $data, string $token = null): array
    {
        return Curl::get(self::$API_URL.$route, $data, self::getHeaders($token));
    }

    public static function post(string $route, $data, string $token = null): array
    {
        return Curl::post(self::$API_URL.$route, $data, self::getHeaders($token));
    }

    private static function getHeaders(string $token = null): array
    {
        $headers = [
            'Content-type: application/json',
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
        ];
        if(!is_null($token)) $headers[] = 'Authorization: Bearer ' . $token;
        return $headers;
    }
}