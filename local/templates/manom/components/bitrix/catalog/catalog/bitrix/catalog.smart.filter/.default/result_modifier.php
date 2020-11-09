<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
foreach ($arResult['ITEMS'] as &$item) {
    if ($item["CODE"] === "memory_size") {
        foreach ($item["VALUES"] as &$value) {
            $value["NUM_VALUE"] = (int)preg_replace('~\D+~', '', $value["VALUE"]);
        }
        unset($value);

        uasort($item["VALUES"], function ($a, $b) {
            if ($a["NUM_VALUE"] == $b["NUM_VALUE"]) {
                return 0;
            }
            return ($a["NUM_VALUE"] < $b["NUM_VALUE"]) ? -1 : 1;
        });
    }
}

unset($item);

$arResult["HAS_FILTER_ELEMENT"] = false;
foreach ($arResult['ITEMS'] as $item) {
    if (isset($item['PRICE'])) {
        if ($item['VALUES']['MAX']['VALUE'] - $item['VALUES']['MIN']['VALUE'] <= 0) {
            continue;
        }

        $arResult["HAS_FILTER_ELEMENT"] = true;
    }

    if (isset($item['PRICE']) || !$item['DISPLAY_TYPE'] || count((array)$item['VALUES']) <= 1) {
        continue;
    }

    $arResult["HAS_FILTER_ELEMENT"] = true;
}

$this->__component->SetResultCacheKeys(["HAS_FILTER_ELEMENT"]);