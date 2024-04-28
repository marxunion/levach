<?php
namespace Api\Handlers;

use Core\Error;

use Base\BaseHandlerRoute;

class csrfTokenHandler extends BaseHandlerRoute
{
    public function Init()
    {
        
        session_start();
        $oldToken = $_SESSION['csrfToken'];
        $token = hash('sha3-224', uniqid().bin2hex(random_bytes(random_int(60,80))));
        $_SESSION['csrfToken'] = $token;

        $this->response = $this->response->withJson(['oldToken' => $oldToken, 'token' => $token]);
    }
}