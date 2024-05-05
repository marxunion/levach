<?php
namespace Api\Handlers;

use Core\Settings;
use Core\SystemInfo;

use Helpers\StringFormatter;

use Base\BaseHandlerRoute;

use Api\Handlers\AdminStatusHandler;

class StatusHandler extends BaseHandlerRoute
{
    public function Init()
    {
        if(Settings::getSetting('DEBUG_MODE') || AdminStatusHandler::isAdmin())
        {
            $memUsage = SystemInfo::getServerMemoryUsage(false);
            $cpuLoad = SystemInfo::getServerLoad();
            $diskInfo = SystemInfo::getDiskInfo();
            
            $this->response = $this->response->withStatus(200)->withJson([
                "status" => true,
                "OS" => PHP_OS,
                "OS FullInfo" => php_uname(),
                "Disk Usage" => StringFormatter::formatSizeEnding(floatval($diskInfo['used']))." / ".StringFormatter::formatSizeEnding(floatval($diskInfo['size']))." (".round(($diskInfo['used']/$diskInfo['size'])*100, 1)."%)",
                "Memory Usage" => StringFormatter::formatSizeEnding($memUsage["total"] - $memUsage["free"])." / ".StringFormatter::formatSizeEnding($memUsage["total"])." (".round(SystemInfo::getServerMemoryUsage(true), 1)."%)",
                "CPU Usage" => round($cpuLoad, 1)."%",
                "Request time" => ((time() - $_SERVER['REQUEST_TIME_FLOAT'])*1000).'ms',
                "Current date" => date('Y-m-d H:i:s'),

                "Web Server Info" => [
                    "Name/Version" => $_SERVER['SERVER_SOFTWARE'],
                    "Server IP" => $_SERVER['SERVER_ADDR'],
                    "Server Protocol" => $_SERVER['SERVER_PROTOCOL'],
                    'Gateway Interface' => $_SERVER['GATEWAY_INTERFACE']
                ],
                "PHP Info" => [
                    "PHP Version" => phpversion(),
                    "Zend Engine Version" => zend_version(),
                    "PHP Sapi Name" => php_sapi_name()
                ]
            ]);
        }
        else
        {
            $this->response = $this->response->withStatus(200)->withJson([
                "status" => true,
                "Request time" => ((time() - $_SERVER['REQUEST_TIME_FLOAT'])*1000).'ms',
                "Current date" => date('Y-m-d H:i:s'),
            ]);
        }
    }
}