<?php

namespace App\Dto;

class DeviceDto
{
    public string $uuid;
    public string $address;
    public int $coins;
    public Encash $lastEncash;

    public function __construct()
    {
        $this->lastEncash = new Encash();
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}

class Encash
{
    public string $date;
    public int $coins;
}