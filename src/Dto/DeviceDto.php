<?php

namespace App\Dto;

readonly class DeviceDto
{
    public function __construct(
        public string        $uuid,
        public string        $address,
        public int           $coins,
        public int           $costPerLiter,
        public CashOperation $lastEncash,
        public CashOperation $lastSale,
    ){}

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'address' => $this->address,
            'coins' => $this->coins,
            'costPerLiter' => $this->costPerLiter,
            'lastEncash' => $this->lastEncash->toArray(),
            'lastSale' => $this->lastSale->toArray(),
        ];
    }
}

readonly class CashOperation
{
    public function __construct(
        public string $date,
        public int    $amount,
    ){}

    public function toArray(): array
    {
        return (array)$this;
    }
}
