<?php

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
}

unset($item);