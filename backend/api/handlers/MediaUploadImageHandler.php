<?php
namespace Api\Handlers;

use Core\Settings;
use Core\Warning;
use Core\Database;

use Base\BaseHandlerRoute;

use Api\Handlers\AdminSettingsGetHandler;

class MediaUploadImageHandler extends BaseHandlerRoute
{
    public function Process()
    {
        if(count($this->request->getUploadedFiles()) < 1)
        {
            throw new Warning(400,"UploadImage File not uploaded", "UploadImage File not uploaded");
            return;
        }

        $uploadedFile = $this->request->getUploadedFiles()['file'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) 
        {
            $maxUploadFilesizeMB = AdminSettingsGetHandler::getSetting('max_upload_filesize_mb');
            if(isset($maxUploadFilesizeMB))
            {
                $maxFileSize = $maxUploadFilesizeMB * 1024 * 1024;
                    
                if ($uploadedFile->getSize() > $maxFileSize) 
                {
                    throw new Warning(400, "UploadImage File size exceeds the maximum allowable file size", "UploadImage File size exceeds the maximum allowable file size");
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
                    $uploadPath = __DIR__.'/../../../media/img/';
                        
                    $newFileName = hash('sha3-256', uniqid().bin2hex(random_bytes(32)).$uploadedFile->getClientFilename()).'.'.pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                    
                    $uploadedFile->moveTo($uploadPath . DIRECTORY_SEPARATOR . $newFileName);
                    $this->response = $this->response->withStatus(200)->withHeader('Content-type', 'application/json')->withJson(
                    [
                        'fileName' => $newFileName
                    ]);
                }
                else
                {
                    throw new Warning(400, "UploadImage Invalid image type", "UploadImage Invalid image type");
                }
            }
            else
            {
                throw new Critical(400, "Max upload filesize not found in settings", "Max upload filesize not found in settings");
            }
        } 
        else 
        {
            throw new Warning(400, "UploadImage File not uploaded", "UploadImage File not uploaded");
        }
    }
}