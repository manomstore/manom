<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Content;

$arResult = Content::setCatalogItemsEcommerceData($arResult);

$items = [];
foreach ($arResult['ITEMS'] as $item) {
    $images = Content::getCatalogItemImages($item);
    $productId = (int)$item['ID'];

    $items[] = [
        'id'        => (int)$item['ID'],
        'productId' => $productId,
        'name'      => $item['NAME'],
        'url'       => $item['DETAIL_PAGE_URL'],
        'images'    => $images,
        'price'     => $item["ecommerceData"]["storeData"]["main"]["price"]["PRICE"],
    ];
}

$arResult['ITEMS'] = $items;
