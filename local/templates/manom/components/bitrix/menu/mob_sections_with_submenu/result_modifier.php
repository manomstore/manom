<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$key = 0;
foreach ($arResult as &$item) {
    $item["notLink"] = $item["PARAMS"]["type"] === "brands";
    $item["disabled"] = $item["PARAMS"]["type"] === "service";
    $key++;
    $item["ITEM_MENU_ID"] = $key;
}
unset($item);
