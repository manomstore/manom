<?php

namespace Manom\Moysklad;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Exception;
use \Manom\Moysklad\Moysklad\Assortment;

/**
 * Class Product
 * @package Manom\Moysklad
 */
class Product
{
    private $productsIblockId;

    /**
     * Product constructor.
     * @throws SystemException
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('iblock')) {
            throw new SystemException('Не подключен модуль iblock');
        }

        $this->productsIblockId = Tools::getProductsIblockId();
    }

    /**
     * @param bool $byXmlId
     * @return array
     */
    public function getProducts($byXmlId = true): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->productsIblockId, '!XML_ID' => false);
        $select = array('IBLOCK_ID', 'ID', 'XML_ID', 'PROPERTY_TOP_FIELD_2');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $key = (int)$row['ID'];
            if ($byXmlId) {
                $key = $row['XML_ID'];
            }

            $items[$key] = array(
                'id' => (int)$row['ID'],
                'xmlId' => $row['XML_ID'],
                'productCode' => $row['PROPERTY_TOP_FIELD_2_VALUE'],
            );
        }

        return $items;
    }

    /**
     * @throws SystemException
     * @throws Exception
     */
    public function updateCodes(): void
    {
        $products = $this->getProducts();

        $assortment = new Assortment;
        $items = $assortment->getElements();

        foreach ($items as $item) {
            if (
                empty($item->fields->externalCode) ||
                empty($item->fields->code) ||
                empty($products[$item->fields->externalCode]) ||
                $products[$item->fields->externalCode]['productCode'] === $item->fields->code
            ) {
                continue;
            }

            \CIBlockElement::SetPropertyValuesEx(
                $products[$item->fields->externalCode]['id'],
                $this->productsIblockId,
                array('TOP_FIELD_2' => $item->fields->code)
            );
        }
    }
}
