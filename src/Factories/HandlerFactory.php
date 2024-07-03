<?php

namespace App\Factories;

use App\Contracts\DtoContract;
use App\Handlers\Base\NotFoundHandler;
use App\Handlers\Base\StartMenuHandler;
use App\Handlers\Devices\GetDevicesHandler;
use App\Handlers\Devices\RefreshDevicesHandler;
use App\Handlers\Devices\SelectDeviceHandler;
use App\Handlers\Handler;

class HandlerFactory
{
    public static function make(DtoContract $dto): Handler
    {
        return match (true) {
            StartMenuHandler::validate($dto) => new StartMenuHandler($dto),
            GetDevicesHandler::validate($dto) => new GetDevicesHandler($dto),
            SelectDeviceHandler::validate($dto) => new SelectDeviceHandler($dto),
            RefreshDevicesHandler::validate($dto) => new RefreshDevicesHandler($dto),
            default => new NotFoundHandler($dto),
        };
    }
}