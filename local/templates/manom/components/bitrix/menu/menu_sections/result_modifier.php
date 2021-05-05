<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @param $children
 * @return string
 */
function getGridClass($children, $maxInColummn): string
{
    $class = "";
    if (!is_array($children)) {
        return $class;
    }

    $countChildren = count($children);

    if ($countChildren >= $maxInColummn * 3) {
        $class = "grid-3";
    } elseif ($countChildren >= $maxInColummn * 2) {
        $class = "grid-2";
    } elseif ($countChildren >= $maxInColummn) {
        $class = "grid";
    }

    return $class;
}

foreach ($arResult as &$item) {
    $item["notLink"] = $item["PARAMS"]["type"] === "brands";
    $item["disabled"] = $item["PARAMS"]["type"] === "service";
    $item["gridClass"] = getGridClass($item["PARAMS"]["submenu"], 8);
    foreach ($item["PARAMS"]["submenu"] as &$submenuItem) {
        $submenuItem["gridClass"] = getGridClass($submenuItem["children"], 6);
    }
    unset($submenuItem);
}
unset($item);