<?php

namespace App\Services;

use App\Core\Api;
use App\Dto\UserDto;

abstract class Service
{
    protected UserDto $userDto;
    protected Api $api;

    public function __construct(UserDto $dto)
    {
        $this->userDto = $dto;
        $this->api = new Api($dto->auth->token);
    }

    protected function getRoute(string $path = null): string
    {
        return $this->getMainRoute() . '/' . $path;
    }

    abstract protected function getMainRoute(): string;
}