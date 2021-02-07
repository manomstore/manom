<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Content;
use Manom\Store\StoreData;

$arResult = Content::setCatalogItemsEcommerceData($arResult);

$items = [];
foreach ($arResult['ITEMS'] as $item) {
    $images = Content::getCatalogItemImages($item);
    $productId = (int)$item['ID'];

    /** @var StoreData $storeData */
    $storeData = $item["ecommerceData"]["storeData"];
    $mainStore = $storeData->getMain();

    $items[] = [
        'id'        => (int)$item['ID'],
        'productId' => $productId,
        'name'      => $item['NAME'],
        'url'       => $item['DETAIL_PAGE_URL'],
        'images'    => $images,
        'price'     => $mainStore["price"]["PRICE"],
    ];
}

$arResult['ITEMS'] = $items;
