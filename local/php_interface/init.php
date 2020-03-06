<?php

use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;
use Bitrix\Main\Loader;
use Bitrix\Sale\PropertyBase;
use Bitrix\Sale\Registry;
use Rover\GeoIp\Location;

require_once __DIR__.'/autoload.php';

Loader::includeModule('rover.geoip');
Loader::includeModule('sale');

if ($_GET["type"] == "catalog" && $_GET["mode"] == "import"):
    AddEventHandler(
        "iblock",
        "OnBeforeIBlockElementUpdate",
        Array("MyHandlerClass", "OnBeforeIBlockElementUpdateHandler")
    );
    AddEventHandler(
        "iblock",
        "OnBeforeIBlockElementAdd",
        Array("MyHandlerClass", "OnBeforeIBlockElementAddHandler")
    );
    AddEventHandler(
        "iblock",
        "OnBeforeIBlockSectionAdd",
        Array("MyHandlerClass", "OnAfterIBlockSectionAddHandler")
    );
    AddEventHandler(
        "iblock",
        "OnBeforeIBlockSectionUpdate",
        Array("MyHandlerClass", "OnAfterIBlockSectionAddHandler")
    );
endif;

AddEventHandler(
    "sale",
    "OnBeforeEventAdd",
    Array("MyHandlerClass", "OnBeforeEventAddHandler")
);
AddEventHandler(
    "sale",
    "OnSaleComponentOrderProperties",
    Array("MyHandlerClass", "OnSaleComponentOrderPropertiesHandler")
);
AddEventHandler(
    "iblock",
    "OnAfterIBlockElementUpdate",
    Array("MyHandlerClass", "reIndexSku")
);
AddEventHandler(
    "sale",
    "OnSaleOrderBeforeSaved",
    Array("MyHandlerClass", "checkTimeDelivery")
);
AddEventHandler(
    "sale",
    "OnSaleOrderBeforeSaved",
    Array("MyHandlerClass", "OnSaleOrderBeforeSavedHandler")
);
AddEventHandler(
    "sale",
    "OnSaleOrderBeforeSaved",
    Array("MyHandlerClass", "roistatOnSaleOrderBeforeSaved")
);
AddEventHandler(
    "yandex.market",
    "onExportOfferWriteData",
    Array("MyHandlerClass", "onExportOfferWriteDataHandler")
);
AddEventHandler(
    "sale",
    "OnSaleComponentOrderUserResult",
    Array("MyHandlerClass", "OnSaleComponentOrderUserResultHandler")
);

AddEventHandler("main", "OnBeforeUserLogin", Array("CUserEx", "OnBeforeUserLogin"));
AddEventHandler("main", "OnBeforeUserRegister", Array("CUserEx", "OnBeforeUserRegister"));
AddEventHandler("main", "OnBeforeUserRegister", Array("CUserEx", "OnBeforeUserUpdate"));
AddEventHandler("main", "OnAfterUserAdd", Array("CUserEx", "OnAfterUserAddHandler"));
AddEventHandler("main", "OnSendUserInfo", Array("CUserEx", "OnSendUserInfoHandler"));
AddEventHandler("main", "OnAfterUserRegister", array('UserHandler', 'afterRegister'));

// AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("MyHandlerClass", "OnBeforeIBlockElementAddHandler"));
// AddEventHandler("iblock", "OnAfterIBlockSectionAdd", Array("MyHandlerClass", "OnAfterIBlockSectionAddHandler"));
// AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", Array("MyHandlerClass", "OnAfterIBlockSectionAddHandler"));

function getRatingAndCountReviewForList($prodIDs)
{
    $rating = 0;
    $arRev = array();
    $return = array();
    $getAllReviewByProdID = CIBlockElement::GetList(
        array('ID' => 'DESC'),
        array(
            'IBLOCK_ID' => 11,
            'PROPERTY_RV_PRODCTS' => $prodIDs,
            'ACTIVE' => 'Y',
        ),
        false,
        false,
        array(
            'PROPERTY_RV_RATING',
            'PROPERTY_RV_PRODCTS',
            'ID',
        )
    );
    $sumRating = array();
    while ($resReview = $getAllReviewByProdID->Fetch()) {
        if (!$sumRating[$resReview['PROPERTY_RV_PRODCTS_VALUE']]) {
            $sumRating[$resReview['PROPERTY_RV_PRODCTS_VALUE']] = 0;
        }
        $sumRating[$resReview['PROPERTY_RV_PRODCTS_VALUE']] += (int)$resReview['PROPERTY_RV_RATING_VALUE'];
        $arRev[$resReview['PROPERTY_RV_PRODCTS_VALUE']][] = $resReview;
    }
    // if ($arRev) {
    foreach ($prodIDs as $key => $value) {
        $return[$value] = array();
        $return[$value]['rating'] = 0;
        $return[$value]['count'] = 0;
        if ($sumRating[$value] > 0) {
            $return[$value]['rating'] = $sumRating[$value] / count($arRev[$value]);
            $return[$value]['count'] = count($arRev[$value]);
        }
        // }
    }
    return $return;
}

