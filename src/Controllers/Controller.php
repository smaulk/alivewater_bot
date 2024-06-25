<?php
namespace App\Controllers;

use Pecee\Http\Request;
use Pecee\Http\Response;
use Pecee\SimpleRouter\SimpleRouter as Router;

abstract class Controller
{
    /**
     * @var Response
     */
    protected Response $response;
    /**
     * @var Request
     */
    protected Request $request;


    public function __construct()
    {
        $this->request = Router::router()->getRequest();
        $this->response =  new Response($this->request);
    }
}