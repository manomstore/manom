<?php

namespace Manom;


class GTM
{
    private static $resultDataJS = [];
    private static $additionalData = [];
    private static $basketItems = [];
    private static $currency = [];
    private static $listsItems = [];
    private static $isListItems = false;
    private static $productsOnPage = [];

    static function getDataJS($pageType, $additional = [])
    {
        $pageType = (string)$pageType;
        if (empty($pageType)) {
            return self::$resultDataJS;
        }

        self::setAdditionalData($additional);
        self::setPageType($pageType);
        self::setCommonData();
        self::setDataForType();

        return json_encode(self::$resultDataJS);
    }

    private static function setCommonData()
    {
        global $USER;
        self::$resultDataJS["user"]["visitorId"] = session_id();

        if ($USER->IsAuthorized()) {
            self::$resultDataJS["user"]["userId"] = $USER->GetID();
            if (!empty($USER->GetEmail())) {
                self::$resultDataJS["user"]["email"] = $USER->GetEmail();
            }

            self::$resultDataJS["user"]["userType"] = $USER->IsAdmin() ? "worker" : "user";
        } else {
            self::$resultDataJS["user"]["userType"] = "guest";
        }

        $userLoc = new \UserLocation();
        $userLoc = $userLoc->getUserLocationInfo();
        self::$resultDataJS["pageVersion"] = 0;
        self::$resultDataJS["geo"] = [
            "country" => $userLoc["COUNTRY_NAME"],
            "countryId" => $userLoc["COUNTRY_ID"],
            "region" => $userLoc["REGION_NAME"],
            "regionId" => $userLoc["REGION_ID"],
            "city" => $userLoc["CITY_NAME"],
            "cityId" => $userLoc["CITY_ID"],
        ];

        if (self::getPageType() !== "purchase") {
            $basket = \Bitrix\Sale\Basket::loadItemsForFUser(
                \Bitrix\Sale\Fuser::getId(),
                \Bitrix\Main\Context::getCurrent()->getSite()
            );

            self::setBasketItems($basket->getBasketItems());
            $basketProductsId = array_map("self::getProductIdCallback", $basket->getBasketItems());

            self::$resultDataJS["cart"] = [
                "currency" => self::getCurrency(),
                "total" => $basket->getPrice(),
                "count" => $basket->count(),
                "items" => self::getProductObjects($basketProductsId),
                "recommend" => self::getProductObjects(self::getRecommendedIds($basketProductsId)),
                "cartId" => $basket->getFUserId(),
            ];
        }

        self::$resultDataJS["geo"] = array_filter(self::$resultDataJS["geo"], function ($item) {
            return !empty($item);
        });
    }

    private static function setDataForType()
    {
        switch (self::getPageType()) {
            case "home":
            case "category":
            case "searchresults":
                self::setListingData();
                break;
            case "product":
                self::setDetailData();
                break;
            case "purchase":
                self::setPurchaseData();
                break;
        }
    }

    private static function setBasketItems($basketItems = [])
    {
        self::$basketItems = $basketItems;
    }

    private static function setPageType($pageType)
    {
        self::$resultDataJS["pageType"] = $pageType;
    }

    private static function setAdditionalData($additionalData)
    {
        self::$additionalData = $additionalData;
    }

    private static function getPageType()
    {
        return self::$resultDataJS["pageType"];
    }

    public static function getCurrency()
    {
        if (!empty(self::$currency)) {
            return self::$currency;
        }

        self::$currency = !empty(self::$basketItems) ? reset(self::$basketItems)->getCurrency() : "";
        self::$currency = !empty(self::$currency) ? self::$currency :
            \COption::GetOptionString("sale", "default_currency", "RUB");

        return self::$currency;
    }

    private static function setPurchaseData()
    {
        $orderId = self::$additionalData["order"];
        $order = \Bitrix\Sale\Order::load($orderId);
        if (empty($order)) {
            return false;
        }

        $basket = $order->getBasket();
        self::setBasketItems($basket->getBasketItems());
        $basketProductsId = array_map("self::getProductIdCallback", $basket->getBasketItems());
        $delivery = $order->getShipmentCollection()->current();
        $payment = $order->getPaymentCollection()->current();

        self::$resultDataJS["transaction"] = [
            "currency" => $order->getCurrency(),
            "id" => (int)$order->getId(),
            "affiliation" => "shop",
            "revenue" => (int)$order->getPrice(),
            "shipping" => (int)$order->getDeliveryPrice(),
            "items" => self::getProductObjects($basketProductsId),
            "recommend" => self::getProductObjects(self::getRecommendedIds($basketProductsId)),
            "shippingType" => $delivery ? (string)$delivery->getDeliveryName() : "",
            "paymentType" => $payment ? (string)$payment->getField("PAY_SYSTEM_NAME") : "",
            "tax" => $order->getTaxValue(),
        ];

        return true;
    }

    private static function getProductIdCallback($item)
    {
        /** @var \Bitrix\Sale\BasketItem $item */
        return (int)$item->getProductId();
    }

