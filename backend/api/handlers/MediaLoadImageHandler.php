<?php
namespace Api\Handlers;

use Core\Warning;
use Core\Database;

use Base\BaseHandlerRouteWithArgs;

class MediaLoadImageHandler extends BaseHandlerRouteWithArgs
{
    private $file;
    private $filePath;

    public function Init()
    {
        if(!empty($this->args['file']))
        {
            $this->file = $this->args['file'];
            
            $this->filePath = __DIR__.'/../../../media/img/'.$this->args['file'];
        }
        else
        {
            throw new Warning(404, "LoadImage File not found", "LoadImage File not found, filePath=".$this->filePath);
        }
    }
    public function Process()
    {
        if (file_exists($this->filePath))
        {
            $this->response = $this->response->withFile($this->filePath);
        }
        else
        {
            throw new Warning(404, "LoadImage File not found", "LoadImage File not found, filePath=".$this->filePath);
        }
    }
}