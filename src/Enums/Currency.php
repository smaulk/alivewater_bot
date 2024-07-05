<?php

namespace App\Enums;


enum Currency: string
{
    case KZT = '₸';
    case RUB = '₽';
    case USD = '$';
    case EUR = '€';

    public static function get(string $code): ?self
    {
        // Ищем соответствующее значение по названию
        foreach (self::cases() as $currency) {
            if ($currency->name === $code) {
                return $currency;
            }
        }
        // Возвращаем null, если код валюты не найден
        return null;
    }
}