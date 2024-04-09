<?php
namespace Api\Handlers;

use Base\BaseHandlerRoute;

use Core\Error;

class UnknownHandler extends BaseHandlerRoute
{
    public function Init()
    {
        throw new Error(404, "Api Unknown path error", "Api Unknown handler error");
    }
}