    public static function setProductsOnPage($itemsId, $byItemsArray = false, $idKey = "id")
    {
        if (!is_array($itemsId)) {
            $itemsId = [];
        }

        if ($byItemsArray && empty($idKey)) {
            $itemsId = [];
        }

        if ($byItemsArray) {
            $itemsId = array_map(function ($item) use ($idKey) {
                return $item[$idKey];
            }, $itemsId);
        }

        self::$productsOnPage = array_merge(self::$productsOnPage, $itemsId);
    }

    public static function getProductsOnPageJS()
    {
        self::$productsOnPage = array_filter(
            self::$productsOnPage,
            function ($item) {
                return (int)$item >= 0;
            });

        $productsObj = self::getProductObjects(self::$productsOnPage);

        return json_encode($productsObj);
    }

    private static function setDetailData()
    {
        $detailObject = [
            "currency" => self::getCurrency(),
            "items" => self::getProductObjects(self::$additionalData["items"]),
            "recommend" => self::getProductObjects(self::getRecommendedIds(self::$additionalData["items"])),
        ];

        self::setCategory($detailObject, self::$additionalData["categoryId"]);

        self::$resultDataJS["detail"] = $detailObject;
    }

    private static function setListingData()
    {
        if (empty(self::$additionalData["items"]) && !empty(self::getListsItems())){
            self::$additionalData["items"] = self::getListsItems();
            self::$isListItems = true;
        }

        $listing = [
            "currency" => self::getCurrency(),
            "items" => self::getProductObjects(self::$additionalData["items"]),
            "resultCount" => self::$additionalData["resultCount"],
            "pageCount" => self::$additionalData["pageCount"],
            "currentPage" => self::$additionalData["currentPage"],
        ];

        self::setCategory($listing, self::$additionalData["categoryId"]);
        self::setSearchQuery($listing, self::$additionalData["searchQuery"]);

        self::$resultDataJS["listing"] = $listing;
        self::$isListItems = false;
    }

    static function getProductObjects(Array $itemsId)
    {

        $arResultProducts = [];

        if (empty($itemsId)) {
            return $arResultProducts;
        }

        $recommendedProducts = self::getRecommendedIds($itemsId, true);

        $products = \CIBlockElement::GetList(
            [],
            [
                "ID" => $itemsId,
                "IBLOCK_ID" => \Helper::CATALOG_IB_ID,
            ],
            false,
            false,
            [
                "ID",
                "IBLOCK_ID",
                "PROPERTY_CML2_ARTICLE",
                "PROPERTY_TOP_FIELD_2",
                "PROPERTY_model",
                "PREVIEW_PICTURE",
                "DETAIL_PICTURE",
                "PROPERTY_MORE_PHOTO",
                "NAME",
                "IBLOCK_SECTION_ID",
                "DETAIL_PAGE_URL",
                "PROPERTY_SELL_PROD",
                "PROPERTY_NEW_PRODUCT",
                "PROPERTY_PRODUCT_OF_THE_DAY",
            ]
        );

        $arProducts = [];

        while ($product = $products->GetNext()) {
            if (!empty($arProducts[$product["ID"]])) {
                continue;
            }
            if (!empty($product["PREVIEW_PICTURE"])) {
                $product["PREVIEW_PICTURE"] = ["ID" => $product["PREVIEW_PICTURE"]];
            }

            if (!empty($product["DETAIL_PICTURE"])) {
                $product["DETAIL_PICTURE"] = ["ID" => $product["DETAIL_PICTURE"]];
            }

            if (!empty($product["PROPERTY_MORE_PHOTO_VALUE"])) {
                $product["PROPERTIES"]["MORE_PHOTO"]["VALUE"] = [
                    $product["PROPERTY_MORE_PHOTO_VALUE"]
                ];
            }

            $arProducts["ITEMS"][$product["ID"]] = $product;
        }

        self::setListItemsData($arProducts["ITEMS"]);

        $arProducts["ITEMS"] = array_values($arProducts["ITEMS"]);

        $arProducts = \Manom\Content::setCatalogItemsEcommerceData($arProducts);
        $arProducts = $arProducts["ITEMS"];

        $domain = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

        foreach ($arProducts as $idx => $arProduct) {
            $image = reset(\Manom\Content::getCatalogItemImages($arProduct));

            $productObject = [
                "id" => (int)$arProduct["ID"],
                "position" => $arProduct["position"] ?? $idx + 1,
                "name" => $arProduct["NAME"],
                "url" => $domain . $arProduct["DETAIL_PAGE_URL"],
            ];

            if (!empty($arProduct["list"])) {
                $productObject["list"] = $arProduct["list"];
            }

            if (!empty($image)) {
                $productObject = array_merge($productObject, [
                    "imageUrl" => $domain . $image["src"],
                    "thumbnailUrl" => $domain . $image["src"],
                ]);
            }

            if (!empty($recommendedProducts[$arProduct["ID"]])) {
                $productObject["crossSell"] = $recommendedProducts[$arProduct["ID"]];
            }

            self::setPriceProduct($productObject, $arProduct);
            self::setQuantityProduct($productObject);
            self::setCategory($productObject, $arProduct["IBLOCK_SECTION_ID"]);
            self::setSku($productObject, $arProduct);
            self::setVariant($productObject, $arProduct);

            $arResultProducts[] = $productObject;
        }

        return $arResultProducts;
    }

