<?php

use Manom\Content;
use Manom\Product;
use Manom\Store\StoreData;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$productsIblockId = 6;
$offersIblockId = 7;

$productsId = array();
$offersId = array();

$arResult['PRODUCTS_ID'] = array();
$arResult['PRODUCTS_COUNT'] = 0;
$arResult['TOTAL_PRICE'] = 0;
foreach ($arResult['ITEMS']['AnDelCanBuy'] as $item) {
    $arResult['PRODUCTS_ID'][] = (int)$item['PRODUCT_ID'];
    $arResult['PRODUCTS_COUNT'] += (int)$item['QUANTITY'];
    $arResult['TOTAL_PRICE'] += (int)$item['QUANTITY'] * (int)$item['PRICE'];

    if (empty($item['IBLOCK_ID'])) {
        $productsId[] = (int)$item['PRODUCT_ID'];
    }

    if ((int)$item['IBLOCK_ID'] === $offersIblockId) {
        $offersId[] = (int)$item['PRODUCT_ID'];
    }

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

if (!empty($offersId)) {
    $properties = array('MORE_PHOTO');
    $filter = array('IBLOCK_ID' => $offersIblockId, 'ID' => $offersId);
    $select = array('IBLOCK_ID', 'ID');
    $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
    while ($rowResult = $result->getNextElement(true, false)) {
        $row = $rowResult->getFields();
        foreach ($properties as $code) {
            $row['PROPERTIES'][$code] = $rowResult->getProperty($code);
        }

        $productsId[] = (int)$row['PROPERTIES']['CML2_LINK']['VALUE'];
        $offersProductId[(int)$row['ID']] = (int)$row['PROPERTIES']['CML2_LINK']['VALUE'];

        if (!empty($row['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
            $offersImage[(int)$row['ID']] = (int)current($row['PROPERTIES']['MORE_PHOTO']['VALUE']);
        }
    }
}

if (!empty($productsId)) {
    $properties = array('MORE_PHOTO');
    $filter = array('IBLOCK_ID' => [$productsIblockId, \Helper::SERVICE_IB_ID], 'ID' => $productsId);
    $select = array('IBLOCK_ID', 'ID', "PREVIEW_PICTURE", "DETAIL_PICTURE");
    $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
    while ($rowResult = $result->getNextElement(true, false)) {
        $row = $rowResult->getFields();
        $row["PREVIEW_PICTURE"] = ["ID" => $row["PREVIEW_PICTURE"]];
        $row["DETAIL_PICTURE"] = ["ID" => $row["DETAIL_PICTURE"]];

        foreach ($properties as $code) {
            $row['PROPERTIES'][$code] = $rowResult->getProperty($code);
        }

        $productsImage[(int)$row['ID']] = Content::getCatalogItemImages($row);
    }
}

$product = new Product;
$ecommerceData = $product->getEcommerceData($arResult['PRODUCTS_ID'], $productsIblockId);

foreach ($arResult['ITEMS']['AnDelCanBuy'] as $i => $item) {
    /** @var StoreData $storeData */
    $storeData = $ecommerceData[$item['PRODUCT_ID']]['storeData'];

    $mainStore = $storeData->getMain();
    $rrcStore = $storeData->getRrc();
    $prices = $storeData->getPrices();
    $item['price'] = $item['PRICE'];
    $item['oldPrice'] = 0;

    if ((int)$mainStore['price']['ID'] === (int)$item['PRODUCT_PRICE_ID']) {
        $item['price'] = $mainStore['price']['PRICE'];
        $item['oldPrice'] = $prices["oldPrice"];
    } elseif ((int)$rrcStore['price']['ID'] === (int)$item['PRODUCT_PRICE_ID']) {
        $item['price'] = $rrcStore['price']['PRICE'];
    }

    $item['sum'] = (int)$item['QUANTITY'] * $item['price'];
    $item['oldSum'] = (int)$item['QUANTITY'] * $item['oldPrice'];

    $productId = $item['PRODUCT_ID'];
    $offerId = 0;
    if (!empty($item['IBLOCK_ID'])) {
        $productId = $offersProductId[$item['PRODUCT_ID']];
        $offerId = $item['PRODUCT_ID'];
    }

    if (!empty($productsImage[$productId])) {
        $item['PIC'] = $productsImage[$productId][0];
    }

    $item["isService"] = $ecommerceData[$item['PRODUCT_ID']]['isService'];

    $arResult['ITEMS']['AnDelCanBuy'][$i] = $item;
}