function checkProdInFavoriteAndCompareList($prodID, $code)
{
    global $USER;
    global $APPLICATION;

    $hasProdInList = false;
    if ($USER->IsAuthorized()) {
        $rsUsers = CUser::GetList(
            ($by = "personal_country"),
            ($order = "desc"),
            array('ID' => $USER->GetID()),
            array(
                'SELECT' => array($code),
            )
        );
        if ($resUsers = $rsUsers->Fetch()) {
            if (!$resUsers[$code]) {
                $resUsers[$code] = json_encode(array());
            }
            $favoriteList = json_decode($resUsers[$code]);

            $newList = array();
            foreach ($favoriteList as $i => $fav) {
                if ($fav == $prodID) {
                    $hasProdInList = true;
                }
            }
        }
    } else {
        $listID = $APPLICATION->get_cookie($code);
        // print_r($listID);
        if (!$listID) {
            $listID = json_encode(array());
        }
        $favoriteList = json_decode($listID);

        $newList = array();
        foreach ($favoriteList as $i => $fav) {
            if ($fav == $prodID) {
                $hasProdInList = true;
            }
        }
    }
    return $hasProdInList;
}

function getProdListFavoritAndCompare($code)
{
    global $USER;
    global $APPLICATION;

    $returnList = array();
    if ($USER->IsAuthorized()) {
        $rsUsers = CUser::GetList(
            ($by = "personal_country"),
            ($order = "desc"),
            array('ID' => $USER->GetID()),
            array(
                'SELECT' => array($code),
            )
        );
        if ($resUsers = $rsUsers->Fetch()) {
            if (!$resUsers[$code]) {
                $resUsers[$code] = json_encode(array());
            }
            $favoriteList = json_decode($resUsers[$code]);

            foreach ($favoriteList as $i => $fav) {
                $returnList[] = $fav;
            }
        }
    } else {
        $listID = $APPLICATION->get_cookie($code);
        // print_r($listID);
        if (!$listID) {
            $listID = json_encode(array());
        }
        $favoriteList = json_decode($listID);

        foreach ($favoriteList as $i => $fav) {
            $returnList[] = $fav;
        }
    }
    return $returnList;
}

function getAllProdsWithoutReviewFromOrders()
{
    global $USER;
    global $APPLICATION;

    $return = array();
    if ($USER->IsAuthorized()) {
        $orderIDs = array();
        $arFilter = array('PAYED' => 'Y', "USER_ID" => $USER->GetID());
        $rsOrders = CSaleOrder::GetList(array('ID' => 'DESC'), $arFilter, false, false, array('ID'));
        while ($ar_sales = $rsOrders->Fetch()) {
            $orderIDs[] = $ar_sales['ID'];
        }

        $return = array();
        if ($orderIDs) {
            $productIDs = array();
            $res = CSaleBasket::GetList(array(), array("ORDER_ID" => $ar_sales['ID']));
            while ($arItem = $res->Fetch()) {
                $productIDs[] = $arItem['PRODUCT_ID'];
            }
            if ($productIDs) {
                $alreadyHasReview = getAllProdByReview();
                $filt = array("IBLOCK_ID" => 7, "ID" => $productIDs);
                if ($alreadyHasReview) {
                    $filt["!PROPERTY_CML2_LINK"] = $alreadyHasReview;
                }
                $getProds = CIBlockElement::GetList(
                    array(),
                    $filt,
                    false,
                    false,
                    array('ID', 'PROPERTY_CML2_LINK')
                );
                while ($resProds = $getProds->Fetch()) {
                    $return[md5($resProds['PROPERTY_CML2_LINK_VALUE'])] = $resProds['PROPERTY_CML2_LINK_VALUE'];
                }
            }
        }
    }
    return $return;
}

function getAllProdByReview()
{
    global $USER;
    global $APPLICATION;

    $return = array();
    if ($USER->IsAuthorized()) {
        $getReview = CIBlockElement::GetList(
            array(),
            array("IBLOCK_ID" => 11, "PROPERTY_RV_USER" => $USER->GetID()),
            false,
            false,
            array('ID', 'PROPERTY_RV_PRODCTS')
        );
        while ($resReview = $getReview->Fetch()) {
            if ($resReview['PROPERTY_RV_PRODCTS_VALUE']) {
                $return[] = $resReview['PROPERTY_RV_PRODCTS_VALUE'];
            }
        }
    }
    return $return;
}

