<?
if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
    die();
}

global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent(
    "bitrix:menu.sections",
    "",
    array(
        "IS_SEF" => "Y",
        "SEF_BASE_URL" => "",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => 6,
        "DEPTH_LEVEL" => "2",
        "CACHE_TYPE" => "N",
    ), false
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>

