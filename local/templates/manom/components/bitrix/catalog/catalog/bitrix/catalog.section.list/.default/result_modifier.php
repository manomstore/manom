<?php

use \Manom\Content;

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