<?php

use Manom\Product;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['PRODUCTS_ID'] = array();
$arResult['PRODUCTS_COUNT'] = 0;
$arResult['TOTAL_PRICE'] = 0;
foreach ($arResult['ITEMS']['AnDelCanBuy'] as $item) {
    $arResult['PRODUCTS_ID'][] = (int)$item['PRODUCT_ID'];
    $arResult['PRODUCTS_COUNT'] += (int)$item['QUANTITY'];
    $arResult['TOTAL_PRICE'] += (int)$item['QUANTITY'] * (int)$item['PRICE'];

    $imageId = 0;
    if (!empty((int)$item['PREVIEW_PICTURE'])) {
        $imageId = (int)$item['PREVIEW_PICTURE'];
    } elseif (!empty((int)$item['DETAIL_PICTURE'])) {
        $imageId = (int)$item['DETAIL_PICTURE'];
    }

    $item['PIC'] = array('src' => '');
    if (!empty($imageId)) {
        $item['PIC'] = CFile::ResizeImageGet(
            $imageId,
            array('width' => 350, 'height' => 350),
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
    }
}

$product = new Product;
$ecommerceData = $product->getEcommerceData($arResult['PRODUCTS_ID'], 6);

foreach ($arResult['ITEMS']['AnDelCanBuy'] as $i => $item) {
    $item['price'] = $item['PRICE'];
    $item['oldPrice'] = 0;

    foreach ($ecommerceData[$item['PRODUCT_ID']]['prices'] as $priceVariant) {
        if ((int)$priceVariant['CATALOG_GROUP_ID'] !== (int)$item['PRICE_TYPE_ID']) {
            continue;
        }

        $item['price'] = $priceVariant['DISCOUNT_PRICE'];
        $item['oldPrice'] = $priceVariant['PRICE'];
    }

    $item['sum'] = (int)$item['QUANTITY'] * $item['price'];
    $item['oldSum'] = (int)$item['QUANTITY'] * $item['oldPrice'];

    $arResult['ITEMS']['AnDelCanBuy'][$i] = $item;
}