function getUserReviewForProd($prodID)
{
    global $USER;

    $return = false;
    if ($USER->IsAuthorized()) {
        $getReview = CIBlockElement::GetList(
            array(),
            array("IBLOCK_ID" => 11, "PROPERTY_RV_USER" => $USER->GetID(), "PROPERTY_RV_PRODCTS" => $prodID),
            false,
            false,
            array(
                'ID',
                'PROPERTY_RV_MERITS',
                'PROPERTY_RV_DISADVANTAGES',
                'PROPERTY_RV_RATING',
                'PREVIEW_TEXT',
                "DATE_CREATE",
            )
        );
        if ($resReview = $getReview->Fetch()) {
            $return = array();
            $return['rating'] = $resReview['PROPERTY_RV_RATING_VALUE'];
            $return['comment'] = $resReview['PREVIEW_TEXT'];
            $return['merits'] = $resReview['PROPERTY_RV_MERITS_VALUE']['TEXT'];
            $return['disadvantages'] = $resReview['PROPERTY_RV_DISADVANTAGES_VALUE']['TEXT'];
            $return['date'] = $resReview['DATE_CREATE'];
        }
    }

    return $return;
}

function isSaleNotifyMessage($event)
{
    return in_array(
        $event,
        [
            "SALE_NEW_ORDER",
            "SALE_ORDER_CANCEL",
            "SALE_ORDER_DELIVERY",
            "SALE_ORDER_PAID",
            "SALE_ORDER_SHIPMENT_STATUS_CHANGED",
            "SALE_ORDER_TRACKING_NUMBER",
            "SALE_STATUS_CHANGED_F",
            "SALE_STATUS_CHANGED_N",
        ]
    );
}

function cssAutoVersion($file)
{
    if (strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'].$file)) {
        return $file;
    }

    $modifyTime = filemtime($_SERVER['DOCUMENT_ROOT'].$file);

    return $file."?m={$modifyTime}";
}

class Helper
{
    const CATALOG_IB_ID = 6;
    const OFFERS_IB_ID = 7;
}

class GTM
{
    private static $resultDataJS = [];
    private static $additionalData = [];
    private static $basketItems = [];

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

        return \CUtil::PhpToJSObject(self::$resultDataJS);
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

        $userLoc = new UserLocation();
        $userLoc = $userLoc->getUserLocationInfo();
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

            $currency = !empty($basketItems) ? reset($basketItems)->getCurrency() : "";
            $currency = !empty($currency) ? $currency :
                \COption::GetOptionString("sale", "default_currency", "RUB");

            self::$resultDataJS["cart"] = [
                "currency" => $currency,
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
                self::setHomeData();
                break;
            case "product":
                self::setProductData();
                break;
            case "checkout":
                self::setCheckoutData();
                break;
            case "purchase":
                self::setPurchaseData();
                break;
            case "searchresults":
                self::setSearchResultsData();
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

    private static function setPurchaseData()
    {
    	$payment = self::$additionalData["payment"];
    	$order = \Bitrix\Sale\Order::load($payment["ORDER_ID"]);
    	if (empty($order)){
    		return false;
	    }
        $basket = $order->getBasket();
        self::setBasketItems($basket->getBasketItems());
        $basketProductsId = array_map("self::getProductIdCallback", $basket->getBasketItems());

	    self::$resultDataJS["transaction"] = [
		    "currency"     => $payment["CURRENCY"],
		    "id"           => (int) $payment["ID"],
		    "affiliation"  => "shop",
		    "revenue"      => (int) $payment["SUM"],
		    "shipping"     => (int) $order->getDeliveryPrice(),
		    "items" => self::getProductObjects($basketProductsId),
		    "recommend" => self::getProductObjects(self::getRecommendedIds($basketProductsId)),
		    "shippingType" => $order->getShipmentCollection()->current()->getDeliveryName(),
		    "paymentType"  => $payment["PAY_SYSTEM_NAME"],
		    "tax"          => $order->getTaxValue(),
	    ];
    }

		private static function getProductIdCallback($item) {
			/** @var \Bitrix\Sale\BasketItem $item */
			return (int) $item->getProductId();
		}

    private static function setHomeData()
    {
        return self::$resultDataJS["transaction"] = [

        ];
    }

    private static function setProductData()
    {
        return self::$resultDataJS["transaction"] = [

        ];
    }
    private static function setCheckoutData()
    {
        return self::$resultDataJS["checkout"] = [

        ];
    }

    private static function setSearchResultsData()
    {
        return self::$resultDataJS["transaction"] = [

        ];
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
                "IBLOCK_ID" => Helper::CATALOG_IB_ID,
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
                "PROPERTY_STOCK_PRODUCT",
                "PROPERTY_NEW_PRODUCT",
                "PROPERTY_PRODUCT_OF_THE_DAY",
            ]
        );

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

        $arProducts["ITEMS"] = array_values($arProducts["ITEMS"]);

        $arProducts = \Manom\Content::setCatalogItemsPrice($arProducts);
        $arProducts = $arProducts["ITEMS"];

        $domain = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

