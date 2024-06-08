<?php
namespace Helpers;

use HTMLPurifier;
use HTMLPurifier_Config;

class StringFormatter 
{
    public static function filterHtmlTags($input)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'img[src|alt|width],figure,math,semantics,mrow,annotation,h1,h2,h3,h4,h5,h6,br,u,blockquote,a,sub,sup,b,p,ul,ol,li,pre,code,div,table,th,td,span');
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        $config->set('Attr.EnableID', true);
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($input);
    }

    public static function replaceViewIdsToViewIdsLinks($input) 
    {
        $pattern = '/#(\d+)/';
        
        $replacementFunction = function($matches) {
            $number = $matches[1];
            return "[#{$number}](#/article/>{$number})";
        };
        
        $output = preg_replace_callback($pattern, $replacementFunction, $input);
        
        return $output;
    }
    
    public static function replaceViewIdsLinksToViewIds($input) 
    {
        $pattern = '/\[#(\d+)\]\(#\/article\/>(\d+)\)/';
        
        $replacementFunction = function($matches) {
            $number = $matches[1];
            return "#{$number}";
        };
        
        $output = preg_replace_callback($pattern, $replacementFunction, $input);
        
        return $output;
    }

    public static function formatSizeEnding($bytes, $binaryPrefix = true)
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