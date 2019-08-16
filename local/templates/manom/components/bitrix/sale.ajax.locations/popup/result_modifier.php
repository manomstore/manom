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


$regionId = $arParams["REGION"];

if ($regionId > 0) {
    $regionName = "";

    foreach ($arResult["REGION_LIST"] as $region) {
        if ((int)$region["ID"] === (int)$regionId) {
            if (!empty($region["NAME_LANG"])) {
                $regionName = $region["NAME_LANG"];
            }
            if (empty($regionName)) {
                $regionName = $region["NAME"];
            }
            break;
        }
    }

    $arResult["LOCATION_STRING"] = str_replace(", " . $regionName, "", $arResult["LOCATION_STRING"]);
}