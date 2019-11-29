<?php

namespace Sneakerhead\Nextjs\Api;

use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;
use \Bitrix\Iblock\IblockTable;

/**
 * Class Offer
 * @package Sneakerhead\Nextjs\Api
 */
class Offer
{
    private $iblockId;
    private $sizesIblockId;

    /**
     * Offer constructor.
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function __construct()
    {
        if (!Loader::includeModule('iblock')) {
            throw new SystemException('Не подключен модуль iblock');
        }

        $this->iblockId = $this->getIblockId();
        $this->sizesIblockId = $this->getSizesIblockId();
    }

    /**
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getIblockId()
    {
        $iblockId = 3;

        $result = IblockTable::getList(array(
            'filter' => array('CODE' => 'offers'),
            'select' => array('ID'),
        ));
        if ($row = $result->fetch()) {
            $iblockId = $row['ID'];
        }

        return $iblockId;
    }

    /**
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getSizesIblockId()
    {
        $iblockId = 17;

        $result = IblockTable::getList(array(
            'filter' => array('CODE' => 'size'),
            'select' => array('ID'),
        ));
        if ($row = $result->fetch()) {
            $iblockId = $row['ID'];
        }

        return $iblockId;
    }

    /**
     * @param $id
     * @return array
     */
    public function getItemsById($id)
    {
        $items = array();

        $sizesId = array();
        $properties = array('CML2_LINK', 'SIZES_SHOES');
        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $id);
        $select = array('IBLOCK_ID', 'ID', 'ACTIVE', 'CATALOG_QUANTITY', 'CATALOG_AVAILABLE');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($rowResult = $result->GetNextElement(true, false)) {
            $row = $rowResult->GetFields();
            foreach ($properties as $code) {
                $row['PROPERTIES'][$code] = $rowResult->GetProperty($code);
            }

            if (
                !empty($row['PROPERTIES']['SIZES_SHOES']['VALUE']) &&
                !in_array((int)$row['PROPERTIES']['SIZES_SHOES']['VALUE'], $sizesId, true)
            ) {
                $sizesId[] = (int)$row['PROPERTIES']['SIZES_SHOES']['VALUE'];
            }

            $items[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'productId' => (int)$row['PROPERTIES']['CML2_LINK']['VALUE'],
                'active' => $row['ACTIVE'] === 'Y',
                'available' => $row['CATALOG_AVAILABLE'] === 'Y',
                'quantity' => (int)$row['CATALOG_QUANTITY'],
                'size' => array(
                    'id' => (int)$row['PROPERTIES']['SIZES_SHOES']['VALUE'],
                    'value' => '',
                ),
            );
        }

        $sizes = $this->getSizes($sizesId);

        foreach ($items as $itemId => $item) {
            if (!empty($sizes[$item['size']['id']])) {
                $items[$itemId]['size']['value'] = $sizes[$item['size']['id']]['name'];
            }
        }

        return $items;
    }

    /**
     * @param array $sizesId
     * @return array
     */
    public function getSizes($sizesId)
    {
        $sizes = array();

        $properties = array();
        $filter = array('IBLOCK_ID' => $this->sizesIblockId, 'ID' => $sizesId);
        $select = array('IBLOCK_ID', 'ID', 'NAME');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($rowResult = $result->GetNextElement(true, false)) {
            $row = $rowResult->GetFields();
            foreach ($properties as $code) {
                $row['PROPERTIES'][$code] = $rowResult->GetProperty($code);
            }

            $sizes[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
            );
        }

        return $sizes;
    }
}
