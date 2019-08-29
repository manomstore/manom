<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResultNew = $arParents = [];

foreach ($arResult as &$arItem) {
    $arItem['CHILDREN'] = [];
    if (isset($arParents[$arItem['DEPTH_LEVEL']])) {
        unset($arParents[$arItem['DEPTH_LEVEL']]);
    }
    $arParents[$arItem['DEPTH_LEVEL']] = &$arItem;
    if ($arItem['DEPTH_LEVEL'] > 1) {
        $arParents[$arItem['DEPTH_LEVEL'] - 1]['CHILDREN'][] = &$arItem;
    } else {
        $arResultNew[] = &$arItem;
    }

}
unset($arItem);

$arResultNewOnlySubmenu = [];
$key = 0;

foreach ($arResultNew as $arItem) {
    $key++;
    $arItem["ITEM_SUBMENU_ID"] = $key;
    if (!empty($arItem["CHILDREN"])) {
        $arResultNewOnlySubmenu[] = $arItem;
    }
}

$arResult = $arResultNewOnlySubmenu;

