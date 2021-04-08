<?php

use \Manom\Content;
use Manom\Content\Section;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['BANNER'] = Content::getSectionBanner((int)$arResult['SECTION']['ID']);

if (!empty($arParams["PREFIX"])) {
    foreach ($arResult["SECTIONS"] as &$section) {
        $sectionPath = str_replace($section["LIST_PAGE_URL"], "", $section["SECTION_PAGE_URL"]);
        $section["SECTION_PAGE_URL"] = $section["LIST_PAGE_URL"] . $arParams["PREFIX"] . $sectionPath;
    }

    unset($section);
}

$obSection = new Section();
$obSection->checkEmptySectionsMaxLevel();

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