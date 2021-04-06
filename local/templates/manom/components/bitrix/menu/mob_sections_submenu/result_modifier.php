<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$secondItems = [];
$thirdItems = [];

foreach ($arResult as $key => $item) {
    $item["itemId"] = $key + 1;

    if (!empty($item["PARAMS"]["submenu"])) {
        foreach ($item["PARAMS"]["submenu"] as $submenuKey => &$submenuItem) {
            $submenuItem["itemId"] = $submenuKey + 1;
            $submenuItem["parentItemId"] = $item["itemId"];
            if (!empty($submenuItem["children"])) {
                foreach ($submenuItem["children"] as $childrenKey => &$children) {
                    $children["itemId"] = $childrenKey + 1;
                    $children["parentItemId"] = $submenuItem["itemId"];
                }
                unset($children);
            }
            $thirdItems[] = $submenuItem;
        }
        unset($submenuItem);
    }

    $item["children"] = $item["PARAMS"]["submenu"];
    $secondItems[] = $item;
}

$clearCallback = function ($item) {
    return !empty($item["children"]) ? $item : null;
};

$secondItems = array_values(array_filter(array_map($clearCallback, $secondItems)));
$thirdItems = array_values(array_filter(array_map($clearCallback, $thirdItems)));
$arResult = [
    "SECOND_ITEMS" => $secondItems,
    "THIRD_ITEMS"  => $thirdItems,
];