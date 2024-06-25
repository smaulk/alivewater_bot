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

    public function fromArray(array $data): UserDto
    {
        $this->username = $data['username'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->uid = $data['uid'] ?? '';
        $this->auth->token = $data['auth']['token'] ?? '' ;
        $this->auth->refreshToken = $data['auth']['refreshToken'] ?? '' ;
        return $this;
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}