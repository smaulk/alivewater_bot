<?php

use App\Controllers\MainController;
use App\Middlewares\AuthMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Core\Env;
use Pecee\Http\Request;
use App\Managers\JsonManager;
use App\Core\Helper;

require __DIR__ . '/../vendor/autoload.php';

Router::setDefaultNamespace('App\Controllers');
Router::post(
    '/' .Env::get('ROUTE')
    , [MainController::class, 'run']
)->addMiddleware(AuthMiddleware::class);

Router::error(function(Request $request, Exception $exception)  {
    Router::response()->httpCode(200);
    $json = ['message' => $exception->getMessage()];
    (new JsonManager(Helper::basePath().'/error.json'))->writeJson($json, true);
    Router::response()->json($json);
});

Router::start();