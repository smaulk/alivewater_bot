<?php

namespace App\Workers;

use App\Core\Curl;
use App\Core\Helper;
use App\Dto\UserDto;

final class AuthWorker extends Worker
{

    protected function getPath(): string
    {
        return "users";
    }

    /**
     * Производит авторизацию, проверяет и получает токены.
     * @return UserDto|null При успехе вовзращается UserDto, если войти не удалось - null.
     */
    public function auth(): UserDto | null
    {
        if(empty($this->userDto->username) || empty($this->userDto->password))
        {
            return null;
        }

        if(empty($this->userDto->auth->token) || empty($this->userDto->auth->refreshToken))
        {
            return $this->login($this->userDto);
        }

        if(!$this->checkTokenValidity($this->userDto->auth->token))
        {
            return $this->refresh($this->userDto);
        }

        return $this->userDto;
    }

    /**
     * Вход в аккаунт с проверкой и записью новых данных.
     * @param UserDto $dto
     * @return UserDto|null
     */
    private function login(UserDto $dto): UserDto | null
    {
        $resp = $this->loginRequest($dto);
        if($resp['HTTP_CODE'] !== 200) return null;
        $dto->uid= $resp['User']['Id'];
        $dto->auth->token = $resp['Token'];
        $dto->auth->refreshToken = $resp['Refresh'];

        return $dto;
    }

    /**
     * Обновление токена с проверкой и записью новых данных.
     * @param UserDto $dto
     * @return UserDto|null
     */
    private function refresh(UserDto $dto): UserDto | null
    {
        $resp = $this->refreshRequest($dto);
        if($resp['HTTP_CODE'] !== 200) return $this->login($dto);
        $dto->auth->token = $resp['Token'];
        $dto->auth->refreshToken = $resp['Refresh'];
        return $dto;
    }

    /**
     * Запрос на вход в аккаунт.
     * @param UserDto $dto
     * @return array
     */
    private function loginRequest(UserDto $dto): array
    {
        $data = [
            'Username' => $dto->username,
            'Password' => Helper::decrypt($dto->password),
        ];
        return Curl::post($this->getUrl('auth/signin'), $data);
    }

    /**
     * Запрос на обновление токена.
     * @param UserDto $dto
     * @return array
     */
    private function refreshRequest(UserDto $dto): array
    {
        $data = [
            'Refresh' => $dto->auth->refreshToken,
        ];
        return Curl::post($this->getUrl('auth/refresh'), $data);
    }

    /**
     * Проверка валидности jwt токена.
     * @param string $token
     * @return bool
     */
    private function checkTokenValidity(string $token): bool
    {
        $resp = Curl::get($this->getUrl('profile'), [], $token);
        if($resp['HTTP_CODE']=== 200) return true;
        return false;

//        $payload =  base64_decode(explode('.', $token)[1]);
//        preg_match('/"exp":(\d+)/', $payload, $matches);
//
//        return time() >= (int)$matches[1];
    }
}