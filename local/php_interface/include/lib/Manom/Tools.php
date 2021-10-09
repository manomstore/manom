<?php

namespace Manom;


use Bitrix\Main\Web\HttpClient;

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

    /**
     * @param $token
     * @return bool
     */
    public static function checkRecaptcha($token): bool
    {
        $client = new HttpClient();

        $response = $client->post(
            "https://www.google.com/recaptcha/api/siteverify",
            [
                "secret"   => "6Leh9rQcAAAAALHFw91e5_YjMm4Ls4nECxwTbMd5",
                "response" => $token,
                "remoteip" => $_SERVER["REMOTE_ADDR"],
            ]
        );

        $arResponse = json_decode($response, true);

        return (bool)$arResponse["success"];
    }
}