<?php
namespace Api\Handlers;

use Api\Models\AdminModel;

use Base\BaseHandlerRoute;
use Api\Models\AdminModel;

class AdminHandler extends BaseHandlerRoute
{
    public static function isAdmin()
    {
        if(AdminModel::isAdmin())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}