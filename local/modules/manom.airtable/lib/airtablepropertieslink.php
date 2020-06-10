<?php

namespace Manom\Airtable;

use \Bitrix\Main\Entity;
use \Bitrix\Main\SystemException;

/**
 * Class AirtablePropertiesLinkTable
 * @package Manom\Airtable
 */
class AirtablePropertiesLinkTable extends Entity\DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return "ma_airtable_properties_link";
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return array(
            new Entity\IntegerField("id", array("primary" => true, "autocomplete" => true)),
            new Entity\StringField("airtable"),
            new Entity\StringField("bitrix"),
        );
    }
}
