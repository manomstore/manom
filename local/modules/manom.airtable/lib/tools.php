<?php

namespace Manom\Airtable;

/**
 * Class Tools
 * @package Manom\Airtable
 */
class Tools
{
    public $MODULE_ID = 'manom.airtable';

    /**
     * Tools constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param bool $absolutePath
     * @return string
     */
    public function getModulePath($absolutePath = true): string
    {
        $modulePath = '';

        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID)) {
            $modulePath = ($absolutePath ? $_SERVER['DOCUMENT_ROOT'] : '').'/bitrix/modules/'.$this->MODULE_ID;
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID)) {
            $modulePath = ($absolutePath ? $_SERVER['DOCUMENT_ROOT'] : '').'/local/modules/'.$this->MODULE_ID;
        }

        return $modulePath;
    }
}
