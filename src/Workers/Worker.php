<?php

namespace App\Workers;

use App\Dto\UserDto;

abstract class Worker
{
    private string $apiUrl = "https://cabinet.api.alivewater.online/";
    protected UserDto $userDto;

    protected function getUrl(string $route = null): string
    {
        return $this->apiUrl.$this->getPath().'/'.$route;
    }

    public function __construct(UserDto $dto)
    {
        $this->userDto = $dto;
    }

    abstract protected function getPath(): string;
}