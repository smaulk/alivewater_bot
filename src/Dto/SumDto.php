<?php

namespace App\Dto;

use App\Enums\Currency;

readonly class SumDto
{
    public function __construct(
        public Currency $currency,
        public int      $amount,
        public int      $coins,
        public int      $mobileApp,
        public int      $litres,
    )
    {}


    public function toArray(): array
    {
        return (array)$this;
    }
}