<?php

namespace App\Factories;

use App\Contracts\DtoContract;
use App\Handlers\Base\NotFoundHandler;
use App\Handlers\Base\StartMenuHandler;
use App\Handlers\Devices\DevicesInfoHandler;
use App\Handlers\Devices\GetDevicesListHandler;
use App\Handlers\Devices\RefreshDevicesListHandler;
use App\Handlers\Devices\SalesDeviceHandler;
use App\Handlers\Devices\SelectDeviceHandler;
use App\Handlers\Handler;

class HandlerFactory
{
    public static function make(DtoContract $dto): Handler
    {
        return match (true) {
            StartMenuHandler::validate($dto) => new StartMenuHandler($dto),
            GetDevicesListHandler::validate($dto) => new GetDevicesListHandler($dto),
            SelectDeviceHandler::validate($dto) => new SelectDeviceHandler($dto),
            RefreshDevicesListHandler::validate($dto) => new RefreshDevicesListHandler($dto),
            DevicesInfoHandler::validate($dto) => new DevicesInfoHandler($dto),
            SalesDeviceHandler::validate($dto) => new SalesDeviceHandler($dto),
            default => new NotFoundHandler($dto),
        };
    }
}