        foreach ($arProducts as $idx => $arProduct) {
            $image = reset(\Manom\Content::getCatalogItemImages($arProduct));

            $productObject = [
                "id" => $arProduct["ID"],
                "position" => $idx + 1,
//                "list" => "",
                "name" => $arProduct["NAME"],
                "url" => $domain . $arProduct["DETAIL_PAGE_URL"],
            ];

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
            self::setCategory($productObject, $arProduct);
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
        [$price, $oldPrice] = $arProduct["PRICE"]["PRICES"];
        $productObject["price"] = (float)$price;

        if ((float)$oldPrice > 0) {
            $productObject["priceOld"] = $oldPrice;
        }
    }

    private static function setCategory(&$productObject, $arProduct)
    {
        if (self::getPageType() !== "category") {
            return false;
        }

        $arSections = CIBlockSection::GetNavChain(false, $arProduct["IBLOCK_SECTION_ID"], ['ID', 'NAME', 'DEPTH_LEVEL'], true);

        foreach ($arSections as $arSection) {
            $productObject["category"][] = $arSection["NAME"];
            $productObject["categoryId"][] = $arSection["ID"];
        }

        return true;
    }

    private static function setVariant(&$productObject, $arProduct)
    {
        if ($arProduct["PROPERTY_STOCK_PRODUCT_VALUE"] === "Да") {
            $productObject["variant"][] = "Акция";
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

    static function getRecommendedIds($productsId, $grouped = false)
    {
        $commonRecommend = [];

        if (empty($productsId)) {
            return [];
        }

        $basketProducts = CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => Helper::CATALOG_IB_ID,
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

class MyHandlerClass
{
    function onExportOfferWriteDataHandler(&$tagResultList, $elementList, $context, $elements, $elementPropsList)
    {
        /** @var \Yandex\Market\Result\XmlNode $tagResult */
        /** @var SimpleXMLElement $element */
        /** @var SimpleXMLElement $outlets */
        /** @var SimpleXMLElement $outlet */

        foreach ($tagResultList as $offerId => $tagResult) {
            $quantityOffer = (int)$elementPropsList[$offerId]["catalog_product"]["QUANTITY"];
            $quantityOffer = $quantityOffer >= 0 ? $quantityOffer : 0;
            $element = $tagResult->getXmlElement();
            if (!($element instanceof SimpleXMLElement)) {
                continue;
            }
            $outlets = $element->addChild("outlets");

            if (!($outlets instanceof SimpleXMLElement)) {
                continue;
            }
            $outlet = $outlets->addChild("outlet");

            if (!($outlet instanceof SimpleXMLElement)) {
                continue;
            }

            $outlet->addAttribute("id", 0);
            $outlet->addAttribute("instock", $quantityOffer);
        }
    }

    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        unset($arFields["NAME"]);
        unset($arFields["IBLOCK_SECTION_ID"]);
        unset($arFields["IBLOCK_SECTION"]);
    }

    function OnBeforeProductUpdateHandler(&$arFields)
    {
        if ((int)$arFields["IBLOCK_ID"] !== 6) {
            return true;
        }

        $properties = \Bitrix\Iblock\PropertyTable::getList(
            [
                "filter" => [
                    "IBLOCK_ID" => 6,
                    "CODE" => [
                        "ONLY_CASH",
                        "ONLY_PICKUP",
                        "ONLY_PREPAYMENT",
                    ],
                ],
            ]
        );

        $arProps = [];

        while ($prop = $properties->fetch()) {
            $arProps[$prop["CODE"]] = $prop["ID"];
        }

        if (empty($arProps)) {
            return true;
        }

        if (!empty($arFields["PROPERTY_VALUES"][$arProps["ONLY_PREPAYMENT"]])
            && !empty($arFields["PROPERTY_VALUES"][$arProps["ONLY_CASH"]])) {
            global $APPLICATION;
            $APPLICATION->throwException("Нельзя ограничить по предоплате и наличным одновременно");
            return false;
        }
    }

    function OnSaleComponentOrderUserResultHandler(&$arUserResult, $request, &$arParams)
    {
        $orderProps = $request->toArray();
        Loader::includeModule("sale");
        $registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);

        if (!method_exists($registry, "getPropertyClassName")) {
            return true;
        }

        /** @var PropertyBase $propertyClassName */
        $propertyClassName = $registry->getPropertyClassName();

        if (!class_exists($propertyClassName)) {
            return true;
        }

        $locationProp = $propertyClassName::getList(
            [
                'select' => ['ID'],
                'filter' => [
                    '=PERSON_TYPE_ID' => $arUserResult["PERSON_TYPE_ID"],
                    'TYPE' => "LOCATION",
                ],
            ]
        )->fetch();

        $orderLocationProp = $orderProps["ORDER_PROP_".$locationProp["ID"]];
        $locationId = 0;

        if ((int)$orderLocationProp) {
            $locationId = (int)$orderLocationProp;
        }

        if (empty($locationId)) {
            $userLocation = (new UserLocation)->getUserLocationInfo();
            $locationId = (int)$userLocation["ID"];
        }

        if (!$locationId) {
            $locationId = 84;
        }

        $isMoscow = $locationId === 84;

        if (in_array($arUserResult["DELIVERY_ID"], [5, 8])) {
            $arUserResult["DELIVERY_ID"] = $isMoscow ? 8 : 5;
        }

        if (in_array($arUserResult["DELIVERY_ID"], [6, 13])) {
            $arUserResult["DELIVERY_ID"] = $isMoscow ? 13 : 6;
        }

        $profileId = $request->get('PROFILE_ID');

        if ($profileId !== null) {
            $arUserResult['PROFILE_ID'] = (int)$profileId;
        }

        $paySystemId = $request->get('PAY_SYSTEM_ID');
        if (!empty($paySystemId)) {
            $arUserResult["PAY_SYSTEM_ID"] = intval($paySystemId);
        }

        $deliveryId = $request->get('DELIVERY_ID');
        if (!empty($deliveryId)) {
            $arUserResult["DELIVERY_ID"] = $deliveryId;
        }

        $buyerStore = $request->get('BUYER_STORE');
        if (!empty($buyerStore)) {
            $arUserResult["BUYER_STORE"] = intval($buyerStore);
        }

        $deliveryExtraServices = $request->get('DELIVERY_EXTRA_SERVICES');
        if (!empty($deliveryExtraServices)) {
            $arUserResult["DELIVERY_EXTRA_SERVICES"] = $deliveryExtraServices;
        }

        if (strlen($request->get('ORDER_DESCRIPTION')) > 0) {
            $arUserResult["~ORDER_DESCRIPTION"] = $request->get('ORDER_DESCRIPTION');
            $arUserResult["ORDER_DESCRIPTION"] = htmlspecialcharsbx($request->get('ORDER_DESCRIPTION'));
        }

        if ($request->get('PAY_CURRENT_ACCOUNT') == "Y") {
            $arUserResult["PAY_CURRENT_ACCOUNT"] = "Y";
        }

        if ($request->get('confirmorder') == "Y") {
            $arUserResult["CONFIRM_ORDER"] = "Y";
            $arUserResult["FINAL_STEP"] = "Y";
        }

        $arUserResult["PROFILE_CHANGE"] = $request->get('profile_change') == "Y" ? "Y" : "N";
    }

    function OnBeforeIBlockElementAddHandler(&$arFields)
    {
        unset($arFields["IBLOCK_SECTION_ID"]);
        unset($arFields["SECTION_ID"]);
        unset($arFields["IBLOCK_SECTION"]);
        $arFields["IBLOCK_SECTION"] = array(204);
        $fp = fopen(__DIR__.'/filename.txt', 'w');
        fwrite($fp, print_r($arFields, true));
        fclose($fp);
        return;
    }

    function OnAfterIBlockSectionAddHandler(&$arFields)
    {
        $arFields["IBLOCK_SECTION_ID"] = 200;
        // CIBlockSection::Delete($arFields['ID']);
        // $fp = fopen(__DIR__.'/filename.txt', 'w');
        // fwrite($fp, print_r($arFields, TRUE));
        // fclose($fp);
    }

    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        try {
            if (!isSaleNotifyMessage($event) || empty($arFields["ORDER_ID"])) {
                throw new \Exception();
            }
            $order = \Bitrix\Sale\Order::load($arFields["ORDER_ID"]);
            $userId = $order->getUserId();
            $user = \Bitrix\Main\UserTable::getList(
                [
                    "filter" => [
                        "=ID" => $userId,
                    ],
                    "select" => [
                        "ID",
                        "EMAIL",
                    ],
                ]
            )->fetch();
            if (!empty($user)) {
                throw new \Exception();
            }

            $oneClick = $user["EMAIL"] === "oneclick@manom.ru";

            if ($oneClick) {
                $event .= "_ONE_CLICK";
            }
        } catch (\Exception $e) {
        }
        return true;
    }

    function OnSaleComponentOrderPropertiesHandler(&$arUserResult, $request, &$arParams, &$arResult)
    {
        Loader::includeModule("sale");
        $registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);

        if (!method_exists($registry, "getPropertyClassName")) {
            return true;
        }

        $propertyClassName = $registry->getPropertyClassName();

        if (!class_exists($propertyClassName)) {
            return true;
        }

        $properties = $propertyClassName::getList(
            [
                'select' => ['ID', "CODE"],
                'filter' => [
                    '=PERSON_TYPE_ID' => $arUserResult["PERSON_TYPE_ID"],
                    'CODE' => [
                        "LOCATION",
                        "ZIP",
                    ],
                ],
            ]
        )->fetchAll();

        foreach ($properties as $property) {
            if ($property["CODE"] === "LOCATION") {
                if (!empty($arUserResult["ORDER_PROP"][$property["ID"]])) {
                    continue;
                }

                $userLocation = (new UserLocation)->getUserLocationInfo();

                if ((int)$userLocation["ID"] <= 0) {
                    continue;
                }

                $arLocation = \Bitrix\Sale\Location\LocationTable::getList(
                    [
                        'select' => ['CODE'],
                        'filter' => ['ID' => $userLocation["ID"]],
                    ]
                )->fetch();

                if (empty($arLocation)) {
                    continue;
                }

                $arUserResult["ORDER_PROP"][$property["ID"]] = $arLocation['CODE'];
            } else {
                if ($property["CODE"] === "ZIP") {
                    if ((int)$arUserResult["ORDER_PROP"][$property["ID"]] <= 0) {
                        $arUserResult["ORDER_PROP"][$property["ID"]] = "000000";
                    }
                }
            }
        }
    }

