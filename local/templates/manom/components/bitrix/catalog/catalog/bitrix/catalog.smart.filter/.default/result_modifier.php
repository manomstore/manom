<?php

use Manom\References\Brand;
use Manom\Related;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$brand = new Brand();
$filterValues = [];

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

    if ($item["CODE"] === "brand") {
        foreach ($item["VALUES"] as &$value) {
            if ($brand->exist($value["VALUE"])) {
                $brandData = $brand->getByName($value["VALUE"]);
                $value["SORT"] = $brandData["sort"];
            };
        }
        unset($value);

        uasort($item["VALUES"], function ($a, $b) {
            $a["SORT"] = (int)$a["SORT"];
            $b["SORT"] = (int)$b["SORT"];

            if ($a["SORT"] == $b["SORT"]) {
                return 0;
            }
            return ($a["SORT"] < $b["SORT"]) ? -1 : 1;
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

    if (in_array($item["CODE"], ["brand", "at_seriya"])) {
        array_walk($item["VALUES"], function ($item) use (&$filterValues) {
            $filterValues[] = $item["VALUE"];
        });
    }

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
}

$arResult["FILTER_VALUES"] = $filterValues;

unset($item);

$arResult["HAS_FILTER_ELEMENT"] = false;
foreach ($arResult['ITEMS'] as &$item) {
    if (isset($item['PRICE'])) {
        if ($item['VALUES']['MAX']['VALUE'] - $item['VALUES']['MIN']['VALUE'] <= 0) {
            continue;
        }

        $item["PRECISION"] = $item['DECIMALS'] ?: 0;
        $item["MIN_VAL"] = $item['VALUES']['MIN']['VALUE'];
        $item["MAX_VAL"] = $item['VALUES']['MAX']['VALUE'];
        $item["MIN_VAL"] = $item["MIN_VAL"] > 0 ? $item["MIN_VAL"] : 1;
        $item["MAX_VAL"] = $item["MAX_VAL"] > 0 ? $item["MAX_VAL"] : 1;

        $rangeSize = $item["MAX_VAL"] - $item["MIN_VAL"];
        $item["STEP_SIZE"] = 0;
        if ($rangeSize > 1) {
            $percent = 0;

            for ($i = 1; $i < $rangeSize; $i++) {
                if ($rangeSize % $i === 0) {
                    $percent = ($i / $rangeSize) * 100;
                    if ($percent <= 5) {
                        $item["STEP_SIZE"] = $i;
                    } else {
                        break;
                    }
                }
            }
        }

        if (!$item["STEP_SIZE"]) {
            $item["STEP_SIZE"] = 1000;
        }

        $arResult["HAS_FILTER_ELEMENT"] = true;
    }

    if (isset($item['PRICE']) || !$item['DISPLAY_TYPE'] || count((array)$item['VALUES']) <= 1) {
        continue;
    }

    $arResult["HAS_FILTER_ELEMENT"] = true;
}
unset($item);

$this->__component->SetResultCacheKeys(["HAS_FILTER_ELEMENT", "FILTER_VALUES"]);