<?php

use App\Controllers\MainController;
use App\Middlewares\AuthMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Router;
use App\Core\Env;

require __DIR__ . '/../vendor/autoload.php';

Router::setDefaultNamespace('App\Controllers');
Router::post(
    '/'.Env::get('ROUTE'),
    [MainController::class, 'run']
)->addMiddleware(AuthMiddleware::class);
Router::response()->httpCode(200);
Router::start();