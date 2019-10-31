<?php
global $USER;

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

foreach ($arResult["GRID"]["ROWS"] as &$row) {
    $dataAttrs = [];
    $dataAttrs["id"] = $row["data"]["ID"];
    $dataAttrs["name"] = $row["data"]["NAME"];
    $dataAttrs["image"] = $row["data"]["PREVIEW_PICTURE_SRC"];
    $dataAttrs["sum"] = $row["data"]["SUM"];
    $dataAttrs["oldSum"] = $row["data"]["SUM_BASE_FORMATED"];
    $dataAttrs["quantity"] = $row["data"]["QUANTITY"];
    $dataAttrs["existDiscount"] = $row["data"]["DISCOUNT_PRICE_PERCENT"] > 0;
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