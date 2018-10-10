<?php
/**
 * log.php file provides a class for logs
 * @author Mukarram Ishaq
 */
namespace Core;
class Log
{

    public static function debug($entry, $file=__FILE__, $line = __LINE__)
    {
        if (is_array($entry)) {
            $entry = print_r($entry, true);
        } else if (is_object($entry)) {
            $entry = var_export($entry,true);
        }
        $date = date("Y-m-d h:m:s");
        $dbgfile = $GLOBALS['config']['log_debug_file'];
        $message = "[{$date}] [{$file}] [{$line}] $entry".PHP_EOL;
        error_log($message,3, $dbgfile);
    }

}
