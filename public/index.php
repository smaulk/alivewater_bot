<?php

use App\Controllers\MainController;
use Pecee\SimpleRouter\SimpleRouter as Router;

require __DIR__ . '/../vendor/autoload.php';

Router::setDefaultNamespace('App\Controllers');
Router::post('/', [MainController::class, 'run']);

Router::start();