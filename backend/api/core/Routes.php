<?php
namespace Api\Core;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;
use Slim\Psr7\Stream;
use Slim\App;

use Core\Settings;
use Core\Logger;
use Core\Database;

class Routes
{
    private static App $app;

    public static function Init(App $app)
    {
        self::$app = $app;
        
        self::$app->group('/api', function (RouteCollectorProxy $group) 
        {
            $group->get('/', function (Request $request, Response $response) 
            {
                $data = [
                    'apiStatus' => "ok"
                ];
                return $response->withJson($data);
            });
    
            $group->get('/status', function (Request $request, Response $response) 
            {
                $data = [
                    'apiStatus' => "ok"
                ];
                return $response->withJson($data);
            });
    
            $group->post('/article/new', function (Request $request, Response $response) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });

            $group->post('/article/edit/{article}', function (Request $request, Response $response, array $args) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });
    
            $group->get('/article/view/{article}', function (Request $request, Response $response, array $args) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });
    
            $group->get('/articles', function (Request $request, Response $response) 
            {
                $data = $request->getParsedBody();
                return $response->withJson($data);
            });
    
            $group->get('/media/img/{file}', function (Request $request, Response $response, array $args) 
            {
                $file = $args['file'];
            
                $filePath = __DIR__.'/../../../media/img/'.$args['file'];
                
                try 
                {
                    if (file_exists($filePath)) 
                    {
                        try 
                        {
                            return $response->withFile($filePath);
                        } 
                        catch (\Throwable $th) 
                        {
                            Logger::WriteError("LoadFile Unable to open file, filePath=".$filePath);
                            return $response->withStatus(500)->withHeader('Content-type', 'application/json')->withJson(['errorStatus' => true, 'errorMessage' => 'Unable to open file', 'errorCode' => "001002"]);
                        }
                    }
                    else
                    {
                        Logger::WriteWarning("LoadFile File not found, filePath=".$filePath);
                        return $response->withStatus(404)->withHeader('Content-type', 'application/json')->withJson(['errorStatus' => true, 'errorMessage' => 'File not found', 'errorCode' => "001001"]);
                    }
                }
                catch (\Throwable $th)
                {
                    Logger::WriteError("LoadFile File not found, filePath=".$filePath);
                    return $response->withStatus(500)->withHeader('Content-type', 'application/json')->withJson(['errorStatus' => true, 'errorMessage' => 'File not found', 'errorCode' => "001001"]);
                }
            });
            $group->post('/media/img/upload', function (Request $request, Response $response) 
            {
                if(count($request->getUploadedFiles()) < 1)
                {
                    Logger::WriteWarning("UploadFile File not uploaded");
                    return $response->withStatus(400)->withHeader('Content-type', 'application/json')->withJson(['errorStatus' => true, 'errorMessage' => 'File not uploaded', 'errorCode' => "002001"]);
                }

                $uploadedFile = $request->getUploadedFiles()['file'];

                if ($uploadedFile->getError() === UPLOAD_ERR_OK) 
                {
                    $maxFileSize = intval(Settings::Get('MAX_UPLOAD_FILESIZE_MB')) * 1024 * 1024;
                        
                    if ($uploadedFile->getSize() > $maxFileSize) 
                    {
                        Logger::WriteWarning("UploadFile File size exceeds the maximum allowable file size");
                        return $response->withStatus(400)->withHeader('Content-type', 'application/json')->withJson(['errorStatus' => true, 'errorMessage' => 'File size exceeds the maximum allowable file size', 'errorCode' => "002002"]);
                    }

                    $allowedTypes = ['image/apng', 'image/png', 'image/avif', 'image/gif', 'image/jpeg', 'image/svg+xml', 'image/webp'];
                    
                    if(in_array($uploadedFile->getClientMediaType(), $allowedTypes))
                    {
                        $uploadPath = __DIR__.'/../../../media/img/';
                        
                        $newFileName = hash('sha3-256', uniqid().bin2hex(random_bytes(32)).$uploadedFile->getClientFilename()).'.'.pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
                        
                        $uploadedFile->moveTo($uploadPath . DIRECTORY_SEPARATOR . $newFileName);
                        return $response->withStatus(200)->withHeader('Content-type', 'application/json')->withJson(['fileName' => $newFileName]);
                    }
                    else
                    {
                        Logger::WriteWarning("UploadFile File size exceeds the maximum allowable file size");
                        return $response->withStatus(400)->withHeader('Content-type', 'application/json')->withJson(['errorStatus' => true, 'errorMessage' => 'Invalid image type', 'errorCode' => "002003"]);
                    }
                } 
                else 
                {
                    Logger::WriteWarning("UploadFile File not uploaded");
                    return $response->withStatus(404)->withHeader('Content-type', 'application/json')->withJson(['errorStatus' => true, 'errorMessage' => 'File not uploaded', 'errorCode' => "002001"]);
                }
            });
        });
    
        self::$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) 
        {
            $data = [
                'apistatus' => "ok"
            ];
            return $response->withJson($data);
        });
    }
}