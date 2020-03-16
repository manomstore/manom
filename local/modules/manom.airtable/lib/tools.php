<?php

namespace Manom\Airtable;

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentOutOfRangeException;

/**
 * Class Tools
 * @package Manom\Airtable
 */
class Tools
{
    private $moduleId = 'manom.airtable';

    /**
     * Tools constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getModuleId(): string
    {
        return $this->moduleId;
    }

    /**
     * @param bool $absolutePath
     * @return string
     */
    public function getModulePath($absolutePath = true): string
    {
        $modulePath = '';

        if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->moduleId)) {
            $modulePath = ($absolutePath ? $_SERVER['DOCUMENT_ROOT'] : '').'/bitrix/modules/'.$this->moduleId;
        } elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->moduleId)) {
            $modulePath = ($absolutePath ? $_SERVER['DOCUMENT_ROOT'] : '').'/local/modules/'.$this->moduleId;
        }

        return $modulePath;
    }

    /**
     * @return string
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public function getAirtableId(): string
    {
        return Option::get($this->moduleId, 'airtableId');
    }

    /**
     * @return string
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public function getApiKey(): string
    {
        return Option::get($this->moduleId, 'apiKey');
    }

    /**
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     */
    public function getSections(): array
    {
        return explode('|', Option::get($this->moduleId, 'sections'));
    }
}
