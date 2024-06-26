<?php
namespace Api\Handlers;

use Core\Settings;

use Core\Warning;
use Core\Error;
use Core\Critical;

use Base\BaseHandlerRoute;

use Api\Models\MediaUploadImageModel;

use Api\Handlers\CaptchaTokenHandler;
use Api\Handlers\AdminSettingsGetHandler;

class MediaUploadImageHandler extends BaseHandlerRoute
{
    public function Init()
    {
        $parsedBody = $this->request->getParsedBody();
        
        if(is_array($parsedBody))
        {
            $this->parsedBody = $parsedBody;
            if(!empty($this->parsedBody['captchaToken']))
            {
                if(CaptchaTokenHandler::checkCaptchaToken($this->request->getServerParam('REMOTE_ADDR'), $this->parsedBody['captchaToken']))
                {
                    if(count($this->request->getUploadedFiles()) > 0)
                    {
                        $this->cookiesBody = $this->request->getCookieParams();
                        $this->model = new MediaUploadImageModel;
                    }
                    else
                    {
                        throw new Warning(400,"File not uploaded", "File not uploaded");
                    }
                }
                else
                {
                    throw new Error(400, "Invalid captcha solving", "Invalid captcha solving");
                }
            }
            else
            {
                throw new Error(400, "Invalid captcha solving", "Invalid captcha solving");
            } 
        }
        else
        {
            throw new Error(400, "Invalid request body", "Invalid request body");
        }
    }
    public function Process()
    {
        $uploadedFile = $this->request->getUploadedFiles()['file'];

        if($uploadedFile->getError() === UPLOAD_ERR_OK) 
        {
            $maxUploadFilesizeMB = AdminSettingsGetHandler::getSetting('max_upload_filesize_mb');
            if(isset($maxUploadFilesizeMB))
            {
                $maxFileSize = $maxUploadFilesizeMB * 1024 * 1024;
                    
                if($uploadedFile->getSize() > $maxFileSize) 
                {
                    throw new Warning(400, "File size exceeds the maximum allowable file size", "File size exceeds the maximum allowable file size", ['max_upload_filesize_mb' => $maxUploadFilesizeMB]);
                    return;
                }
                $allowedTypes = 
                [
                    'image/apng', 
                    'image/png', 
                    'image/avif', 
                    'image/gif', 
                    'image/jpeg', 
                    'image/svg+xml', 
                    'image/webp'
                ];
                    
                if(in_array($uploadedFile->getClientMediaType(), $allowedTypes))
                {
                    $newFileName = uniqid().bin2hex(random_bytes(10)).'.'.pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                    

                    $this->model->uploadImage($newFileName, $_FILES['file']['tmp_name']);
                    $this->response = $this->response->withHeader('Content-type', 'application/json')->withJson(
                    [
                        'fileName' => $newFileName
                    ]);
                }
                else
                {
                    throw new Warning(400, "Invalid image type", "Invalid image type");
                }
            }
            else
            {
                throw new Critical(500, "Unknown error", "Max upload filesize not found in settings");
            }
        } 
        else 
        {
            throw new Warning(400, "File not uploaded", "File not uploaded");
        }
    }
}