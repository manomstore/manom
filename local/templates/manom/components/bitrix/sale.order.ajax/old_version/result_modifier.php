<?php
global $USER;

use Manom\Product;
use Manom\Store\StoreData;

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if ((!$USER->IsAuthorized() && $request->get("is_ajax_post") !== "Y") || $request->get("isChangeLocation") === "Y") {
    foreach ($arResult["DELIVERY"] as &$delivery) {
        $delivery["CHECKED"] = "N";
    }
}

$isMoscowLocations = false;
$pickUpShop = (int)$arResult["ORDER_DATA"]["DELIVERY_ID"] === 13;

foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $property) {
    if ($property["CODE"] === "LOCATION") {
        $isMoscowLocations = (int)$property["VALUE"] === 84;
        break;
    }
}

if (!$isMoscowLocations || $pickUpShop) {
    foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as &$property) {
        if (in_array($property["CODE"], ["DATE_DELIVERY", "TIME_DELIVERY"])) {
            $property = null;
        }
    }
    $arResult["ORDER_PROP"]["USER_PROPS_Y"] = array_filter($arResult["ORDER_PROP"]["USER_PROPS_Y"]);

    unset($property);
}

$totalQuantity = 0;

$productIds = array_map(function ($item) {
    return $item["data"]["PRODUCT_ID"];
}, $arResult["GRID"]["ROWS"]);

$product = new Product();
$ecommerceData = $product->getEcommerceData(array_values($productIds), 6);

foreach ($arResult["GRID"]["ROWS"] as &$row) {
    /** @var StoreData $storeData */
    $storeData = $ecommerceData[$row["data"]["PRODUCT_ID"]]["storeData"];
    $mainStore = $storeData->getMain();
    $rrcStore = $storeData->getRrc();
    $prices = $storeData->getPrices();

    $price = $row["data"]['PRICE'];
    $oldPrice = 0;

    if ((int)$mainStore['price']['ID'] === (int)$row["data"]['PRODUCT_PRICE_ID']) {
        $price = $mainStore['price']['PRICE'];
        $oldPrice = $prices["oldPrice"];
    } elseif ((int)$rrcStore['price']['ID'] === (int)$row["data"]['PRODUCT_PRICE_ID']) {
        $price = $rrcStore['price']['PRICE'];
    }

    $dataAttrs = [];
    $dataAttrs["id"] = $row["data"]["ID"];
    $dataAttrs["name"] = $row["data"]["NAME"];
    $dataAttrs["image"] = $row["data"]["PREVIEW_PICTURE_SRC"];
    $sum = (int)$row["data"]["QUANTITY"] * $price;
    $oldSum = (int)$row["data"]["QUANTITY"] * $oldPrice;
    $dataAttrs["sum"] = SaleFormatCurrency($sum, $arResult["BASE_LANG_CURRENCY"]);
    $dataAttrs["isService"] = $ecommerceData[$row["data"]["PRODUCT_ID"]]["storeData"];
    if ($dataAttrs["isService"] && $sum <= 0) {
        $dataAttrs["sum"] = "Бесплатно";
    }
    $dataAttrs["oldSum"] = SaleFormatCurrency($oldSum, $arResult["BASE_LANG_CURRENCY"]);
    $dataAttrs["quantity"] = $row["data"]["QUANTITY"];
    $dataAttrs["existDiscount"] = !empty((int)$dataAttrs['oldSum']) &&
        (int)$dataAttrs['sum'] !== (int)$dataAttrs['oldSum'];
    $dataAttrs["onlyCash"] = $row["data"]["PROPERTY_ONLY_CASH_VALUE"] === "Y";
    $dataAttrs["onlyPrepayment"] = $row["data"]["PROPERTY_ONLY_PREPAYMENT_VALUE"] === "Y";
    $dataAttrs["onlyPickup"] = $row["data"]["PROPERTY_ONLY_PICKUP_VALUE"] === "Y";
    $dataAttrs["model"] = $row["data"]["PROPERTY_this_prod_model_VALUE"];
    $row["DATA_ATTRS"] = htmlspecialchars(json_encode($dataAttrs));
    $totalQuantity += (int)$row["data"]["QUANTITY"];
}

unset($row);

$arResult["TOTAL_DATA_ATTRS"] = [
    "productsSum" => $arResult["ORDER_PRICE_FORMATED"],
    "discountSum" => $arResult["DISCOUNT_PRICE_FORMATED"],
    "deliverySum" => $arResult["DELIVERY_PRICE_FORMATED"],
    "totalSum" => $arResult["ORDER_TOTAL_PRICE_FORMATED"],
    "existDiscount" => $arResult["DISCOUNT_PERCENT"] > 0,
    "totalQuantity" => $totalQuantity,
];

$arResult["TOTAL_DATA_ATTRS"] = htmlspecialchars(json_encode($arResult["TOTAL_DATA_ATTRS"]));