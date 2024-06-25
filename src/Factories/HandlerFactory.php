<?php

namespace App\Factories;

use App\Handlers\Base\NotFoundHandler;
use App\Handlers\Base\StartMenuHandler;
use App\Handlers\DevicesHandler;
use App\Handlers\Handler;
use App\Contracts\DtoContract;
use Exception;

class HandlerFactory
{
    public static function make(DtoContract $dto): Handler
    {
        return match (true) {
            StartMenuHandler::validate($dto) => new StartMenuHandler($dto),
            DevicesHandler::validate($dto) => new DevicesHandler($dto),
            default => new NotFoundHandler($dto),
        };
    }
}