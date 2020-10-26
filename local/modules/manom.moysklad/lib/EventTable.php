<?php

namespace Manom\Moysklad;


use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\Entity;

/**
 * Class EventTable
 * @package Manom\Moysklad
 */
class EventTable extends DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return "ma_moysklad_event";
    }

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return array(
            new Entity\IntegerField("id", ["primary" => true, "autocomplete" => true]),
            new Entity\StringField("href_change"),
        );
    }

    public static function add(array $data)
    {
        if (empty($data["href_change"])) {
            return false;
        }

        $existHrefChange = static::getList([
            "filter" => ["href_change" => $data["href_change"]]
        ])->fetch();
        if (!$existHrefChange) {
            parent::add($data);
        }
        return true;
    }
}