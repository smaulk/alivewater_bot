<?php

namespace App\Dto;

readonly class UserDto
{
    public function __construct(
        public ?string  $username = null,
        public ?string  $password = null,
        public ?string  $uuid = null,
        public AuthData $auth = new AuthData(),
    ){}

    public static function fromArray(array $data): UserDto
    {
        return new self(
            $data['username'] ?? null,
            $data['password'] ?? null,
            $data['uuid'] ?? null,
            new AuthData(
                $data['auth']['token'] ?? null,
                $data['auth']['refreshToken'] ?? null
            ),
        );
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'uuid' => $this->uuid,
            'auth' => $this->auth->toArray(),
        ];
    }
}

readonly class AuthData
{
    public function __construct(
        public ?string $token = null,
        public ?string $refreshToken = null
    ){}

    public function toArray(): array
    {
        return (array)$this;
    }
}
