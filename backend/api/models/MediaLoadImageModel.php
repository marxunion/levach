<?php
namespace Api\Models;

use Core\S3Client;
use Core\Critical;

use Aws\Exception\AwsException;

use Base\BaseModel;

class MediaLoadImageModel extends BaseModel
{
    private $s3client;

    public function __construct()
    {
        $this->s3client = S3Client::getConnection();
    }

    public function getImageByCode($fileName)
    {
        try 
        {
            $result = $this->s3Client->getObject([
                'Bucket' => 'images',
                'Key'    => $fileName,
            ]);
            return $result;
        }
        catch (AwsException $e) 
        {
            throw new Critical(500, "Failed upload image", $e->getMessage());
        }
    }  
}