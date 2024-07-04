<?php
namespace App\Enums;

enum State: string
{
    case StartMenu = "Открыть меню";
    case Login = "Войти в аккаунт";
    case InputUsername = "InputUsername";
    case InputPassword = "InputPassword";
    case DeviceList = "Выбрать аппарат";
    case SelectDevice = "sdv";
}