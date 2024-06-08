<?php
namespace Api\Handlers;

use GuzzleHttp\Client;

use Core\Settings;
use Core\Error;

use Base\BaseHandlerRoute;

class CaptchaTokenHandler extends BaseHandlerRoute
{
    public static function checkCaptchaToken($remoteIp, $token)
    {
        if(Settings::getSetting('CAPTCHA_ENABLED'))
        {
            $client = new Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', 
            [
                'form_params' => [
                    'secret' => Settings::getSetting('RECAPTCHA_SECRETKEY'),
                    'response' => $token,
                    'remoteip' => $remoteIp
                ]
            ]);
    
            $body = json_decode($response->getBody());
            
            if($body->success) 
            {
                return true;
            } 
            else 
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }
}