    function reIndexSku($arFields)
    {
        if ((int)$arFields["IBLOCK_ID"] !== 6) {
            return true;
        }

        $existOffers = \CCatalogSKU::getExistOffers([$arFields["ID"]])[$arFields["ID"]];

        if (!$existOffers) {
            return true;
        }

        $offersList = \CCatalogSKU::getOffersList([$arFields["ID"]]);
        $offers = [];
        if (!empty($offersList[$arFields["ID"]])) {
            $offers = $offersList[$arFields["ID"]];
        }

        if (empty($offers)) {
            return true;
        }

        $activeProduct = $arFields["ACTIVE"] === "Y";

        foreach ($offers as $offer) {
            if ($activeProduct) {
                \CAllIBlockElement::UpdateSearch($offer["ID"], true);
            } else {
                \CSearch::DeleteIndex("iblock", $offer["ID"]);
            }
        }
    }

    function roistatOnSaleOrderBeforeSaved($entity)
    {
        $propertyCollection = $entity->getPropertyCollection();

        $visit = "no_cookie";
        if (isset($_COOKIE['roistat_visit'])) {
            $visit = $_COOKIE['roistat_visit'];
        }
        foreach ($propertyCollection as $property) {
            $code = $property->getField('CODE');
            switch ($code) {
                case 'ROISTAT':
                    $oldValue = $property->getValue();
                    if (empty($oldValue)) {
                        $property->setValue($visit);
                    }
                    break;
                case 'ROISTAT_TYPE':
                    $oldValue = $property->getValue();
                    if (empty($oldValue)) {
                        $property->setValue("Корзина");
                    }
                    break;
            }
        }
        //$order->getPropertyCollection()->save();
    }

