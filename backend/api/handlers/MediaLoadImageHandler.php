<?php
namespace Api\Handlers;

use Core\S3Client;

use Core\Warning;

use GuzzleHttp\Psr7\Stream as GuzzleStream;
use Slim\Psr7\Factory\StreamFactory;
use Api\Models\MediaLoadImageModel;

use Base\BaseHandlerRouteWithArgs;

class MediaLoadImageHandler extends BaseHandlerRouteWithArgs
{
    private $file;
    private $filePath;

    public function Init()
    {
        if(!empty($this->args['file']))
        {
            $this->model = new MediaLoadImageModel;
        }
        else
        {
            throw new Warning(404, "LoadImage File not found", "LoadImage File not found, filePath=".$this->filePath);
        }
    }
    public function Process()
    {
        $result = $this->model->getImageByCode($this->args['file']);

        if(isset($result))
        {
            $imageType = $result['ContentType'];
            $imageData = $result['Body']->getContents();
            
            $this->response = $this->response->withHeader('Content-Type', $imageType);
            $this->response->getBody()->write($imageData);
        }
        else
        {
            throw new Warning(404, "LoadImage File not found", "LoadImage File not found, filePath=".$this->filePath);
        }
    }
}