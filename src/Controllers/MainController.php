<?php
namespace App\Controllers;

use App\Core\DataManager;
use App\Core\Env;
use App\Dto\UserDto;
use App\Factories\DtoFactory;
use App\Factories\HandlerFactory;
use App\Handler;
use Exception;
use Pecee\Http\Request;
use Pecee\SimpleRouter\SimpleRouter;

final class MainController extends Controller
{

    /**
     * @throws Exception
     */
    public function run()
    {
        $dto = DtoFactory::make($this->request);
        $handler = HandlerFactory::make($dto);

        $handler->process();
    }
}