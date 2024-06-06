<?php
namespace Api\Models;

use Core\S3Client;

use Aws\Exception\AwsException;

use Core\Error;

use Base\BaseModel;

class MediaUploadImageModel extends BaseModel
{
    private $s3Client;

    public function __construct()
    {
        $this->s3Client = S3Client::getConnection();
    }
    
    public function uploadImage(string $newFileName, string $image)
    {
        try 
        {
            $result = $this->s3Client->putObject([
                'Bucket' => 'images',
                'Key'    => $newFileName,
                'Body'   => fopen($image, 'r'),
                'ACL'    => 'public-read',
            ]);
        } 
        catch (AwsException $e) 
        {
            
            throw new Critical(500, "Failed upload image", $e->getMessage());
        }
    }  
}