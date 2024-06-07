<?php
namespace Api\Handlers;

use Core\S3Client;

use Core\Warning;

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
        $this->response = $this->model->getImageByCode($this->args['file']);
    }
}