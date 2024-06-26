<?php

namespace App\Factories;

use App\Contracts\DtoContract;
use App\Handlers\Base\NotFoundHandler;
use App\Handlers\Base\StartMenuHandler;
use App\Handlers\Devices\DevicesHandler;
use App\Handlers\Devices\SelectDeviceHandler;
use App\Handlers\Handler;

class HandlerFactory
{
    public static function make(DtoContract $dto): Handler
    {
        return match (true) {
            StartMenuHandler::validate($dto) => new StartMenuHandler($dto),
            DevicesHandler::validate($dto) => new DevicesHandler($dto),
            SelectDeviceHandler::validate($dto) => new SelectDeviceHandler($dto),
            default => new NotFoundHandler($dto),
        };
    }
}