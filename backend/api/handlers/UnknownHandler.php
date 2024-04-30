<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

class UnknownHandler extends BaseHandlerRoute
{
    public function Init()
    {
        throw new Error(404, "Api Unknown path error", "Api Unknown handler error");
    }
}