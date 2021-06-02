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
            new Entity\StringField("entity"),
            new Entity\StringField("type"),
            new Entity\StringField("href_entity"),
        );
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public static function add(array $data): bool
    {
        if (
            empty($data["href_entity"])
            || empty($data["type"])
            || empty($data["entity"])
        ) {
            return false;
        }

        $existEntry = static::getList([
            "filter" => [
                "href_entity" => $data["href_entity"],
                "entity"      => $data["entity"],
                "type"        => $data["type"],
            ]
        ])->fetch();

        if (!$existEntry) {
            $result = parent::add($data);

            if ($result->isSuccess()) {
                Agent::setActiveAgent(true, "handleEvents");
            }
        }
        return true;
    }
}