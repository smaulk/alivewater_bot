<?php

namespace App\Core;

final class Curl
{
    private static function postCurl(string $url, array $data, array $headers = []): mixed
    {
        $data = empty($data) ? '' : json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result);
        $result->HTTP_CODE = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $result;
    }

    private static function getCurl(string $url, array $data, array $headers = []): mixed
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url.http_build_query($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result);
        $result->HTTP_CODE = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $result;
    }

    public static function post(string $url, array $data, string $token = null): mixed
    {
        return Curl::postCurl($url, $data, Curl::getHeaders($token));
    }

    public static function get(string $url, array $data, string $token = null): mixed
    {
        return Curl::getCurl($url, $data, Curl::getHeaders($token));
    }

    private static function getHeaders(string $token = null): array
    {
        $headers = [
            'Content-type: application/json',
            'Accept: application/json',
        ];
        if(!is_null($token)) $headers[] = 'Authorization: Bearer ' . $token;
        return $headers;
    }
}