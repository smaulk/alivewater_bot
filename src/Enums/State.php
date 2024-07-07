<?php
namespace App\Enums;

enum State: string
{
    case StartMenu = "Открыть меню";
    case Login = "Войти в аккаунт";
    case InputUsername = "InputUsername";
    case InputPassword = "InputPassword";
    case DeviceList = "Выбрать аппарат";
    case DevicesInfo = "Посмотреть аппараты";
    case SelectDevice = "sdv";
    case DeviceSales = "dvsl";
    case Sales = "Продажи";
    case PeriodSales = "psl";
}