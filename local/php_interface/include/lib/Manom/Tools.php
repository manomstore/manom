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
        $log .= $content . "\n";
        file_put_contents($logPath, date("d.m.Y H:i:s") . " " . $log);
    }
}