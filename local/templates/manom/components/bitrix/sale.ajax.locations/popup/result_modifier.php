<?php
$countryId = $arParams["COUNTRY"];
$countryName = "";

foreach ($arResult["COUNTRY_LIST"] as $country) {
    if ((int)$country["ID"] === (int)$countryId) {
        if (!empty($country["NAME_LANG"])) {
            $countryName = $country["NAME_LANG"];
        }
        if (empty($countryName)) {
            $countryName = $country["NAME"];
        }
        break;
    }
}

$arResult["LOCATION_STRING"] = str_replace(", " . $countryName, "", $arResult["LOCATION_STRING"]);