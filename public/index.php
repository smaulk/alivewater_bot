<?php

use App\Controllers\MainController;
use App\Middlewares\AuthMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Core\Env;
use Pecee\Http\Request;

require __DIR__ . '/../vendor/autoload.php';

Router::setDefaultNamespace('App\Controllers');
Router::post(
    '/'
//    .Env::get('ROUTE')
    , [MainController::class, 'run']
)->addMiddleware(AuthMiddleware::class);

Router::error(function(Request $request, Exception $exception)  {
    Router::response()->httpCode(200);
    Router::response()->json([
        'message' => $exception->getMessage()
    ]);
});

Router::start();