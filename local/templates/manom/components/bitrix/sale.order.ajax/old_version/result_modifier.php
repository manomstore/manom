<?php
global $USER;

if (!$USER->IsAuthorized()) {
    foreach ($arResult["DELIVERY"] as &$delivery) {
        $delivery["CHECKED"] = "N";
    }
}

$isMoscowLocations = false;

foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as $property) {
    if ($property["CODE"] === "LOCATION") {
        $isMoscowLocations = (int)$property["VALUE"] === 84;
        break;
    }
}

if (!$isMoscowLocations) {
    foreach ($arResult["ORDER_PROP"]["USER_PROPS_Y"] as &$property) {
        if (in_array($property["CODE"], ["DATE_DELIVERY", "TIME_DELIVERY"])) {
            $property = null;
        }
    }
    $arResult["ORDER_PROP"]["USER_PROPS_Y"] = array_filter($arResult["ORDER_PROP"]["USER_PROPS_Y"]);

    unset($property);
}