    function checkTimeDelivery($entity)
    {
        //Тут ещё обрабатывается пустой почтовый индекс

        $orderFields = $entity->getFieldValues();

        $dateDelivery = $timeDelivery = null;
        $dateDeliveryExist = false;
        foreach ($entity->getPropertyCollection() as $property) {
            $propertyValue = $property->getFieldValues();

            if ($propertyValue["CODE"] === "DATE_DELIVERY") {
                $dateDelivery = $property->getValue();
                $dateDeliveryExist = true;
            }

            if ($propertyValue["CODE"] === "TIME_DELIVERY") {
                $timeDelivery = $property->getValue();
                if (is_array($timeDelivery)) {
                    $timeDelivery = $timeDelivery[0];
                }
                $dateDeliveryExist = true;
            }

            if ($propertyValue["CODE"] === "ZIP") {
                $zip = $property->getValue();
                if ((int)$zip <= 0) {
                    $property->setValue("000000");
                }
            }
        }

        if (!$dateDeliveryExist) {
            return;
        }

        $timeRanges = [
            1 => 6,
            2 => 9,
            3 => 12,
            4 => 15,
            5 => 18,
        ];

        $currentHour = (int)date("G");
        $isPast = false;

        if ($dateDelivery) {
            $isPast = strtotime($dateDelivery) === false;
            if (!$isPast) {
                $isPast = strtotime($dateDelivery) < strtotime(date('d.m.Y'));
            }
        }

        if (!$isPast && $dateDelivery === date('d.m.Y')) {
            foreach ($timeRanges as $key => $range) {
                if ($currentHour >= $range && $key === (int)$timeDelivery) {
                    $isPast = true;
                }
            }
        }

        if ($isPast) {
            return new Bitrix\Main\EventResult(
                Bitrix\Main\EventResult::ERROR,
                new Bitrix\Sale\ResultError("Дата или время доставки указаны некорректно"),
                'sale'
            );
        } elseif (!$dateDelivery && $timeDelivery) {
            foreach ($entity->getPropertyCollection() as $property) {
                $propertyValue = $property->getFieldValues();

                if ($propertyValue["CODE"] === "TIME_DELIVERY") {
                    $property->setValue("");
                }
            }
        }
    }

    function OnSaleOrderBeforeSavedHandler($arFields)
    {
        Loader::includeModule("catalog");
        $registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);

        $productsId = [];
        foreach ($arFields->getBasket() as $basketItem) {
            $productsId[] = $basketItem->getProductId();
        }

        $fieldValues = $arFields->getFieldValues();
        $currentPaySystem = (int)$fieldValues["PAY_SYSTEM_ID"];
        $currentDelivery = (int)$fieldValues["DELIVERY_ID"];
        $products = [];

