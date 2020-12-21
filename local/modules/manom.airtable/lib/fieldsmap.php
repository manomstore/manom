<?php

namespace Manom\Airtable;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Exception;

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
            'IBLOCK_SECTION' => 'Тест подкатегория',
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

    /**
     * @param array $fields
     * @return bool
     * @throws Exception
     */
    public function addLink($fields): bool
    {
        if (empty($fields['airtable']) || empty($fields['bitrix'])) {
            return false;
        }

        $result = AirtablePropertiesLinkTable::add(array(
            'airtable' => $fields['airtable'],
            'bitrix' => $fields['bitrix'],
        ));

        return $result->isSuccess();
    }

    /**
     * @param array $fields
     * @return bool
     * @throws Exception
     */
    public function updateLink($fields): bool
    {
        if (empty($fields['id']) || empty($fields['airtable']) || empty($fields['bitrix'])) {
            return false;
        }

        $result = AirtablePropertiesLinkTable::update($fields['id'], array(
            'airtable' => $fields['airtable'],
            'bitrix' => $fields['bitrix'],
        ));

        return $result->isSuccess();
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function deleteLink($id): bool
    {
        if (empty($id)) {
            return false;
        }

        $result = AirtablePropertiesLinkTable::delete($id);

        return $result->isSuccess();
    }
}
