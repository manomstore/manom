<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

foreach ($arResult as $key => &$item) {
    $item["notLink"] = $item["PARAMS"]["type"] === "brands";
    $item["disabled"] = $item["PARAMS"]["type"] === "service";
    $item["itemId"] = $key + 1;
    $item["hasChildren"] = !empty($item["PARAMS"]["submenu"]);
}
unset($item);
