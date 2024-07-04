<?php

namespace App\Core;

final class Api
{
    private const string API_URL = "https://cabinet.api.alivewater.online/";
    private readonly ?string $TOKEN;

    public function __construct(?string $TOKEN)
    {
        $this->TOKEN = $TOKEN;
    }

    public function get(string $route, $data = []): array
    {
        return Curl::get(self::API_URL . $route, $data, $this->getHeaders());
    }

    public function post(string $route, $data = []): array
    {
        return Curl::post(self::API_URL . $route, $data, $this->getHeaders());
    }

    private function getHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent'   => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36',
        ];
        if (!empty($this->TOKEN)) {
            $headers['Authorization'] = 'Bearer ' . $this->TOKEN;
        }

        return $headers;
    }
}