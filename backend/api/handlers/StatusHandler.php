<?php
namespace Api\Handlers;

use Base\BaseHandlerRoute;

class StatusHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $this->response = $this->response->withStatus(200)->withJson([
            "status" => true,
        ]);
    }
}