<?php

namespace App\Dto;

use App\Enums\SaleType;

readonly class SaleDto
{
    public function __construct(
        public string   $date,
        public int      $amount,
        public float    $litres,
        public SaleType $type,
        public ?string   $address,
    )
    {
    }

    public function toArray(): array
    {
        return (array)$this;
    }
}