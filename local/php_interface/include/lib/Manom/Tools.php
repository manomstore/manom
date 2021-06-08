<?php

namespace Manom;


class Tools
{
    /**
     * @param string $content
     * @param string $logName
     */
    public static function addToLog(string $content, string $logName): void
    {
        if (empty($logName)) {
            return;
        }

        $logPath = $_SERVER["DOCUMENT_ROOT"] . "/logs/{$logName}.log";
        $log = file_get_contents($logPath);
        if ($logName === "ms_dimensions") {
            $log = "";
        }
        $log .= date("d.m.Y H:i:s") . "\n" . $content . "\n\n";
        file_put_contents($logPath, $log);
    }

    /**
     * @param \Exception $e
     * @param string $logName
     * @param string $moreInfo
     */
    public static function errorToLog(\Exception $e, string $logName, string $moreInfo = ""): void
    {
        $logText = "Error " . $e->getMessage() . ", Path:" . $e->getFile() . ":" . $e->getLine();
        if ($moreInfo) {
            $logText .= "\nMore info: " . $moreInfo;
        }
        static::addToLog($logText, $logName);
    }
}