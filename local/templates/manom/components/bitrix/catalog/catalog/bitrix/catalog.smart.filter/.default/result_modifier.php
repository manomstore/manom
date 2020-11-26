<?php

use Manom\Related;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
foreach ($arResult['ITEMS'] as &$item) {
    if (!isset($item['PRICE'])) {
        $counter = 0;
        array_walk($item["VALUES"], function (&$value, $key, &$counter) {
            $counter++;
            $value["SHOW"] = $counter <= 5 || $value["CHECKED"];

        }, $counter);

        $item["SHOW_MORE"] = count(array_filter($item["VALUES"], function ($value) {
                return $value["SHOW"] === false;
            })) >= 1;
    }
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

    if ($item["CODE"] === "color") {
        try {
            $related = new Related();
            $item["VALUES"] = array_map(function ($item) {
                $item["value"] = $item["VALUE"];
                return $item;
            }, $item["VALUES"]);

            $item["VALUES"] = $related->processColors($item["VALUES"]);
        } catch (\Exception $e) {
            $item["VALUES"] = [];
        }
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