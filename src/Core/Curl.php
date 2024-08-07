<?php

namespace App\Core;

final class Curl
{
    public static function post(string $url, array $data, array $headers = []): array
    {
        $data = empty($data) ? '' : json_encode($data);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => self::formatHeaders($headers),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, true);
        $result['HTTP_CODE'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $result;
    }

    public static function get(string $url, array $data, array $headers = []): array
    {
        $data = empty($data)
            ? null
            : '?' . http_build_query($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $data,
            CURLOPT_HTTPHEADER => self::formatHeaders($headers),
            CURLOPT_RETURNTRANSFER => true,
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, true);
        $result['HTTP_CODE'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return $result;
    }

    private static function formatHeaders(array $headers): array
    {
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = "{$key}: {$value}";
        }
        return $formattedHeaders;
    }
}