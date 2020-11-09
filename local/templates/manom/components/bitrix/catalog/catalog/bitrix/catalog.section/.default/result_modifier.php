<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Content;

$arResult = Content::setCatalogItemsEcommerceData($arResult);

$arParams["IS_SEARCH"] = $arParams["IS_SEARCH"] === "Y";

$itemsId = array();
foreach ($arResult['ITEMS'] as $item) {
    if (!in_array((int)$item['ID'], $itemsId, true)) {
        $itemsId[] = (int)$item['ID'];
    }
}

$rating = array();
if (!empty($itemsId)) {
    $rating = getRatingAndCountReviewForList($itemsId);
}

$items = array();
foreach ($arResult['ITEMS'] as $item) {
    $productId = (int)$item['ID'];
    $canBuy = (bool)$item['CAN_BUY'];
    if (!empty($item['OFFERS'])) {
        $productId = (int)$item['PRICE']['OFFER_ID'];

        foreach ($item['OFFERS'] as $offer) {
            if ((int)$offer['ID'] !== $productId) {
                continue;
            }

            $canBuy = (bool)$offer['CAN_BUY'];
        }
    }

    $images = Content::getCatalogItemImages($item);

    $properties = array();
    foreach ($item['DISPLAY_PROPERTIES'] as $property) {
        if (is_array($property['VALUE'])) {
            continue;
        }

        $properties[] = $property;
    }

    if (empty($properties) && !empty($item['OFFERS'])) {
        foreach ($item['OFFERS'] as $offer) {
            foreach ($offer['DISPLAY_PROPERTIES'] as $property) {
                if (is_array($property['VALUE'])) {
                    continue;
                }

                $properties[] = $property;
            }
        }
    }

    $items[] = array(
        'id' => (int)$item['ID'],
        'productId' => $productId,
        'name' => $item['NAME'],
        'url' => $item['DETAIL_PAGE_URL'],
        'images' => $images,
        'properties' => $properties,
        'price' => $item['price'],
        'oldPrice' => $item['oldPrice'],
        'canBuy' => $canBuy,
        'productOfTheDay' => $item['PROPERTIES']['PRODUCT_OF_THE_DAY']['VALUE'] === 'Да',
        'sale' => $item['PROPERTIES']['SELL_PROD']['VALUE'] === 'Да',
        'inFavoriteAndCompare' => checkProdInFavoriteAndCompareList((int)$item['ID'], 'UF_FAVORITE_ID'),
        'rating' => $rating[(int)$item['ID']],
    );
}

$arResult['ITEMS'] = $items;
$arResult['GTM_DATA'] = [
    "items" => $arResult['ELEMENTS'],
    "resultCount" => (int)$arResult["NAV_RESULT"]->NavRecordCount,
    "pageCount" => (int)$arResult["NAV_RESULT"]->NavPageCount,
    "currentPage" => (int)$arResult["NAV_RESULT"]->NavPageNomer,
    "categoryId" => (int)$arResult["ID"],
];
$arResult['GTM_PAGE_TYPE'] = $arParams["IS_SEARCH"] ? "searchresults" : "category";

if ((int)$arResult["ID"]) {
    $sectionProps = \CIBlockSection::GetList(
        [],
        [
            "IBLOCK_ID" => $arResult["IBLOCK_ID"],
            "ID" => $arResult["ID"]
        ],
        false,
        [
            "ID",
            "IBLOCK_ID",
            "UF_LOGO"
        ]
    )->GetNext();
}

if (!empty($sectionProps["UF_LOGO"])) {
    $arResult["BRAND_LOGO"] = \CFile::GetFileArray($sectionProps["UF_LOGO"])["SRC"];
}

$parentSection = false;
if ((int)$arResult["IBLOCK_SECTION_ID"]) {
    $parentSection = \CIBlockSection::GetByID($arResult["IBLOCK_SECTION_ID"])->GetNext();
}

$arResult['PARENT_SECTION'] = $parentSection;

$arParams["HIDE_SMART_FILTER"] = $arParams["HIDE_SMART_FILTER"] === true;
