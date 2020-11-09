<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Content;

$arResult = Content::setCatalogItemsEcommerceData($arResult);

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
                if (is_array($property['DISPLAY_VALUE'])) {
                    $property['DISPLAY_VALUE'] = implode(',', $property['DISPLAY_VALUE']);
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
        'newProduct' => $item['PROPERTIES']['NEW_PRODUCT']['VALUE'] === 'Да',
        'sale' => $item['PROPERTIES']['SELL_PROD']['VALUE'] === 'Да' || $item['showOldPrice'],
        'showOldPrice' => $item['showOldPrice'],
        'inFavoriteAndCompare' => checkProdInFavoriteAndCompareList((int)$item['ID'], 'UF_FAVORITE_ID'),
        'rating' => $rating[(int)$item['ID']],
    );
}

$arResult['ITEMS'] = $items;
