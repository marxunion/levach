<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

class CSRFTokenHandler extends BaseHandlerRoute
{
    public static function checkCsrfToken($token)
    {
        session_start();

        if($_SESSION['csrfToken'] == $token)
        {
            return true;
        }
        else
        {
            throw new Error(403, "Invalid CSRF token", "Invalid CSRF token");
        }
    }
    public function Init()
    {
        session_start();
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrfToken'] = $token;

        $this->response = $this->response->withJson(['token' => $token]);
    }
}