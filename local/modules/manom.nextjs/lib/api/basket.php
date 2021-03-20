<?php

namespace Manom\Nextjs\Api;

use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;
use \Bitrix\Sale\Basket as bitrixBasket;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\BasketItem;
use \Bitrix\Sale\Fuser;
use \Bitrix\Main\Context;
use \Bitrix\Sale\BasketItemBase;
use Bitrix\Sale\SectionTable;

/**
 * Class Basket
 * @package Manom\Nextjs\Api
 */
class Basket
{
    /** @var BasketBase  $basket*/
    protected $basket;
    private $locationId;

    /**
     * Basket constructor.
     * @param int $orderId
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public function __construct($orderId = 0, $productId = 0)
    {
        if (!Loader::includeModule('sale')) {
            throw new SystemException('Не подключен модуль sale');
        }
        if (!Loader::includeModule('catalog')) {
            throw new SystemException('Не подключен модуль catalog');
        }

        $this->setCloneBasket();
        $this->addBasketItem($productId);

        if ($this->basket === null) {
            throw new \Bitrix\Main\ObjectNotFoundException('Корзина не существует');
        }
    }

    /**
     * @param int $locationId
     */
    public function setLocationId($locationId)
    {
        $this->locationId = (int)$locationId;
    }

    /**
     * @param int $orderId
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     */
    private function setBitrixBasket($orderId = 0)
    {
        $basket = null;

        if (empty($orderId)) {
            $basket = bitrixBasket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
        } else {
            $order = Order::getBitrixOrder($orderId);
            if ($order !== null) {
                $basket = $order->getBasket();
            }
        }

        $this->basket = $basket;
    }


    /**
     * @param int $orderId
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     */
    private function setCloneBasket()
    {
        $basket = null;

        $bitrixBasket = bitrixBasket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite())->getOrderableItems();
        $basket = \Bitrix\Sale\Basket::create(Context::getCurrent()->getSite());

        if ($bitrixBasket->count()) {
            /** @var BasketItem $basketItem */
            foreach ($bitrixBasket as $basketItem) {
                $item = $basket->createItem('catalog', $basketItem->getProductId());
                $item->setFields(array(
                    'QUANTITY' => $basketItem->getQuantity(),
                    'CURRENCY' => $basketItem->getCurrency(),
                    'LID' => $basketItem->getField("LID"),
                    'PRODUCT_PROVIDER_CLASS' => $basketItem->getField("PRODUCT_PROVIDER_CLASS"),
                ));
            }
        }