        if (!empty($productsId)) {
            $productList = \CCatalogSKU::getProductList($productsId);
            $productList = array_map(
                function ($item) {
                    return $item["ID"];
                },
                $productList
            );

            $productList = array_unique(array_values($productList));

            $rsProducts = \CIBlockElement::GetList(
                [],
                [
                    "ID" => $productList,
                    "IBLOCK_ID" => 6,
                ],
                false,
                false,
                [
                    "ID",
                    "IBLOCK_ID",
                    "PROPERTY_ONLY_CASH",
                    "PROPERTY_ONLY_PICKUP",
                    "PROPERTY_ONLY_PREPAYMENT",
                ]
            );

            while ($arProduct = $rsProducts->GetNext()) {
                $products[] = $arProduct;
            }
        }

        foreach ($products as $product) {
            if (
                $product["PROPERTY_ONLY_PREPAYMENT_VALUE"] === "Y"
                && !in_array($currentPaySystem, [4, 9])) {
                return new Bitrix\Main\EventResult(
                    Bitrix\Main\EventResult::ERROR,
                    new Bitrix\Sale\ResultError("В корзине присутствует товар доступный только по предоплате"),
                    'sale'
                );
            }

            if (
                $product["PROPERTY_ONLY_PICKUP_VALUE"] === "Y"
                && !in_array($currentDelivery, [13, 6])) {
                return new Bitrix\Main\EventResult(
                    Bitrix\Main\EventResult::ERROR,
                    new Bitrix\Sale\ResultError("В корзине присутствует товар доступный только самовывозом"),
                    'sale'
                );
            }

            if (
                $product["PROPERTY_ONLY_CASH_VALUE"] === "Y"
                && $currentPaySystem !== 7) {
                return new Bitrix\Main\EventResult(
                    Bitrix\Main\EventResult::ERROR,
                    new Bitrix\Sale\ResultError("В корзине присутствует товар доступный для оплаты только наличными"),
                    'sale'
                );
            }
        }

        if (!method_exists($registry, "getPropertyClassName")) {
            return true;
        }

        /** @var PropertyBase $propertyClassName */
        $propertyClassName = $registry->getPropertyClassName();

        if (!class_exists($propertyClassName)) {
            return true;
        }

        $orderProperties = $arFields->getPropertyCollection()->getArray()["properties"];

        $properties = $propertyClassName::getList(
            [
                'select' => ['ID', "CODE"],
                'filter' => [
                    '=PERSON_TYPE_ID' => $arFields->getPersonTypeId(),
                    'CODE' => [
                        "COMMENT",
                    ],
                ],
            ]
        )->fetchAll();

        foreach ($properties as $property) {
            if ($property["CODE"] === "COMMENT") {
                foreach ($orderProperties as $orderProperty) {
                    if ($orderProperty["ID"] === $property["ID"]) {
                        $arFields->setField("COMMENTS", $orderProperty["VALUE"][0]);
                        break;
                    }
                }
                break;
            }
        }
    }
}

class CUserEx
{
    public static $newUserPass;

    function OnBeforeUserLogin($arFields)
    {
        $filter = Array("EMAIL" => $arFields["LOGIN"]);
        $rsUsers = CUser::GetList(($by = "LAST_NAME"), ($order = "asc"), $filter);
        if ($user = $rsUsers->GetNext()) {
            $arFields["LOGIN"] = $user["LOGIN"];
        }
        /*else $arFields["LOGIN"] = "";*/
    }

    function OnBeforeUserRegister($arFields)
    {
        $arFields["LOGIN"] = $arFields["EMAIL"];
    }

    function OnAfterUserAddHandler($arFields)
    {
        self::$newUserPass = $arFields["CONFIRM_PASSWORD"];
    }

    function OnSendUserInfoHandler($arFields)
    {
        $arFields["FIELDS"]["PASSWORD"] = "Пароль: ".self::$newUserPass."\n";
        self::$newUserPass = null;
    }
}

class UserHandler
{
    function afterRegister(&$arFields)
    {
        $new_password = randString(10);
        $user = new CUser;
        $fields = Array(
            "PASSWORD" => $new_password,
            "CONFIRM_PASSWORD" => $new_password,
        );
        $user->Update($arFields['ID'], $fields);
        $msgData = array(
            'EMAIL' => $arFields['EMAIL'],
            'PASS' => $new_password,
        );
        CEvent::Send("USER_INFO", s1, $msgData, 'Y', 2);
    }
}

class UserLocation
{
    public function selectUserLocatonCityID($cityID)
    {
        $return = $this->getCityInfoByID($cityID);
        if ($return) {
            $this->setUserLocationCityID($return['ID']);
            $this->setUserSpecify(true);
        }
    }

    public function getUserLocationInfo()
    {
        $return = false;
        $cityID = $this->getUserLocationCityID();
        if ($cityID) {
            $return = $this->getCityInfoByID($cityID);
        }
        if (!$return) {
            $return = $this->getUserLocationByGeo();
            if (!$return) {
                $return = $this->getDefaultValue();
            }
        }
        return $return;
    }