    private static function setQuantityProduct(&$productObject)
    {
        if (
            !in_array(self::getPageType(), ["cart", "purchase"])
            || !is_array(self::$basketItems)
            || empty(self::$basketItems)
        ) {
            return false;
        }

        $productBasket = array_filter(self::$basketItems, function ($item) use ($productObject) {
            return (int)$item->getProductId() === (int)$productObject["id"];
        });

        $productBasket = reset($productBasket);

        if (!$productBasket) {
            return false;
        }

        $productObject["quantity"] = (int)$productBasket->getQuantity();
        return true;
    }

    private static function setPriceProduct(&$productObject, $arProduct)
    {
        $productObject["price"] = (float)$arProduct["price"];

        if ((float)$arProduct["oldPrice"] > 0) {
            $productObject["priceOld"] = (float)$arProduct["oldPrice"];
        }
    }

    private static function setCategory(&$productObject, $categoryId)
    {
        if (!in_array(self::getPageType(), ["category", "product"]) || (int)$categoryId <= 0) {
            return false;
        }

        $arSections = \CIBlockSection::GetNavChain(false, $categoryId, ['ID', 'NAME', 'DEPTH_LEVEL'], true);

        foreach ($arSections as $arSection) {
            $productObject["category"][] = $arSection["NAME"];
            $productObject["categoryId"][] = $arSection["ID"];
        }

        return true;
    }

    private static function setSearchQuery(&$listingObject, $searchQuery)
    {
        if (self::getPageType() !== "searchresults" || empty($searchQuery)) {
            return false;
        }

        $listingObject["query"] = $searchQuery;
        return true;
    }

    private static function setVariant(&$productObject, $arProduct)
    {
        if ($arProduct["PROPERTY_SELL_PROD_VALUE"] === "Да") {
            $productObject["variant"][] = "Распродажа";
        }
        if ($arProduct["PROPERTY_NEW_PRODUCT_VALUE"] === "Да") {
            $productObject["variant"][] = "Новинка";
        }
        if ($arProduct["PROPERTY_PRODUCT_OF_THE_DAY_VALUE"] === "Да") {
            $productObject["variant"][] = "Товар дня";
        }
    }

    private static function setSku(&$productObject, $arProduct)
    {
        $sku = $arProduct["PROPERTY_CML2_ARTICLE_VALUE"];

        if (empty($sku)) {
            $sku = $arProduct["PROPERTY_TOP_FIELD_2_VALUE"];
        }

        if (empty($sku)) {
            $sku = $arProduct["PROPERTY_MODEL_VALUE"];
        }

        if (!empty($sku)) {
            $productObject["sku"] = $sku;
        }
    }

    public static function addListsItems($type, $items)
    {
        self::$listsItems[$type] = $items;
        self::setProductsOnPage($items);
    }

    private static function getLists()
    {
        return self::$listsItems;
    }

    private static function getListsItems()
    {
        $items = [];
        array_map(function ($list) use (&$items) {
            $items = array_merge($items, array_values($list));
        }, self::getLists());

        return $items;
    }

    private static function setListItemsData(&$products)
    {
        if (!self::$isListItems) {
            return;
        }

        $resultItems = [];
        foreach (self::getLists() as $listType => $items) {
            $position = 0;
            if (!is_array($items)) {
                continue;
            }

            foreach ($items as $item) {
                $position++;
                $products[$item]["position"] = $position;
                $products[$item]["list"] = $listType;
                $resultItems[] = $products[$item];
            }
        }
        $products = $resultItems;
    }

    public static function getTransaction($orderId)
    {
        $gtmData = self::getDataJS("purchase", ["order" => $orderId]);
        $gtmData = json_decode($gtmData, true);

        return $gtmData["transaction"];
    }

    static function getRecommendedIds($productsId, $grouped = false)
    {
        $commonRecommend = [];

        if (empty($productsId)) {
            return [];
        }

        $basketProducts = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => \Helper::CATALOG_IB_ID,
                "=ID" => $productsId,
            ],
            false,
            false,
            [
                "ID",
                "IBLOCK_ID",
                "IBLOCK_ID",
                "PROPERTY_ACESS",
            ]
        );

        while ($basketProduct = $basketProducts->GetNext()) {
            if ((int)$basketProduct["PROPERTY_ACESS_VALUE"] > 0) {
                if ($grouped) {
                    $commonRecommend[$basketProduct["ID"]][] = (int)$basketProduct["PROPERTY_ACESS_VALUE"];
                } else {
                    $commonRecommend[] = (int)$basketProduct["PROPERTY_ACESS_VALUE"];
                }
            }
        }

        if (empty($commonRecommend)) {
            return [];
        }

        return $commonRecommend;
    }
}