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

                (new \CIBlockElement)->UpdateSearch($products[$item->fields->externalCode]['id'], true);
            }
        }
    }

    /**
     * @param String $url
     * @return array|null
     */
    public function sendRequest(String $url, $method = "GET", $body = false)
    {
        $client = new \GuzzleHttp\Client();

        $authData = \Manom\Moysklad\Tools::getAuthData();

        $options = [
            'auth'    => [$authData["login"], $authData["password"]],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        if ($body) {
            $options["body"] = $body;
        }
        return json_decode($client->request($method, $url, $options)->getBody()->getContents(), true);
    }

    /**
     *
     */
    public function updateFields()
    {
        $fields = [
            "brand"        => "Изготовитель",
            "manufacturer" => "Страна производитель",
            "category"     => "Категория",
            "url"          => "URL",
        ];
        $attributesData = [];
        $errors = [];
        $totalProducts = $updatedProducts = 0;
        try {
            $attributes = $this->sendRequest("https://online.moysklad.ru/api/remap/1.2/entity/product/metadata/attributes");

            array_walk($attributes["rows"], function ($attribute) use (&$attributesData, $fields) {
                $key = array_search($attribute["name"], $fields);
                if ($key !== false) {
                    $attribute["code"] = $key;
                    $attributesData[$key] = $attribute;
                }
            });

            if (!empty(array_diff(array_keys($attributesData), array_keys($fields)))) {
                $errors[] = "Недостаточно атрибутов";
            }
            $product = new \Manom\Moysklad\Moysklad\Product();
            $msProducts = $product->getElements();

            $result = \CIBlockElement::GetList(
                [],
                [
                    "IBLOCK_ID"             => \Helper::CATALOG_IB_ID,
                    "!PROPERTY_YM_CATEGORY" => false,
                ],
                false,
                false,
                [
                    "IBLOCK_ID",
                    "ID",
                    "PROPERTY_YM_CATEGORY",
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
                    "category"     => trim($row["PROPERTY_YM_CATEGORY_VALUE"]),
                    "url"          => "https://manom.ru" . $row["DETAIL_PAGE_URL"],
                ];
            }
            $totalProducts = count($products);

            $msProducts = $msProducts->filter(function ($product) use ($products) {
                return in_array($product->fields->externalCode, array_keys($products));
            });
        } catch (\Exception $e) {
            $errors[] = $e->getMessage() . "(" . $e->getFile() . ":" . $e->getLine() . ")";
        }
        $msProducts->each(function ($product) use ($products, $attributesData, &$errors, &$updatedProducts) {
            $attributes = [];
            $productData = $products[$product->fields->externalCode];
            foreach ($attributesData as $code => $attributeData) {
                if (empty($productData[$code])) {
                    $errors[] = "У товара " . $product->fields->externalCode . " пустое свойство - {$attributeData["name"]}";
                    continue;
                }

                $attributes[] = [
                    "meta"  => $attributeData["meta"],
                    "value" => $productData[$code],
                ];
            };
            try {
                $url = "https://online.moysklad.ru/api/remap/1.2/entity/product/" . $product->fields->id;
                $body = [
                    "attributes" => $attributes,
                ];

                $body = json_encode($body);
                $result = $this->sendRequest($url, "PUT", $body);
                if ($result["externalCode"] === $product->fields->externalCode) {
                    $updatedProducts = $updatedProducts + 1;
                }
            } catch (\Exception $e) {
                $errors[] = $e->getMessage() . "(" . $e->getFile() . ":" . $e->getLine() . ")";
            }
        });

        echo "Обновлено {$updatedProducts} товаров из {$totalProducts}<br>";
        echo implode($errors, "<br>");
    }

    /**
     *
     */
    public function importCategoriesFromFile()
    {
        $data = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/upload/categories.csv");
        $rows = explode("\n", $data);
        $productsArticles = [];
        $products = [];
        $updatedCnt = $allCnt = 0;
        foreach ($rows as $row) {
            list($article, $category) = explode(";", $row);
            if (empty($article) || empty($category)) {
                continue;
            }
            $productsArticles[$article] = $category;
        }

        $allCnt = count($productsArticles);

        if (!empty($productsArticles)) {
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
                    "PROPERTY_CML2_ARTICLE",
                    "PROPERTY_at_artikul",
                ],
                );

            while ($row = $result->GetNext()) {
                $article = "";
                if (in_array($row["PROPERTY_CML2_ARTICLE_VALUE"], array_keys($productsArticles))) {
                    $article = $row["PROPERTY_CML2_ARTICLE_VALUE"];
                } elseif (in_array($row["PROPERTY_AT_ARTIKUL_VALUE"], array_keys($productsArticles))) {
                    $article = $row["PROPERTY_AT_ARTIKUL_VALUE"];
                }

                if (!empty($article)) {
                    $products[$row["ID"]] = $productsArticles[$article];
                    unset($productsArticles[$article]);
                }
            }
        }

        foreach ($products as $productId => $category) {
            if ((int)$productId <= 0 || empty($category)) {
                continue;
            }

            \CIBlockElement::SetPropertyValuesEx(
                $productId,
                \Helper::CATALOG_IB_ID,
                [
                    "YM_CATEGORY" => $category,
                ]
            );
            $updatedCnt = $updatedCnt + 1;
        }

        echo "<br>Обновлено {$updatedCnt} товаров из {$allCnt}<br>";
    }
}