    public function getListCity($name, $count = 10)
    {
        $return = array();
        $db_vars = CSaleLocation::GetList(
            array(
                "SORT" => "ASC",
                "COUNTRY_NAME_LANG" => "ASC",
                "CITY_NAME_LANG" => "ASC",
            ),
            array(
                "LID" => LANGUAGE_ID,
                "%CITY_NAME" => (string)$name,
            ),
            false,
            array('nTopCount' => $count),
            array()
        );
        while ($vars = $db_vars->Fetch()) {
            $return[] = array(
                "title" => $vars['CITY_NAME'],
                "id" => $vars['ID'],
            );
        }
        return $return;
    }

    public function getUserSpecifyStatus()
    {
        $status = (bool)$this->getUserSpecify();
        return $status;
    }

    public function changeStatusSpecify()
    {
        $this->setUserSpecify(true);
    }

    public function getDefaultListOfCity()
    {
        $return = [];
        $db_vars = CSaleLocation::GetList(
            array(
                "SORT" => "ASC",
                "COUNTRY_NAME_LANG" => "ASC",
                "CITY_NAME_LANG" => "ASC",
            ),
            array(
                "LID" => LANGUAGE_ID,
                "CITY_NAME" => array(
                    'Москва',
                    'Санкт-Петербург',
                    'Екатеринбург',
                    'Нижний Новгород',
                    'Новосибирск',
                    'Казань',
                ),
            ),
            false,
            false,
            array()
        );
        while ($vars = $db_vars->Fetch()) {
            $return[] = array(
                "title" => $vars['CITY_NAME'],
                "id" => $vars['ID'],
            );
        }
        return $return;
    }

    private function getUserSpecify()
    {
        global $APPLICATION;
        $status = $APPLICATION->get_cookie("USER_LOCATION_SPECIFY_STATUS");
        return $status;
    }

    private function setUserSpecify($status)
    {
        $this->addCookie('USER_LOCATION_SPECIFY_STATUS', $status);
    }

    private function getDefaultValue()
    {
        $return = false;
        $db_vars = CSaleLocation::GetList(
            array(
                "SORT" => "ASC",
                "COUNTRY_NAME_LANG" => "ASC",
                "CITY_NAME_LANG" => "ASC",
            ),
            array(
                "LID" => LANGUAGE_ID,
                "CITY_NAME" => 'Москва',
            ),
            false,
            false,
            array()
        );
        if ($vars = $db_vars->Fetch()) {
            $return = $vars;
            $this->setUserLocationCityID($return['ID']);
        }
        $this->setUserSpecify(false);
        return $return;
    }

    private function getUserLocationByGeo()
    {
        $return = false;
        $application = Application::getInstance();
        $location = Location::getInstance(Location::getCurIp());
        if ($location->getCityName()) {
            $cityInfo = $this->getCityInfoByName($location->getCityName());
            if ($cityInfo) {
                $return = $cityInfo;
                $this->setUserLocationCityID($cityInfo['ID']);
            }
        }
        $this->setUserSpecify(false);
        return $return;
    }

    private function getUserLocationCityID()
    {
        global $APPLICATION;
        $cityID = $APPLICATION->get_cookie("USER_LOCATION_CITY_ID");
        return $cityID;
    }

    private function setUserLocationCityID($cityID)
    {
        if ($cityID && $this->getCityInfoByID($cityID)) {
            $this->addCookie('USER_LOCATION_CITY_ID', $cityID);
        }
    }

    private function getCityInfoByID($cityID)
    {
        $return = false;
        $db_vars = CSaleLocation::GetList(
            array(
                "SORT" => "ASC",
                "COUNTRY_NAME_LANG" => "ASC",
                "CITY_NAME_LANG" => "ASC",
            ),
            array(
                "LID" => LANGUAGE_ID,
                "ID" => $cityID,
            ),
            false,
            false,
            array()
        );
        if ($vars = $db_vars->Fetch()) {
            $return = $vars;
        }
        return $return;
    }

    private function getCityInfoByName($cityName)
    {
        $return = false;
        $db_vars = CSaleLocation::GetList(
            array(
                "SORT" => "ASC",
                "COUNTRY_NAME_LANG" => "ASC",
                "CITY_NAME_LANG" => "ASC",
            ),
            array(
                "LID" => LANGUAGE_ID,
                "CITY_NAME" => $cityName,
            ),
            false,
            false,
            array()
        );
        if ($vars = $db_vars->Fetch()) {
            $return = $vars;
        }
        return $return;
    }

    private function addCookie($code, $val)
    {
        $application = Application::getInstance();
        $context = $application->getContext();
        $cookie = new Cookie($code, $val, time() + 60 * 60 * 24 * 30 * 12 * 2);
        $cookie->setDomain($context->getServer()->getHttpHost());
        $cookie->setHttpOnly(false);
        $context->getResponse()->addCookie($cookie);
        $context->getResponse()->flush("");
    }
}
