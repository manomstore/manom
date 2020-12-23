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
        $select = [
            'IBLOCK_ID',
            'ID',
            'XML_ID',
            'PROPERTY_TOP_FIELD_2',
            'PROPERTY_CML2_ARTICLE',
            'PROPERTY_model'
        ];
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
                'productArticle' => $row['PROPERTY_CML2_ARTICLE_VALUE'],
                'productModel' => $row['PROPERTY_MODEL_VALUE'],
            );
        }

        return $items;
    }

    /**
     * @throws SystemException
     * @throws Exception
     */
    public function updateProperties(): void
    {
        $products = $this->getProducts();

        $assortment = new Assortment;
        $items = $assortment->getElements();

        foreach ($items as $item) {
            $updateProperty = [];
            if (empty($item->fields->externalCode) || empty($products[$item->fields->externalCode])) {
                continue;
            }

            if (
                !empty($item->fields->code) &&
                (
                    $products[$item->fields->externalCode]['productCode'] !== $item->fields->code
                    || $products[$item->fields->externalCode]['productArticle'] !== $item->fields->code
                )
            ) {
                $updateProperty = array_merge($updateProperty, [
                    "TOP_FIELD_2"  => $item->fields->code,
                    "CML2_ARTICLE" => $item->fields->code,
                ]);
            }

            if (
                !empty($item->fields->article) &&
                $products[$item->fields->externalCode]['productModel'] !== $item->fields->article
            ) {
                $updateProperty = array_merge($updateProperty, [
                    "model"  => $item->fields->article,
                ]);
            }

            if (!empty($updateProperty)) {
                \CIBlockElement::SetPropertyValuesEx(
                    $products[$item->fields->externalCode]['id'],
                    $this->productsIblockId,
                    $updateProperty
                );
            }
        }
    }
}
