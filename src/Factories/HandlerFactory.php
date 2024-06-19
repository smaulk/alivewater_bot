<?php

namespace App\Factories;

use App\Handler;
use App\Handlers\DevicesHandler;
use App\Handlers\StartHandler;
use App\Interfaces\DtoMessage;
use Exception;

class HandlerFactory
{

    /**
     * @throws Exception
     */
    public static function make(DtoMessage $dto): Handler
    {
        return match (true) {
            StartHandler::validate($dto) => new StartHandler($dto),
            DevicesHandler::validate($dto) => new DevicesHandler($dto),
            default => throw new Exception(),
        };
    }
}