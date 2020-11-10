<?php

namespace Manom\Airtable\Bitrix;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;

/**
 * Class Element
 * @package Manom\Airtable\Bitrix
 */
class Element
{
    private $iblockId;
    private $bitrixElement;

    /**
     * Element constructor.
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

        $this->bitrixElement = new \CIBlockElement;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, '!XML_ID' => false);
        $select = array('IBLOCK_ID', 'IBLOCK_SECTION_ID', 'ID', 'XML_ID');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $items[$row['XML_ID']] = array(
                'id' => (int)$row['ID'],
                'sectionId' => (int)$row['IBLOCK_SECTION_ID'],
                'xmlId' => $row['XML_ID'],
            );
        }

        return $items;
    }

    /**
     * @param array $fields
     * @return bool
     */
    public function update($fields): bool
    {
        $result = false;

        if ($this->bitrixElement->Update($fields['ID'], $fields)) {
            $result = true;

            if (!empty($fields['PROPERTIES'])) {
                foreach ($fields['PROPERTIES'] as $code => $value) {
                    \CIBlockElement::SetPropertyValuesEx($fields['ID'], $this->iblockId, array($code => $value));
                }
            }
        } else {
            echo '<pre>'.print_r($this->bitrixElement->LAST_ERROR, true).'</pre>';
        }

        return $result;
    }
}
