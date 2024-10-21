<?php
namespace Helpers;

use PDOException;
use HTMLPurifier;
use HTMLPurifier_Config;
use Aws\Exception\AwsException;

class StringFormatter 
{
    public static function padNumberWithZeroes(int $number) : string 
    {
        $numberStr = (string)$number;
        
        $zeroesToAdd = 12 - strlen($numberStr);
        
        for ($i = 0; $i < $zeroesToAdd; $i++) 
        {
            $numberStr = '0' . $numberStr;
        }
        
        return $numberStr;
    }

    public static function filterHtmlTags(string $input) : string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'img[src|alt|width],figure,math,semantics,mrow,annotation,div,h1,h2,h3,h4,h5,h6,br,u,blockquote,a,sub,sup,b,p,ul,ol,li,pre,code,div,table,th,td,span');
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('Attr.EnableID', true);
        $purifier = new HTMLPurifier($config);

        return str_replace("&gt;",">", $purifier->purify($input));
    }

    public static function replaceViewIdsToViewIdsLinks(string $input) : string
    {
        $pattern = '/#(\d+)/';
        
        $replacementFunction = function($matches) 
        {
            $number = $matches[1];
            return "[#{$number}](#/article/>{$number})";
        };
        
        $output = preg_replace_callback($pattern, $replacementFunction, $input);
        
        return $output;
    }

    public static function pdoExceptionToString(PDOException $e) : string 
    {
        return "PDOException: \nCode: ".strval($e->getCode())."\nMessage: ".$e->getMessage()."\nFile: ".$e->getFile()."\nLine: ".$e->getLine();
    }

    public static function awsExceptionToString(AwsException $e): string
    {
        return "AWSException: \nCode: ".$e->getAwsErrorCode()."\nMessage: ".$e->getAwsErrorMessage()."\nRequest ID: ".$e->getAwsRequestId()."\nHTTP Status Code: ".strval($e->getStatusCode());
    }
    
    public static function replaceViewIdsLinksToViewIds(string $input) : string
    {
        $pattern = '/\[#(\d+)\]\(#\/article\/>(\d+)\)/';
        
        $replacementFunction = function($matches) 
        {
            $number = $matches[1];
            return "#{$number}";
        };
        
        $output = preg_replace_callback($pattern, $replacementFunction, $input);
        
        return $output;
    }

    public static function formatSizeEnding(int $bytes, bool $binaryPrefix = true) : string
    {
        if($binaryPrefix) 
        {
            $unit = [
                'B',
                'KiB',
                'MiB',
                'GiB',
                'TiB',
                'PiB'
            ];

            if($bytes == 0) return '0 ' . $unit[0];
            return @round($bytes/pow(1024, ($i=floor(log($bytes,1024)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
        } 
        else 
        {
            $unit = [
                'B',
                'KB',
                'MB',
                'GB',
                'TB',
                'PB'
            ];

            if($bytes == 0) return '0 ' . $unit[0];
            return @round($bytes/pow(1000, ($i=floor(log($bytes,1000)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
        }
    }
}