<?php
namespace Api\Models;

use Core\S3Client;
use Core\Critical;
use Core\Settings;

use Aws\Exception\AwsException;

use Base\BaseModel;

class MediaLoadImageModel extends BaseModel
{
    private $s3Client;

    public function __construct()
    {
        $this->s3Client = S3Client::getConnection();
    }

    public function getImageByCode($fileName)
    {
        try 
        {
            $result = $this->s3Client->getObject([
                'Bucket' => Settings::getSetting("S3_IMAGES_BUCKET_NAME"),
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