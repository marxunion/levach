<?php
namespace Core;

class SystemInfo
{
    public static function getServerMemoryUsage(bool $getPercentage = true)
    {
        $memoryTotal = null;
        $memoryFree = null;
    
        if(stristr(PHP_OS, "win")) 
        {
            $cmd = "wmic ComputerSystem get TotalPhysicalMemory";
            @exec($cmd, $outputTotalPhysicalMemory);
    
            $cmd = "wmic OS get FreePhysicalMemory";
            @exec($cmd, $outputFreePhysicalMemory);
    
            if($outputTotalPhysicalMemory && $outputFreePhysicalMemory) 
            {
                foreach($outputTotalPhysicalMemory as $line) {
                    if($line && preg_match("/^[0-9]+\$/", $line)) 
                    {
                        $memoryTotal = $line;
                        break;
                    }
                }

                foreach($outputFreePhysicalMemory as $line) 
                {
                    if($line && preg_match("/^[0-9]+\$/", $line)) 
                    {
                        $memoryFree = $line;
                        $memoryFree *= 1024;
                        break;
                    }
                }
            }
        }
        else
        {
            if(is_readable("/proc/meminfo"))
            {
                $stats = @file_get_contents("/proc/meminfo");
    
                if($stats !== false) 
                {
                    $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                    $stats = explode("\n", $stats);

                    foreach($stats as $statLine) 
                    {
                        $statLineData = explode(":", trim($statLine));

                        if(count($statLineData) == 2 && trim($statLineData[0]) == "MemTotal") 
                        {
                            $memoryTotal = trim($statLineData[1]);
                            $memoryTotal = explode(" ", $memoryTotal);
                            $memoryTotal = $memoryTotal[0];
                            $memoryTotal *= 1024;
                        }

                        if(count($statLineData) == 2 && trim($statLineData[0]) == "MemFree") 
                        {
                            $memoryFree = trim($statLineData[1]);
                            $memoryFree = explode(" ", $memoryFree);
                            $memoryFree = $memoryFree[0];
                            $memoryFree *= 1024;
                        }
                    }
                }
            }
        }
    
        if(is_null($memoryTotal) || is_null($memoryFree)) 
        {
            return null;
        } 
        else 
        {
            if($getPercentage) 
            {
                return (100 - ($memoryFree * 100 / $memoryTotal));
            } 
            else 
            {
                return
                [
                    "total" => $memoryTotal,
                    "free" => $memoryFree,
                ];
            }
        }
    }

    private static function getServerLoadLinuxData()
    {
        if(is_readable("/proc/stat"))
        {
            $stats = @file_get_contents("/proc/stat");
            if($stats !== false)
            {
                $stats = preg_replace("/[[:blank:]]+/", " ", $stats);

                $stats = str_replace(["\r\n", "\n\r", "\r"], "\n", $stats);
                $stats = explode("\n", $stats);

                foreach($stats as $statLine)
                {
                    $statLineData = explode(" ", trim($statLine));
                    if((count($statLineData) >= 5) && ($statLineData[0] == "cpu"))
                    {
                        return 
                        [
                            $statLineData[1],
                            $statLineData[2],
                            $statLineData[3],
                            $statLineData[4],
                        ];
                    }
                }
            }
        }
        return null;
    }
    public static function getDiskInfo(string $path = '/') : 
    {
        $result = [];
        $result['size'] = 0;
        $result['free'] = 0;
        $result['used'] = 0;

        if(PHP_OS == 'WINNT') 
        {
            $lines = null;
            exec('wmic logicaldisk get FreeSpace^,Name^,Size /Value', $lines);
            foreach($lines as $index => $line) 
            {
                if($line != "Name=$path") 
                {
                    continue;
                }
                $result['free'] = explode('=', $lines[$index - 1])[1];
                $result['size'] = explode('=', $lines[$index + 1])[1];
                $result['used'] = $result['size'] - $result['free'];
                break;
            }
        } 
        else 
        {
            $lines = null;
            exec(sprintf('df /P %s', $path), $lines);
            foreach($lines as $index => $line) 
            {
                if($index != 1) 
                {
                    continue;
                }
                $values = preg_split('/\s{1,}/', $line);
                $result['size'] = $values[1] * 1024;
                $result['free'] = $values[3] * 1024;
                $result['used'] = $values[2] * 1024;
                break;
            }
        }
        return $result;
    }

    public static function getServerLoad()
    {
        $load = null;
        if(stristr(PHP_OS, "win"))
        {
            $cmd = "wmic cpu get loadpercentage /all";
            @exec($cmd, $output);
            if($output)
            {
                foreach($output as $line)
                {
                    if($line && preg_match("/^[0-9]+\$/", $line))
                    {
                        $load = $line;
                        break;
                    }
                }
            }
        }
        else
        {
            if(is_readable("/proc/stat"))
            {
                $statData1 = self::getServerLoadLinuxData();
                sleep(1);
                $statData2 = self::getServerLoadLinuxData();
                if((!is_null($statData1)) && (!is_null($statData2)))
                {
                    $statData2[0] -= $statData1[0];
                    $statData2[1] -= $statData1[1];
                    $statData2[2] -= $statData1[2];
                    $statData2[3] -= $statData1[3];

                    $cpuTime = $statData2[0] + $statData2[1] + $statData2[2] + $statData2[3];

                    $load = 100 - ($statData2[3] * 100 / $cpuTime);
                }
            }
        }
        return $load;
    }

}