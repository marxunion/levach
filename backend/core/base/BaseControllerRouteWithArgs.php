<?php
namespace Core\Base;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Base\BaseControllerRoute;

class BaseControllerRouteWithArgs extends BaseControllerRoute
{
    protected $args;

    public function __construct(Request $request, Response $response, array $args)
    {
        parent::__construct($request, $response);
        $this->args = $args;
    }
}