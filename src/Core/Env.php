<?php
namespace App\Core;

final class Env
{
    private static array $params = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if(empty(self::$params)) {
            self::load();
        }

        return self::$params[$key] ?? $default;
    }

    private static function load(): void
    {
        $path = Helper::basePath('.env');
        $file = fopen($path, 'r');
        while (($string = fgets($file)) !== false) {
            $string = trim($string);
            if (empty($string)) {
                continue;
            }
            [$key, $value] = explode('=', $string, 2);
            self::$params[$key] = $value;
        }
    }
}