<?php

namespace App\Controllers;

use App\Factories\RequestDtoFactory;
use App\Factories\HandlerFactory;
use Exception;

final class MainController extends Controller
{
    public function run()
    {
        try {
            $handler = HandlerFactory::make($this->request->dto);
            $handler->process();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
}