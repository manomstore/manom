<?php

namespace Manom\Airtable\Bitrix;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;
use \Bitrix\Iblock\PropertyTable;
use Manom\Airtable\Import;

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

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getAllPropertiesData(): array
    {
        $items = array();

        $result = PropertyTable::getList(array(
            "order" => array('SORT' => 'ASC', 'ID' => 'ASC'),
            "filter" => array('IBLOCK_ID' => $this->iblockId),
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

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function createProperty($airtableProperty, $airtableValue, Import $import): array
    {
        $oCIBlockProperty = new \CIBlockProperty();

        $isHtmlType = strripos($airtableValue, "\n");
        $prepareProperty = [
            'IBLOCK_ID' => $this->iblockId,
            'NAME' => $airtableProperty,
            'CODE' => $this->createAtPropertyCode($airtableProperty),
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => $isHtmlType ? "HTML" : null,
            'SORT' => $isHtmlType ? 700 : 1100
        ];

        if (empty($prepareProperty["CODE"])) {
            return [];
        }

        $aFilter = array('IBLOCK_ID' => $this->iblockId, 'CODE' => $prepareProperty['CODE']);
        $oDbRes = \CIBlockProperty::GetList(array(), $aFilter);
        if ($aDbRes = $oDbRes->fetch()) {
            $import->addError('Не удалось создать ' . $prepareProperty['NAME'] .
                '. Свойство с кодом "' . $prepareProperty['CODE'] . '" уже существует.');
        } elseif ($propertyId = $oCIBlockProperty->Add($prepareProperty)) {
            $prepareProperty["ID"] = $propertyId;
            return $prepareProperty;
        } else {
            $import->addError('Не удалось создать свойство "' .
                $prepareProperty['NAME'] .
                '". Error: ' . $oCIBlockProperty->LAST_ERROR);
        }
        return [];
    }

    /**
     * @return string
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function createAtPropertyCode($name): string
    {
        if (empty($name)) {
            return false;
        }

        $code = \Cutil::translit($name, "ru");
        if (strlen($code) > 20) {
            $code = substr($code, 0, 10) . substr($code, -10, 10) . strlen($code);
        }
        return "at_" . $code;
    }
}
