<?php

use \Manom\Content;

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