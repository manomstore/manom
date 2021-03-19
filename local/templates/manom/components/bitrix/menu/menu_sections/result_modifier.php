<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

foreach ($arResult as &$item) {
    $item["notLink"] = $item["PARAMS"]["type"] === "brands";
    $item["disabled"] = $item["PARAMS"]["type"] === "service";
}
unset($item);