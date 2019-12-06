<?php

namespace Manom\Nextjs\Api;

use Bitrix\Main\Context;
use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;
use \Bitrix\Iblock\IblockTable;

/**
 * Class Product
 * @package Manom\Nextjs\Api
 */
class Product
{
    private $iblockId;
    private $brandsIblockId;

    /**
     * Product constructor.
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
        $this->brandsIblockId = $this->getBrandsIblockId();
    }

    /**
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getIblockId()
    {
        $iblockId = 2;

        $result = IblockTable::getList(array(
            'filter' => array('CODE' => 'products'),
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
    public function getBrandsIblockId()
    {
        $iblockId = 6;

        $result = IblockTable::getList(array(
            'filter' => array('CODE' => 'brands'),
            'select' => array('ID'),
        ));
        if ($row = $result->fetch()) {
            $iblockId = $row['ID'];
        }

        return $iblockId;
    }

    /**
     * @param array $id
     * @return array
     * @throws \Bitrix\Main\SystemException
     */
    public function getItemsById($id)
    {
        $locationId = Context::getCurrent()->getRequest()->get("locationId");
        $items = array();
        $sectionsId = array();
        $brandsId = array();

        $properties = array('BRAND', 'SALE', 'SPECIAL_PRICE', 'SPECIAL_DATE','STOCK_STATUS');
        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $id);
        $select = array(
            'IBLOCK_ID',
            'IBLOCK_SECTION_ID',
            'ID',
            'XML_ID',
            'CODE',
            'NAME',
            'DETAIL_PAGE_URL',
            'PREVIEW_PICTURE',
            'ACTIVE',
            'CATALOG_QUANTITY',
            'CATALOG_AVAILABLE',
        );
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($rowResult = $result->GetNextElement(true, false)) {
            $row = $rowResult->GetFields();
            foreach ($properties as $code) {
                $row['PROPERTIES'][$code] = $rowResult->GetProperty($code);
            }

            if (
                !empty($row['IBLOCK_SECTION_ID']) &&
                !in_array((int)$row['IBLOCK_SECTION_ID'], $sectionsId, true)
            ) {
                $sectionsId[] = (int)$row['IBLOCK_SECTION_ID'];
            }

            if (
                !empty($row['PROPERTIES']['BRAND']['VALUE']) &&
                !in_array((int)$row['PROPERTIES']['BRAND']['VALUE'], $brandsId, true)
            ) {
                $brandsId[] = (int)$row['PROPERTIES']['BRAND']['VALUE'];
            }

            $items[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'xmlId' => $row['XML_ID'],
                'code' => $row['CODE'],
                'name' => $row['NAME'],
                'url' => $row['DETAIL_PAGE_URL'],
                'imageId' => $row['PREVIEW_PICTURE'],
                'section' => array(
                    'id' => (int)$row['IBLOCK_SECTION_ID'],
                    'name' => '',
                ),
                'brand' => array(
                    'id' => (int)$row['PROPERTIES']['BRAND']['VALUE'],
                    'name' => '',
                    'url' => '',
                ),
                'sale' => !empty($row['PROPERTIES']['SALE']['VALUE']),
                'specialPrice' => (int)$row['PROPERTIES']['SPECIAL_PRICE']['VALUE'],
                'specialPriceTime' => strtotime($row['PROPERTIES']['SPECIAL_DATE']['VALUE']),
                'active' => $row['ACTIVE'] === 'Y',
                'available' => $row['CATALOG_AVAILABLE'] === 'Y',
                'quantity' => (int)$row['CATALOG_QUANTITY'],
                'stockStatus' => (int)$row['PROPERTIES']['STOCK_STATUS']["VALUE"],
            );
        }

        $sections = $this->getSections($sectionsId);
        $brands = $this->getBrands($brandsId);

        foreach ($items as $itemId => $item) {
            if (!empty($sections[$item['section']['id']])) {
                $items[$itemId]['section']['name'] = $sections[$item['section']['id']]['name'];
                $items[$itemId]['section']['xml_id'] = $sections[$item['section']['id']]['xml_id'];
            }

            if (!empty($brands[$item['brand']['id']])) {
                $items[$itemId]['brand']['name'] = $brands[$item['brand']['id']]['name'];
                $items[$itemId]['brand']['url'] = $brands[$item['brand']['id']]['url'];
            }
        }

        return $items;
    }

    /**
     * @param array $id
     * @return array
     */
    public function getItemsSectionId($id)
    {
        $itemsSectionId = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $id);
        $select = array('IBLOCK_ID', 'IBLOCK_SECTION_ID', 'ID');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $itemsSectionId[(int)$row['ID']] = (int)$row['IBLOCK_SECTION_ID'];
        }

        return $itemsSectionId;
    }

    /**
     * @param array $sectionsId
     * @return array
     */
    public function getSections($sectionsId)
    {
        $sections = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $sectionsId);
        $select = array('IBLOCK_ID', 'ID', 'NAME',"XML_ID");
        $result = \CIBlockSection::GetList(array(), $filter, false, $select);
        if ($row = $result->fetch()) {
            $sections[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'xml_id' => $row['XML_ID'],
            );
        }

        return $sections;
    }

    /**
     * @param int $sectionId
     * @return array
     */
    public function getNavChain($sectionId)
    {
        $navChain = array();

        $result = \CIBlockSection::GetNavChain($this->iblockId, $sectionId);
        while ($row = $result->GetNext()) {
            $navChain[] = array(
                'id' => (int)$row['ID'],
                'sectionId' => (int)$row['IBLOCK_SECTION_ID'],
                'code' => $row['CODE'],
                'name' => $row['NAME'],
                'url' => $row['SECTION_PAGE_URL'],
                'sort' => (int)$row['SORT'],
                'active' => (int)$row['ACTIVE'],
                'depthLevel' => (int)$row['DEPTH_LEVEL'],
            );
        }

        return $navChain;
    }

    /**
     * @param array $brandsId
     * @return array
     */
    public function getBrands($brandsId)
    {
        $brands = array();

        $properties = array();
        $filter = array('IBLOCK_ID' => $this->brandsIblockId, 'ID' => $brandsId);
        $select = array(
            'IBLOCK_ID',
            'IBLOCK_SECTION_ID',
            'ID',
            'XML_ID',
            'CODE',
            'NAME',
            'DETAIL_PAGE_URL',
        );
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($rowResult = $result->GetNextElement(true, false)) {
            $row = $rowResult->GetFields();
            foreach ($properties as $code) {
                $row['PROPERTIES'][$code] = $rowResult->GetProperty($code);
            }

            $brands[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'sectionId' => (int)$row['IBLOCK_SECTION_ID'],
                'xmlId' => $row['XML_ID'],
                'code' => $row['CODE'],
                'name' => $row['NAME'],
                'url' => $row['DETAIL_PAGE_URL'],
            );
        }

        return $brands;
    }
}
