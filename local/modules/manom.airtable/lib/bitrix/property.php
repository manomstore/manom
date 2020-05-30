<?php

namespace Manom\Airtable\Bitrix;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;
use \Bitrix\Iblock\PropertyTable;

/**
 * Class Property
 * @package Manom\Airtable\Bitrix
 */
class Property
{
    private $iblockId;

    /**
     * Property constructor.
     * @param int $iblockId
     * @throws LoaderException
     * @throws SystemException
     */
    public function __construct($iblockId)
    {
        if (empty($iblockId)) {
            throw new SystemException('Не указан ид инфоблока');
        }

        if (!Loader::includeModule('iblock')) {
            throw new SystemException('Не подключен модуль iblock');
        }

        $this->iblockId = $iblockId;
    }

    /**
     * @return array
     */
    public function getEnumValues(): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->iblockId);
        $result = \CIBlockPropertyEnum::GetList(array(), $filter);
        while ($row = $result->Fetch()) {
            $items[$row['PROPERTY_CODE']][(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'xmlId' => $row['XML_ID'],
                'value' => $row['VALUE'],
            );
        }

        return $items;
    }

    /**
     * @param int $propertyId
     * @param string $value
     * @return int
     */
    public function addEnumValue($propertyId, $value): int
    {
        $fields = array('PROPERTY_ID' => $propertyId, 'XML_ID' => $value, 'VALUE' => $value);

        if ($id = \CIBlockPropertyEnum::Add($fields)) {
            return (int)$id;
        }

        return 0;
    }

    /**
     * @param array $codes
     * @return array
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     */
    public function getPropertiesData($codes): array
    {
        $items = array();

        $result = PropertyTable::getList(array(
            "order" => array(),
            "filter" => array('IBLOCK_ID' => $this->iblockId, 'CODE' => $codes),
            "select" => array('ID', 'CODE', 'NAME', 'PROPERTY_TYPE', 'MULTIPLE'),

        ));
        while ($row = $result->fetch()) {
            $items[$row['CODE']] = array(
                'id' => (int)$row['ID'],
                'code' => $row['CODE'],
                'name' => $row['NAME'],
                'type' => $row['PROPERTY_TYPE'],
                'multiple' => $row['MULTIPLE'] === 'Y',
            );
        }

        return $items;
    }
}
