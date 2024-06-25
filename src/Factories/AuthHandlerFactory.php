<?php

namespace App\Factories;

use App\Handlers\Auth\LoginHandler;
use App\Handlers\Auth\LoginStateHandler;
use App\Handlers\Base\StartHandler;
use App\Handlers\Handler;
use App\Contracts\DtoContract;

class AuthHandlerFactory
{

    public static function make(DtoContract $dto): Handler|null
    {
        return match (true) {
            LoginStateHandler::validate($dto) => new LoginStateHandler($dto),
            LoginHandler::validate($dto) => new LoginHandler($dto),
            StartHandler::validate($dto) => new StartHandler($dto),
            default => null,
        };
    }
}