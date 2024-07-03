<?php

namespace App\Dto;

class DeviceDto
{
    public string $uuid;
    public string $address;
    public int $coins;

    public function __construct(array $body)
    {
        $this->uuid = $body['Id'];
        $this->address = $body['Info']['Address'];
        $this->coins = $body['DeviceState']['Coins'];
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}