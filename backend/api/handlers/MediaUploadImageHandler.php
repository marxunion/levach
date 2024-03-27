<?php
namespace Api\Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

use Core\Settings;
use Core\Logger;
use Core\Database;

use Base\BaseHandler;
use Base\BaseHandlerRouter;

class MediaUploadImageHandler extends BaseHandlerRouter
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
    }
    public function process()
    {
        if(count($this->request->getUploadedFiles()) < 1)
        {
            Logger::WriteWarning("UploadFile File not uploaded");
            return $this->response->withStatus(400)->withHeader('Content-type', 'application/json')->withJson(
            [
                'errorStatus' => true, 
                'errorMessage' => 'File not uploaded', 
                'errorCode' => "002001"
            ]);
        }

        $uploadedFile = $this->request->getUploadedFiles()['file'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) 
        {
            $maxFileSize = intval(Settings::Get('MAX_UPLOAD_FILESIZE_MB')) * 1024 * 1024;
                
            if ($uploadedFile->getSize() > $maxFileSize) 
            {
                Logger::WriteWarning("UploadFile File size exceeds the maximum allowable file size");
                return $this->response->withStatus(400)->withHeader('Content-type', 'application/json')->withJson(['errorStatus' => true, 'errorMessage' => 'File size exceeds the maximum allowable file size', 'errorCode' => "002002"]);
            }
            $allowedTypes = ['image/apng', 'image/png', 'image/avif', 'image/gif', 'image/jpeg', 'image/svg+xml', 'image/webp'];
            
            if(in_array($uploadedFile->getClientMediaType(), $allowedTypes))
            {
                $uploadPath = __DIR__.'/../../../media/img/';
                
                $newFileName = hash('sha3-256', uniqid().bin2hex(random_bytes(32)).$uploadedFile->getClientFilename()).'.'.pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                
                $uploadedFile->moveTo($uploadPath . DIRECTORY_SEPARATOR . $newFileName);
                return $this->response->withStatus(200)->withHeader('Content-type', 'application/json')->withJson(
                [
                    'fileName' => $newFileName
                ]);
            }
            else
            {
                Logger::WriteWarning("UploadFile File size exceeds the maximum allowable file size");
                return $this->response->withStatus(400)->withHeader('Content-type', 'application/json')->withJson(
                [
                    'errorStatus' => true, 
                    'errorMessage' => 'Invalid image type', 
                    'errorCode' => "002003"
                ]);
            }
        } 
        else 
        {
            Logger::WriteWarning("UploadFile File not uploaded");
            return $this->response->withStatus(404)->withHeader('Content-type', 'application/json')->withJson(
            [
                'errorStatus' => true, 
                'errorMessage' => 'File not uploaded', 
                'errorCode' => "002001"
            ]);
        }
    }
}