<?php

namespace App\Middlewares;

use App\Factories\AuthHandlerFactory;
use App\Factories\RequestDtoFactory;
use App\Handlers\Auth\Auth;
use Exception;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
    /**
     * @throws Exception
     */
    public function handle(Request $request): void
    {
        $request->dto = RequestDtoFactory::make($request);

        $handler = AuthHandlerFactory::make($request->dto);
        if(!is_null($handler))
        {
            $handler->process();
            throw new Exception('Пользователь не авторизован!');
        }
        $auth = new Auth($request->dto);
        if (!$auth->check()) {
            $auth->sendError();
            throw new Exception('Ошибка: не удалось войти в аккаунт!');
        }
    }
}