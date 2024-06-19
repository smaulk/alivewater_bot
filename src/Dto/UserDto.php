<?php
namespace App\Dto;

class AuthData
{
    public string $token = '';
    public string $refreshToken = '';
}

class UserDto
{
    public string $username = '';
    public string $password = '';
    public string $uid = '';
    public AuthData $auth;

    public function __construct(){
        $this->auth = new AuthData();
    }

    public function fromJson($json): UserDto
    {
        $this->username = $json->username ?? '';
        $this->password = $json->password ?? '';
        $this->uid = $json->uid ?? '';
        $this->auth->token = $json->auth->token ?? '' ;
        $this->auth->refreshToken = $json->auth->refreshToken ?? '' ;
        return $this;
    }

    public function toJson(): string
    {
        return json_encode($this, JSON_PRETTY_PRINT);
    }

}