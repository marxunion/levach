<?php
namespace Base;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

use Base\BaseHandlerRouter;

class BaseHandlerRouterWithArgs extends BaseHandlerRouter
{
    protected array $args;

    public function __construct(Request $request, Response $response, array $args)
    {
        parent::__construct($request, $response);
        $this->args = $args;
    }
}