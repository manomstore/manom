<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Content;

$arResult = Content::setCatalogItemsEcommerceData($arResult);

$items = array();
foreach ($arResult['ITEMS'] as $item) {
    $productId = (int)$item['ID'];
    $canBuy = (bool)$item['CAN_BUY'];

    $images = Content::getCatalogItemImages($item);

    $properties = array();
    foreach ($item['DISPLAY_PROPERTIES'] as $property) {
        if (is_array($property['VALUE'])) {
            continue;
        }

        $properties[] = $property;
    }

    $items[] = array(
        'id' => (int)$item['ID'],
        'productId' => $productId,
        'name' => $item['NAME'],
        'url' => $item['DETAIL_PAGE_URL'],
        'images' => $images,
        'previewPicture' => current($images),
        'properties' => $properties,
        'price' => $item['price'],
        'oldPrice' => $item['oldPrice'],
        'showOldPrice' => $item['showOldPrice'],
        'canBuy' => $canBuy,
    );
}

$arResult['ITEMS'] = $items;