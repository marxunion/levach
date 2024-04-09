<?php
namespace Api\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

use Core\Settings;
use Core\Warning;
use Core\Database;

use Base\BaseHandlerRoute;

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
            $maxFileSize = intval(Settings::getProperty('MAX_UPLOAD_FILESIZE_MB')) * 1024 * 1024;
                    
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
                return;
            }
        } 
        else 
        {
            throw new Warning(400, "UploadImage File not uploaded", "UploadImage File not uploaded");
            return;
        }
    }
}