        $this->basket = $basket;
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotImplementedException
     */
    protected function addBasketItem($productId)
    {
        if ((int)$productId <= 0) {
            return;
        }

        if ($this->basket) {
            if ($item = $this->basket->getExistsItem('catalog', $productId)) {
                $item->setField('QUANTITY', $item->getQuantity() + 1);
            } else {
                $item = $this->basket->createItem('catalog', $productId);
                $item->setFields(array(
                    'QUANTITY' => 1,
                    'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                    'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
                    'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                ));
            }
//            $this->basket->save();

        }
    }

    /**
     * @return object
     */
    public function getBitrixBasket()
    {
        return $this->basket;
    }

    /**
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Exception
     */
    public function getItems()
    {
        $items = array();

        $basketItems = $this->basket->getBasketItems();
        if (empty($basketItems)) {
            return $items;
        }

        $this->getDiscounts();

        foreach ($basketItems as $basketItem) {
            $price = (int)$basketItem->getField('PRICE');
            $oldPrice = (int)$basketItem->getField('BASE_PRICE');

            $item = array(
                'id' => (int)$basketItem->getField('ID'),
                'productId' => (int)$basketItem->getField('PRODUCT_ID'),
                'name' => $basketItem->getField('NAME'),
                'image' => '',
                'url' => '',
                'category' => '',
                'brand' => '',
                'brandUrl' => '',
                'sizes' => '',
                'quantity' => (int)$basketItem->getField('QUANTITY'),
                'available' => $basketItem->canBuy() ? 1 : 0,
                'price' => $price,
                'priceFormat' => number_format($price, 0, '', ' '),
            );

            $discountPrice = (int)$basketItem->getField('DISCOUNT_PRICE');
            if (!empty($discountPrice)) {
                $valuePercent = round((($oldPrice - $price) / $oldPrice) * 100);

                $discountName = $basketItem->getField('DISCOUNT_NAME');
                if (empty($discountName)) {
                    $discountName = 'Скидка '.$valuePercent.'%';
                }

                $item['oldPrice'] = $oldPrice;
                $item['oldPriceFormat'] = number_format($oldPrice, 0, '', ' ');
                $item['discount'] = array(
                    'name' => $discountName,
                    'value' => $oldPrice - $price,
                    'valueFormat' => number_format($oldPrice - $price, 0, '', ' '),
                    'valuePercent' => $valuePercent,
                );
            }

            $items[] = $item;
        }

        $this->setFullNameItems($items);

        $offer = new Offer;
        $offers = $offer->getItemsById($this->getOffersId());

        $productsId = array();
        foreach ($offers as $offer) {
            foreach ($items as $i => $item) {
                if ($item['productId'] === $offer['id']) {
                    if (
                        !$offer['active'] ||
                        !$offer['available'] ||
                        $offer['quantity'] === 0
                    ) {
                        $items[$i]['available'] = 0;
                    }

                    $items[$i]['canIncrease'] = (int)($items[$i]['quantity'] < $offer['quantity']);
                    break;
                }
            }

            if (!in_array($offer['productId'], $productsId, true)) {
                $productsId[] = $offer['productId'];
            }
        }

        $product = new Product;
        $products = $product->getItemsById($productsId);

        foreach ($items as $i => $item) {
            if (!empty($offers[$item['productId']])) {
                $productId = $offers[$item['productId']]['productId'];

                if (!empty($products[$productId])) {
                    if (!empty($products[$productId]['imageId'])) {
                        $image = \CFile::ResizeImageGet(
                            $products[$productId]['imageId'],
                            array('width' => 240, 'height' => 240),
                            BX_RESIZE_IMAGE_PROPORTIONAL
                        );
                        $items[$i]['image'] = $image['src'];
                    }

                    $items[$i]['url'] = $products[$productId]['url'];
                    $items[$i]['category'] = $products[$productId]['section']['name'];
                    $items[$i]['brand'] = $products[$productId]['brand']['name'];
                    $items[$i]['brandUrl'] = $products[$productId]['brand']['url'];
                    $items[$i]['parentProductId'] = $products[$productId]['id'];
                    $items[$i]['availableInCurrentLocation'] = (int)($products[$productId]['stockStatus'] !== \Helper::NO_DELIVERY_IN_MOSCOW
                        || $this->locationId !== 84);

                    if (
                        !$products[$productId]['active'] ||
                        !$products[$productId]['available']
                    ) {
                        $items[$i]['available'] = 0;
                    }

                    if (
                        $products[$productId]['sale'] &&
                        !empty($products[$productId]['specialPrice']) &&
                        !empty($products[$productId]['specialPriceTime']) &&
                        $products[$productId]['specialPrice'] < $items[$i]['price'] &&
                        $products[$productId]['specialPriceTime'] > time()
                    ) {
                        $items[$i]['price'] = $products[$productId]['specialPrice'];
                        $items[$i]['priceFormat'] = number_format($products[$productId]['specialPrice'], 0, '', ' ');
                    }

                    if ($products[$productId]['sale'] && !empty($products[$productId]['specialPrice'])) {
                        $items[$i]['oldPrice'] = $products[$productId]['specialPrice'];
                        $items[$i]['oldPriceFormat'] = number_format($products[$productId]['specialPrice'], 0, '', ' ');
                        $items[$i]['customDiscount'] = array(
                            'name' => 'Распродажа',
                            'value' => $items[$i]['oldPrice'] - $items[$i]['price'],
                            'valueFormat' => number_format($items[$i]['oldPrice'] - $items[$i]['price'], 0, '', ' '),
                            'valuePercent' => round((($items[$i]['oldPrice'] - $items[$i]['price']) / $items[$i]['oldPrice']) * 100),
                        );
                    }
                }
            }
        }

        $this->setSizesItems($items);
        return $items;
    }

    /**
     * @return array
     */
    public function getOffersId()
    {
        $productsId = array();

        $basketItems = $this->basket->getBasketItems();
        if (empty($basketItems)) {
            return $productsId;
        }

        foreach ($basketItems as $basketItem) {
            $productId = (int)$basketItem->getField('PRODUCT_ID');
            if (!in_array($productId, $productsId, true)) {
                $productsId[] = $productId;
            }
        }

        return $productsId;
    }

    /**
     * @return int
     */
    public function getSum()
    {
        return (int)$this->basket->getPrice();
    }

    /**
     * @return int
     */
    public function getBaseSum()
    {
        return (int)$this->basket->getBasePrice();
    }

    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public function getDiscounts()
    {
        if (!$this->basket->getOrder()) {
            global $USER;
            $userId = $USER->GetID() ?: \CSaleUser::GetAnonymousUserID();

            $order = \Bitrix\Sale\Order::create(Context::getCurrent()->getSite(), $userId);

            $result = $order->appendBasket($this->basket);
            if ($result->isSuccess()) {
                $discounts = $order->getDiscount();
                $showPrices = $discounts->getShowPrices();

                if (!empty($showPrices['BASKET'])) {
                    foreach ($showPrices['BASKET'] as $basketCode => $data) {
                        $basketItem = $this->basket->getItemByBasketCode($basketCode);
                        if ($basketItem instanceof BasketItemBase) {
                            $basketItem->setFieldNoDemand('BASE_PRICE', $data['SHOW_BASE_PRICE']);
                            $basketItem->setFieldNoDemand('PRICE', $data['SHOW_PRICE']);
                            $basketItem->setFieldNoDemand('DISCOUNT_PRICE', $data['SHOW_DISCOUNT']);
                        }
                    }
                }
            }
        }

        $order = $this->basket->getOrder();
        $calcResults = $order->getDiscount()->getApplyResult(true);

        $appliedDiscountList = array();
        foreach ($calcResults['DISCOUNT_LIST'] as $discountData) {
            if (isset($calcResults['FULL_DISCOUNT_LIST'][$discountData['REAL_DISCOUNT_ID']])) {
                $appliedDiscountList[$discountData['REAL_DISCOUNT_ID']] = $calcResults['FULL_DISCOUNT_LIST'][$discountData['REAL_DISCOUNT_ID']];
            }
        }

        return $calcResults['FULL_DISCOUNT_LIST'];
    }

    /**
     * @param int $id
     * @param int $quantity
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function updateItemQuantity($id, $quantity)
    {
        $return = array(
            'error' => false,
            'message' => '',
            'success' => false,
        );

        $item = $this->basket->getItemById($id);
        if (empty($item)) {
            return array(
                'error' => true,
                'message' => 'Item does not exist',
            );
        }

        $productId = (int)$item->getField('PRODUCT_ID');
        if (empty($productId)) {
            return array(
                'error' => true,
                'message' => 'Product does not exist',
            );
        }

        $offerObject = new Offer;
        $offers = $offerObject->getItemsById($productId);
        if (empty($offers)) {
            return array(
                'error' => true,
                'message' => 'Product does not exist',
            );
        }

        $offer = reset($offers);
        if ((int)$quantity > (int)$offer['quantity']) {
            $quantity = $offer['quantity'];
        }

        $result = $item->setField('QUANTITY', $quantity);
        if ($result->isSuccess()) {
            $basketSave = $this->basket->save();
            $return['success'] = $basketSave->isSuccess();
        } else {
            $return['error'] = true;
            $return['message'] = $result->getErrorMessages();
        }

        return $return;
    }

    /**
     * @param int $id
     * @param int $offerId
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function updateItemSize($id, $offerId)
    {
        $return = array(
            'error' => false,
            'message' => '',
            'success' => false,
        );

        $result = null;

        $item = $this->basket->getItemById($id);
        if (empty($item)) {
            return array(
                'error' => true,
                'message' => 'Item does not exist',
            );
        }

        $offerObject = new Offer;
        $offers = $offerObject->getItemsById($offerId);
        if (empty($offers)) {
            return array(
                'error' => true,
                'message' => 'Product does not exist',
            );
        }

        $offer = reset($offers);

        if ((int)$item->getProductId() === (int)$offerId) {
            $return["success"] = true;
            return $return;
        }

        if (in_array((int)$offerId, $this->getOffersId())) {
            $return["success"] = true;
            return $return;
        }

        if ($offer["quantity"] <= 0 || !$offer["active"] || !$offer["available"]) {
            return array(
                'error' => true,
                'message' => 'Product Is Not Available For Purchase',
            );
        }

        $result = $item->setField('PRODUCT_ID', $offerId);

        if ($result->isSuccess()) {
            if ((int)$item->getQuantity() > (int)$offer['quantity']) {
                $result = $item->setField('QUANTITY', (int)$offer['quantity']);
            }

            if ($result->isSuccess()) {
                $result = $this->basket->refresh();
                if ($result->isSuccess()) {
                    $basketSave = $this->basket->save();
                    $return['success'] = $basketSave->isSuccess();
                }
            }
        }

        if (!$return['success']) {
            $return['error'] = true;
            if ($result) {
                $return['message'] = $result->getErrorMessages();
            }
        }

        return $return;
    }

    /**
     * @param int $id
     * @return array
     */
    public function deleteItem($id)
    {
        $return = array(
            'error' => false,
            'message' => '',
            'success' => false,
        );

        $item = $this->basket->getItemById($id);
        if (empty($item)) {
            return array(
                'error' => true,
                'message' => 'Item does not exist',
            );
        }

        $result = $item->delete();
        if ($result->isSuccess()) {
            $basketSave = $this->basket->save();
            $return["success"] = $basketSave->isSuccess();
        } else {
            $return['error'] = true;
            $return['message'] = $result->getErrorMessages();
        }

        return $return;
    }

    /**
     * @param array $items
     *
     * @return void
     * @throws \Exception
     */
    private function setFullNameItems(Array &$items)
    {
        if (empty($items)) {
            return;
        }

        $skuData = \CCatalogSku::getProductList($this->getOffersId());
        $skuToProducts = array_map(
            function ($product) {
                return $product["ID"];
            },
            $skuData
        );

        if (empty(array_filter(array_values($skuToProducts)))) {
            return;
        }

        $rsProducts = \CIBlockElement::getList(
            [],
            [
                "=ID" => array_values($skuToProducts),
            ],
            false,
            false,
            [
                "ID",
                "IBLOCK_ID",
                "PROPERTY_BRAND.NAME",
                "IBLOCK_SECTION_ID",
            ]
        );

        $products = [];

        while ($product = $rsProducts->GetNext()) {
            $products[$product["ID"]] = [
                "SECTION_ID" => $product["IBLOCK_SECTION_ID"],
                "BRAND_NAME" => $product["PROPERTY_BRAND_NAME"],
            ];
        }

        if (empty($products)) {
            return;
        }

        $sectionsIds = array_map(
            function ($product) {
                return $product["SECTION_ID"];
            },
            $products
        );

        $arSectionsId = array_unique(array_filter(array_values($sectionsIds)));

        $sections = [];

        if (!empty($arSectionsId)) {
            $arSections = \Bitrix\Iblock\SectionTable::getList(
                [
                    "filter" => [
                        "=ID" => $arSectionsId,
                    ],
                    "select" => [
                        "ID",
                        "NAME",
                    ],
                ]
            )->fetchAll();

            foreach ($arSections as $section) {
                $sections[$section["ID"]] = $section["NAME"];
            }
        }

        foreach ($products as &$product) {
            if (!empty($sections[$product["SECTION_ID"]])) {
                $product["SECTION_NAME"] = $sections[$product["SECTION_ID"]];
            }
        }

        unset($product);

        foreach ($items as &$item) {
            if (empty($productId = $skuToProducts[$item["productId"]])) {
                continue;
            }

            if (empty($product = $products[$productId])) {
                continue;
            }

            $additionalName = !empty($product["SECTION_NAME"]) ? $product["SECTION_NAME"] : "";
            $additionalName .= !empty($product["BRAND_NAME"]) ? " ".$product["BRAND_NAME"] : "";
            $item["name"] = "{$additionalName} {$item["name"]}";
            $item["name"] = trim($item["name"]);
        }
    }

    /**
     * @param array $items
     *
     * @return void
     * @throws \Exception
     */
    private function setSizesItems(Array &$items)
    {
        $products = \CCatalogSku::getProductList($this->getOffersId());

        $products = array_map(function ($item) {
            return $item["ID"];
        }, $products);

        $offers = \CCatalogSku::getOffersList(array_values($products));

        if (!is_array($offers)) {
            $offers = [];
        }

        $productsForSku = [];


        foreach ($offers as $productId => $productOffers) {
            foreach ($productOffers as $productOffer) {
                $productsForSku[$productOffer["ID"]] = $productId;
            }
        }

        $allIds = array_merge(
            array_keys($productsForSku),
            array_values($productsForSku)
        );

        $allIds = array_unique($allIds);

        $arProducts = [];
        $arSections = [];
        $arOffers = [];

        $genderFromBasket = [
            'м' => 88,
            'ж' => 89,
            'д' => 90,
        ];

        if (empty($allIds)) {
            return;
        }

        $rsProducts = \CIBlockElement::GetList(
            [],
            [
                "=ID" => $allIds,
                "ACTIVE" => "Y",
                [
                    "LOGIC" => "OR",
                    [
                        "IBLOCK_ID" => 3,
                        ">CATALOG_QUANTITY" => 0
                    ],
                    [
                        "IBLOCK_ID" => 2
                    ],
                ],
            ],
            false,
            false,
            [
                "IBLOCK_ID",
                "NAME",
                "ID",
                "IBLOCK_SECTION_ID",
                "PROPERTY_SIZES_SHOES.NAME",
                "PROPERTY_GENDER.NAME",
                "PROPERTY_BRAND.ID",
                "PROPERTY_BRAND.XML_ID",
                "PROPERTY_BRAND.PROPERTY_SIZE_CHART",
                "PROPERTY_SIZECHART",
            ]
        );

        while ($arProduct = $rsProducts->GetNext()) {
            if ((int)$arProduct["IBLOCK_ID"] === 2) {
                if (!empty($arProduct["PROPERTY_GENDER_NAME"])) {
                    $arProduct["GENDER"] = mb_strtolower(mb_substr(strip_tags($arProduct["PROPERTY_GENDER_NAME"]), 0, 1,
                        "UTF-8"), "UTF-8");
                } else {
                    $arProduct["GENDER"] = "м";
                }

                if (!empty($arProduct["PROPERTY_SIZECHART_VALUE"])) {
                    $arProduct["PROPERTY_BRAND_PROPERTY_SIZE_CHART_VALUE"] = $arProduct["PROPERTY_SIZECHART_VALUE"];
                }

                $arProducts[$arProduct["ID"]] = $arProduct;
            }
            if ((int)$arProduct["IBLOCK_ID"] === 3) {
                $arOffers[$arProduct["ID"]] = $arProduct;
            }
        }

        $brandIds = array_map(
            function ($item) {
                return $item["PROPERTY_BRAND_ID"];
            },
            $arProducts
        );

        $brandIds = array_unique(array_filter(array_values($brandIds)));

        $brandsSizesTables = [];

        foreach ($brandIds as $brandId) {
            $rsSizesTables = \CIBlockElement::GetProperty(6, $brandId, ['SORT' => 'ASC'], ['CODE' => 'SIZE_TABLES']);

            while ($arSizesTable = $rsSizesTables->GetNext()) {
                $brandsSizesTables[$brandId][] = $arSizesTable['VALUE'];
            }
        }


        $arSectionsId = array_map(
            function ($item) {
                return $item["IBLOCK_SECTION_ID"];
            },
            $arProducts
        );

        $arSectionsId = array_unique(array_filter(array_values($arSectionsId)));

        if (!empty($arSectionsId)) {
            $rsSections = \CIBlockSection::GetList(
                [],
                [
                    "=ID" => $arSectionsId,
                ],
                false,
                [
                    "ID",
                    "IBLOCK_ID",
                    "XML_ID",
                ]
            );

            while ($arSection = $rsSections->GetNext()) {
                $arSections[$arSection["ID"]] = $arSection;
            }
        }

        foreach ($arProducts as &$arProduct) {
            if (
                !empty($arProduct["IBLOCK_SECTION_ID"])
                && !empty(
                $arSections[$arProduct["IBLOCK_SECTION_ID"]]
                )
            ) {
                $arProduct["SECTION"] = $arSections[$arProduct["IBLOCK_SECTION_ID"]];
            }
        }

        $offersByProducts = [];

        foreach ($arOffers as &$item) {
            $currentItem = $item;
            $arCurProduct = $arProducts[$productsForSku[$item["ID"]]];

            if (!empty($currentItem["PROPERTY_SIZES_SHOES_NAME"])) {
                $currentItem["SIZES_SHOES"] = $currentItem["PROPERTY_SIZES_SHOES_NAME"];
            }

            if (
                in_array($arCurProduct["SECTION"]["XML_ID"], [46, 26, 21])
                && !empty($arCurProduct["PROPERTY_BRAND_XML_ID"])
            ) {
                $err_mess = "FILE: " . __FILE__ . "<br>LINE: ";

                global $DB;
                $strSql = "SELECT * FROM cat_category_sizecharts WHERE brand_id=" . $arCurProduct["PROPERTY_BRAND_XML_ID"] . " && gender=" . $genderFromBasket[$arCurProduct["GENDER"]];
                $res = $DB->Query($strSql, false, $err_mess . __line__);

                if (!$res->SelectedRowsCount()) {
                    $strSql = "SELECT * FROM cat_category_sizecharts WHERE brand_id=49  && gender=" . $genderFromBasket[$arCurProduct["GENDER"]];
                    $res = $DB->Query($strSql, false, $err_mess . __line__);
                }
                $sizesU = [];
                while ($arElement = $res->GetNext()) {
                    $sizesU[] = $arElement;
                }

                if (empty($sizesU)) {
                    $sizesU = \Helper::$defaultSizes;
                }

                if ($arCurProduct['PROPERTY_BRAND_PROPERTY_SIZE_CHART_VALUE']) {
                    foreach ($brandsSizesTables as $brandsSizesTable) {

                        if (
                            ($brandsSizesTable['Пол'] == $arCurProduct["GENDER"])
                            && ($brandsSizesTable[$arCurProduct['PROPERTY_BRAND_PROPERTY_SIZE_CHART_VALUE']] === $currentItem['SIZES_SHOES'])
                        ) {
                            $currentItem['SIZE'] = $currentItem['SIZES_SHOES'];
                            $currentItem['US_SIZE'] = $brandsSizesTable['US'];
                        }
                        if (empty($currentItem['SIZE'])) {
                            foreach ($sizesU as $suzeU) {
                                if ($suzeU[$arCurProduct['PROPERTY_BRAND_PROPERTY_SIZE_CHART_VALUE']] === $currentItem['SIZES_SHOES']) {
                                    $currentItem['SIZE'] = $currentItem['SIZES_SHOES'];
                                    $currentItem['US_SIZE'] = $suzeU['US'];
                                    $currentItem['RUS_SIZE'] = $suzeU['RUS'];
                                }
                            }
                        }
                    }
                }
            }

            if (empty($currentItem['SIZE'])) {
                $currentItem['SIZE'] = $currentItem['SIZES_SHOES'];
                $currentItem['SIZE_NAME'] = $currentItem['SIZES_SHOES'];
            }

            if (!empty($currentItem['US_SIZE'])) {
                $item["size"] = $currentItem['US_SIZE'] . " US";
            }

            if (!empty($currentItem['RUS_SIZE'])) {
                if (!empty($currentItem['US_SIZE'])) {
                    $item["size"] .= " ({$currentItem['RUS_SIZE']} RUS)";
                } else {
                    $item["size"] = $currentItem['RUS_SIZE'] . " RUS";
                }
            }

            if (empty($item["size"])) {
                $item["size"] = $currentItem['SIZE_NAME'];
            } else {
                $item["size_num"] = $currentItem['SIZE'];
            }

            $offersByProducts[$productsForSku[$item["ID"]]][] = $item;
        }
        unset($item);

        foreach ($offersByProducts as &$offersByProduct) {
            usort($offersByProduct, function ($a, $b) {
                return $a['size_num'] > $b['size_num'] ? 1 : -1;
            });

            foreach ($offersByProduct as &$offer) {
                $offer = [
                    "id" => (int)$offer["ID"],
                    "name" => $offer["size"],
                ];
            }
            unset($offer);
        }

        unset($offersByProduct);
        $basketOffersId = $this->getOffersId();
        foreach ($items as &$item) {
            $sizes = $offersByProducts[$item["parentProductId"]];

            $sizes = array_filter(
                $sizes,
                function ($sizesItem)
                use ($item, $basketOffersId) {
                    return !in_array($sizesItem["id"], $basketOffersId) || $sizesItem["id"] === $item["productId"];
                });

            $item["sizes"] = array_values($sizes);
        }
    }
}
