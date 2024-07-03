<?php

namespace App\Services;

use App\Dto\UserDto;

abstract class Service
{
    protected UserDto $userDto;

    public function __construct(UserDto $dto)
    {
        $this->userDto = $dto;
    }

    protected function getRoute(string $path = null): string
    {
        return $this->getMainRoute() . '/' . $path;
    }

    abstract protected function getMainRoute(): string;
}