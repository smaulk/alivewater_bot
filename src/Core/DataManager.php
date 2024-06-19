<?php
namespace App\Core;

use App\Dto\UserDto;
use App\Core\Helper;

final class DataManager
{
    private static function getPath($tgId)
    {
        return Helper::basePath('data').'/'.$tgId.'.json';
    }

    public static function readUserData(string $tgId): UserDto | null
    {
        $path = self::getPath($tgId);
        if(file_exists($path)){
            $file = file_get_contents($path);
            $data = json_decode($file);
            return (new UserDto())->fromJson($data);
        }
        return null;
    }

    public static function writeUserData(string $tgId, UserDto $userDto): void
    {
        $path = self::getPath($tgId);
        $json = $userDto->toJson();
        file_put_contents($path, $json);
    }

    public static function deleteUserData(string $tgId): void
    {
        $path = self::getPath($tgId);
        if(file_exists($path)){
            unlink($path);
        }
    }
}