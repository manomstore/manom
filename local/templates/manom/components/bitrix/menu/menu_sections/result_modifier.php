<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Content\Section;

$arResultNew = $arParents = [];

$section = new Section();
$section->checkEmptySectionsOnLevel(2);

foreach ($arResult as &$arItem) {
    $arItem["DISABLED"] = $section->isDisabled($arItem["PARAMS"]["SECTION_ID"]);
    $arItem['CHILDREN'] = [];
    if ( isset($arParents[$arItem['DEPTH_LEVEL']]) ) {
        unset($arParents[$arItem['DEPTH_LEVEL']]);
    }
    $arParents[$arItem['DEPTH_LEVEL']] = &$arItem;
    if ( $arItem['DEPTH_LEVEL'] > 1 ) {
        $arParents[$arItem['DEPTH_LEVEL'] - 1]['CHILDREN'][] = &$arItem;
    } else {
        $arResultNew[] = &$arItem;
    }

}
unset($arItem);

$arResult = $arResultNew;

