<?php
namespace App\Core;

use DateTime;
use DateTimeZone;
use Exception;

final class Helper
{
    public static function basePath(string $path = null): string
    {
        return realpath(__DIR__ . '/../../' . self::preparePath($path));
    }

    public static function appPath(string $path): string
    {
        return self::basePath('src');
    }

    private static function preparePath(?string $path): string
    {
        return $path === null ? '' : trim($path, '/');
    }


    public static function encrypt($data): string
    {
        $key = Env::get('ENCRYPT_KEY');
        // Генерация инициализационного вектора (IV)
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        // Шифрование данных
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        // Комбинирование IV и зашифрованных данных
        return base64_encode($iv . $encryptedData);
    }

    public static function decrypt($encrypted): string
    {
        $key = Env::get('ENCRYPT_KEY');
        // Раскодирование закодированных данных
        $encryptedDataWithIv = base64_decode($encrypted);
        // Извлечение IV и зашифрованных данных
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($encryptedDataWithIv, 0, $ivLength);
        $encryptedData = substr($encryptedDataWithIv, $ivLength);
        // Дешифрование данных
        return openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
    }

    /**
     * @throws Exception
     */
    public static function getDate(string $timestamp, string $format, string $timezone = 'UTC' ): string
    {
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone($timezone));
        $dt->setTimestamp($timestamp);
        return $dt->format($format);
    }
}