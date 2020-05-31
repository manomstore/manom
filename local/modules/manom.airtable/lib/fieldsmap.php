<?php

namespace Manom\Airtable;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

/**
 * Class FieldsMap
 * @package Manom\Airtable
 */
class FieldsMap
{
    /**
     * FieldsMap constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getMap(): array
    {
        $fields = array(
            'NAME' => 'Название',
            'PREVIEW_TEXT' => 'Анонс',
            'DETAIL_TEXT' => 'Описание',
            'IBLOCK_SECTION_ID' => 'Подкатегория',
        );

        $properties = $this->getList();

        return array('fields' => $fields, 'properties' => $properties);
    }

    /**
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getList(): array
    {
        $items = array();

        $result = AirtablePropertiesLinkTable::getList(array(
            'order' => array('id' => 'ASC'),
            'filter' => array(),
            'select' => array('id', 'airtable', 'bitrix'),
        ));
        while ($row = $result->fetch()) {
            $items[] = array(
                'id' => (int)$row['id'],
                'airtable' => $row['airtable'],
                'bitrix' => $row['bitrix'],
            );
        }

        return $items;
    }
}
