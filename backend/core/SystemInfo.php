<?php
namespace Core;


class SystemInfo
{
    public static function getServerMemoryUsage($getPercentage = true)
    {
        $memoryTotal = null;
        $memoryFree = null;
    
        if (stristr(PHP_OS, "win")) 
        {
            // Get total physical memory (this is in bytes)
            $cmd = "wmic ComputerSystem get TotalPhysicalMemory";
            @exec($cmd, $outputTotalPhysicalMemory);
    
            // Get free physical memory (this is in kibibytes!)
            $cmd = "wmic OS get FreePhysicalMemory";
            @exec($cmd, $outputFreePhysicalMemory);
    
            if ($outputTotalPhysicalMemory && $outputFreePhysicalMemory) 
            {
                // Find total value
                foreach ($outputTotalPhysicalMemory as $line) {
                    if ($line && preg_match("/^[0-9]+\$/", $line)) 
                    {
                        $memoryTotal = $line;
                        break;
                    }
                }
    
                // Find free value
                foreach ($outputFreePhysicalMemory as $line) 
                {
                    if ($line && preg_match("/^[0-9]+\$/", $line)) 
                    {
                        $memoryFree = $line;
                        $memoryFree *= 1024;  // convert from kibibytes to bytes
                        break;
                    }
                }
            }
        }
        else
        {
            if (is_readable("/proc/meminfo"))
            {
                $stats = @file_get_contents("/proc/meminfo");
    
                if ($stats !== false) 
                {
                    // Separate lines
                    $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                    $stats = explode("\n", $stats);
    
                    // Separate values and find correct lines for total and free mem
                    foreach ($stats as $statLine) 
                    {
                        $statLineData = explode(":", trim($statLine));
    
                        // Total memory
                        if (count($statLineData) == 2 && trim($statLineData[0]) == "MemTotal") 
                        {
                            $memoryTotal = trim($statLineData[1]);
                            $memoryTotal = explode(" ", $memoryTotal);
                            $memoryTotal = $memoryTotal[0];
                            $memoryTotal *= 1024;  // convert from kibibytes to bytes
                        }
    
                        // Free memory
                        if (count($statLineData) == 2 && trim($statLineData[0]) == "MemFree") 
                        {
                            $memoryFree = trim($statLineData[1]);
                            $memoryFree = explode(" ", $memoryFree);
                            $memoryFree = $memoryFree[0];
                            $memoryFree *= 1024;  // convert from kibibytes to bytes
                        }
                    }
                }
            }
        }
    
        if (is_null($memoryTotal) || is_null($memoryFree)) 
        {
            return null;
        } 
        else 
        {
            if ($getPercentage) 
            {
                return (100 - ($memoryFree * 100 / $memoryTotal));
            } 
            else 
            {
                return array(
                    "total" => $memoryTotal,
                    "free" => $memoryFree,
                );
            }
        }
    }

    private static function getServerLoadLinuxData()
    {
        if (is_readable("/proc/stat"))
        {
            $stats = @file_get_contents("/proc/stat");
            if ($stats !== false)
            {
                // Remove double spaces to make it easier to extract values with explode()
                $stats = preg_replace("/[[:blank:]]+/", " ", $stats);
                // Separate lines
                $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                $stats = explode("\n", $stats);
                // Separate values and find line for main CPU load
                foreach ($stats as $statLine)
                {
                    $statLineData = explode(" ", trim($statLine));
                    // Found!
                    if((count($statLineData) >= 5) && ($statLineData[0] == "cpu"))
                    {
                        return array(
                            $statLineData[1],
                            $statLineData[2],
                            $statLineData[3],
                            $statLineData[4],
                        );
                    }
                }
            }
        }
        return null;
    }
    public static function getDiskInfo($path = '/')
    {
        $result = array();
        $result['size'] = 0;
        $result['free'] = 0;
        $result['used'] = 0;

        if (PHP_OS == 'WINNT') {
            $lines = null;
            exec('wmic logicaldisk get FreeSpace^,Name^,Size /Value', $lines);
            foreach ($lines as $index => $line) {
                if ($line != "Name=$path") {
                    continue;
                }
                $result['free'] = explode('=', $lines[$index - 1])[1];
                $result['size'] = explode('=', $lines[$index + 1])[1];
                $result['used'] = $result['size'] - $result['free'];
                break;
            }
        } else {
            $lines = null;
            exec(sprintf('df /P %s', $path), $lines);
            foreach ($lines as $index => $line) {
                if ($index != 1) {
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
        if (stristr(PHP_OS, "win"))
        {
            $cmd = "wmic cpu get loadpercentage /all";
            @exec($cmd, $output);
            if ($output)
            {
                foreach ($output as $line)
                {
                    if ($line && preg_match("/^[0-9]+\$/", $line))
                    {
                        $load = $line;
                        break;
                    }
                }
            }
        }
        else
        {
            if (is_readable("/proc/stat"))
            {
                $statData1 = self::getServerLoadLinuxData();
                sleep(1);
                $statData2 = self::getServerLoadLinuxData();
                if((!is_null($statData1)) && (!is_null($statData2)))
                {
                    // Get difference
                    $statData2[0] -= $statData1[0];
                    $statData2[1] -= $statData1[1];
                    $statData2[2] -= $statData1[2];
                    $statData2[3] -= $statData1[3];
                    // Sum up the 4 values for User, Nice, System and Idle and calculate
                    // the percentage of idle time (which is part of the 4 values!)
                    $cpuTime = $statData2[0] + $statData2[1] + $statData2[2] + $statData2[3];
                    // Invert percentage to get CPU time, not idle time
                    $load = 100 - ($statData2[3] * 100 / $cpuTime);
                }
            }
        }
        return $load;
    }

}