<?php

use \Manom\Content;
use Manom\Content\Section;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['BANNER'] = Content::getSectionBanner((int)$arResult['SECTION']['ID']);

if (!empty($arParams["BRAND_DATA"] && $arParams["BRAND_DATA"]["code"])) {
    foreach ($arResult["SECTIONS"] as &$section) {
        $section["SECTION_PAGE_URL"] = $section["SECTION_PAGE_URL"] . "brand/{$arParams["BRAND_DATA"]["code"]}/";
    }

    unset($section);
}

$obSection = new Section();
$obSection->checkEmptySectionsOnLevel();

$arParams["FILTER_VALUES"] = is_array($arParams["FILTER_VALUES"]) ? $arParams["FILTER_VALUES"] : [];
$filterValues = array_map(function ($value) {
    return strtolower(trim($value));
}, $arParams["FILTER_VALUES"]);

$arResult["SECTIONS"] = array_filter($arResult["SECTIONS"], function ($section) use ($filterValues) {
    $sectionName = strtolower(trim($section["NAME"]));
    $searchKey = array_search($sectionName, $filterValues);

    return !array_key_exists($searchKey, $filterValues);
});

$arResult["SECTIONS"] = array_filter($arResult["SECTIONS"], function ($section) use ($obSection) {
    return !$obSection->isDisabled((int)$section["ID"]);
});