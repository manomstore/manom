<?php

namespace Manom\Moysklad;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Exception;
use \Manom\Moysklad\Moysklad\Assortment;
use Manom\Moysklad\Moysklad\Attribute;

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

                (new \CIBlockElement)->UpdateSearch($products[$item->fields->externalCode]['id'], true);
            }
        }
    }

    /**
     * @throws SystemException
     */
    public function updateYMarketFields(): void
    {
        $fields = [
            "brand"        => "Изготовитель",
            "manufacturer" => "Страна производитель",
            "url"          => "URL",
            "width"        => "Ширина",
            "height"       => "Высота",
            "weight"       => "Вес",
            "package"      => "Упаковка",
        ];

        $attribute = new Attribute($fields);
        $product = new Assortment();

        $msProducts = $product->getElements();

        $result = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => \Helper::CATALOG_IB_ID,
            ],
            false,
            false,
            [
                "IBLOCK_ID",
                "ID",
                "PROPERTY_brand",
                "PROPERTY_country_manufacture",
                "PROPERTY_at_strana_proizvoditel",
                "DETAIL_PAGE_URL",
                "XML_ID",
            ]
        );

        $products = [];
        while ($row = $result->GetNext()) {
            $manufacturer = $row["PROPERTY_COUNTRY_MANUFACTURE_VALUE"];
            if (empty($manufacturer)) {
                $manufacturer = $row["PROPERTY_AT_STRANA_PROIZVODITEL_VALUE"];
            }

            $products[$row["XML_ID"]] = [
                "id"           => $row["ID"],
                "brand"        => trim($row["PROPERTY_BRAND_VALUE"]),
                "manufacturer" => trim($manufacturer),
                "url"          => "https://manom.ru" . $row["DETAIL_PAGE_URL"],
            ];
        }

        $msProducts->each(function ($product) use ($products, $attribute) {
            $attributes = $attribute->getForUpdate($product, $products[$product->fields->externalCode]);
            if (!empty($attributes)) {
                $url = "https://online.moysklad.ru/api/remap/1.2/entity/product/" . $product->fields->id;
                $body = [
                    "attributes" => $attributes,
                ];

                Tools::sendRequest($url, "PUT", json_encode($body));
            }
        });
    }

    /**
     * @throws SystemException
     */
    public static function transferDimensions(): void
    {
        $fields = [
            "weight"  => "Вес",
            "package" => "Упаковка",
        ];

        $product = new Assortment();
        $attribute = new Attribute($fields);
        $msProducts = $product->getElements();
        $msProducts = $msProducts->filter(function ($product) {
            return !empty($product->fields->description);
        });

        $msProducts = $msProducts->filter(function ($product) {
            return $product->fields->externalCode === "M3C441jhhMHExOIqMipPE1";
        });

        $msProducts->each(function ($product) use ($attribute) {
            $attributes = [];
            $attribute->addDimensionsFromDescription($attributes, $product);

            if (!empty($attributes)) {
                $url = "https://online.moysklad.ru/api/remap/1.2/entity/product/" . $product->fields->id;
                $body = [
                    "attributes" => $attributes,
                ];

                Tools::sendRequest($url, "PUT", json_encode($body));
            }
        });
    }
}