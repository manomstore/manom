<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

foreach ($arResult as &$item) {
    $item["notLink"] = $item["PARAMS"]["type"] === "brands";
    $item["disabled"] = $item["PARAMS"]["type"] === "service";
}
unset($item);

$arResultNewOnlySubmenu = [];
$key = 0;

foreach ($arResult as $arItem) {
    $key++;
    $arItem["ITEM_SUBMENU_ID"] = $key;
    if (!empty($arItem["PARAMS"]["submenu"])) {
        $arResultNewOnlySubmenu[] = $arItem;
    }
}

$arResult = $arResultNewOnlySubmenu;

