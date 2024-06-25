<?php

use App\Controllers\MainController;
use App\Middlewares\AuthMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Router;

require __DIR__ . '/../vendor/autoload.php';

Router::setDefaultNamespace('App\Controllers');
Router::post('/', [MainController::class, 'run'])->addMiddleware(AuthMiddleware::class);
Router::response()->httpCode(200);
Router::start();