<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Manom\References\Brand;
use Manom\Content\Section;

$arResultNew = $arParents = [];

$section = new Section();
$section->checkEmptySectionsOnLevel(2);

foreach ($arResult as &$arItem) {
    $arItem["DISABLED"] = $section->isDisabled($arItem["PARAMS"]["SECTION_ID"]);
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

$arResult = $arResultNew;

try {
    $brand = new Brand();
} catch (\Exception $e) {
    $brand = false;
}

if ($brand) {
    foreach ($arResult as &$arItem) {
        $sectionCode = str_replace(['/catalog/', '/'], '', $arItem["LINK"]);
        $arItem["CODE"] = $sectionCode;
        $arItem["BRANDS"] = $brand->getForSection($sectionCode);
    }
    unset($arItem);
}

foreach ($arResult as &$arItem) {
    $arItem["CHILDREN"] = array_chunk($arItem["CHILDREN"], 8);
    $arItem["BRANDS"] = array_chunk($arItem["BRANDS"], 8);
}
unset($arItem);
