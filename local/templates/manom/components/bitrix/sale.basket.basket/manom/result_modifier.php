<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Content\Accessory;
use Manom\Product;
use Manom\Content;
use Manom\Store\StoreData;
use Manom\Store\StoreItem;

$productsIblockId = 6;
$offersIblockId = 7;

$productsId = array();
$offersId = array();
$productsImage = array();
$offersImage = array();
$productsModel = array();
$offersModel = array();
$productsAccessoriesId = array();
$productsAdditionalServicesId = array();
$offersProductId = array();
$accessoriesAndAdditionalServicesId = array();
$accessoriesAndAdditionalServices = array();

$arResult['PRODUCTS_ID'] = array();
foreach ($arResult['GRID']['ROWS'] as $i => $item) {
    $arResult['PRODUCTS_ID'][] = (int)$item['PRODUCT_ID'];

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

    $arResult['GRID']['ROWS'][$i] = $item;
}

$product = new Product;
$basketEcommerceData = $product->getEcommerceData($arResult['PRODUCTS_ID'], 6);

if (!empty($offersId)) {
    $properties = array('CML2_LINK', 'MORE_PHOTO', 'this_prod_model');
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

        if (!empty($row['PROPERTIES']['this_prod_model']['VALUE'])) {
            $offersModel[(int)$row['ID']] = $row['PROPERTIES']['this_prod_model']['VALUE'];
        }
    }
}

if (!empty($productsId)) {
    $properties = array('MORE_PHOTO', 'ACESS', 'DOP_SERV', 'model');
    $filter = array('IBLOCK_ID' => $productsIblockId, 'ID' => $productsId);
    $select = array('IBLOCK_ID', 'ID', "IBLOCK_SECTION_ID");
    $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
    while ($rowResult = $result->getNextElement(true, false)) {
        $row = $rowResult->getFields();
        foreach ($properties as $code) {
            $row['PROPERTIES'][$code] = $rowResult->getProperty($code);
        }

        if (!empty($row['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
            $productsImage[(int)$row['ID']] = (int)current($row['PROPERTIES']['MORE_PHOTO']['VALUE']);
        }

        $accessories = new Accessory((int)$row["IBLOCK_SECTION_ID"], (array)$row["PROPERTIES"]["ACESS"]["VALUE"]);

        if ($accessories->existItems()) {
            $productsAccessoriesId[(int)$row['ID']] = $accessories->getItems();
        }

        if (!empty($row['PROPERTIES']['DOP_SERV']['VALUE'])) {
            $productsAdditionalServicesId[(int)$row['ID']] = $row['PROPERTIES']['DOP_SERV']['VALUE'];
        }

        if (!empty($row['PROPERTIES']['model']['VALUE'])) {
            $productsModel[(int)$row['ID']] = $row['PROPERTIES']['model']['VALUE'];
        }
    }
}

foreach ($productsAccessoriesId as $productAccessoriesId) {
    foreach ($productAccessoriesId as $id) {
        if (!in_array((int)$id, $accessoriesAndAdditionalServicesId, true)) {
            $accessoriesAndAdditionalServicesId[] = (int)$id;
        }
    }
}

foreach ($productsAdditionalServicesId as $productAdditionalServicesId) {
    foreach ($productAdditionalServicesId as $id) {
        if (!in_array((int)$id, $accessoriesAndAdditionalServicesId, true)) {
            $accessoriesAndAdditionalServicesId[] = (int)$id;
        }
    }
}

if (!empty($accessoriesAndAdditionalServicesId)) {
    $product = new Product;
    $ecommerceData = $product->getEcommerceData($accessoriesAndAdditionalServicesId, $productsIblockId);

    $properties = array('MORE_PHOTO');
    $filter = array(
        'IBLOCK_ID' => $productsIblockId,
        'ID' => $accessoriesAndAdditionalServicesId,
        '!ID' => $productsId,
        'ACTIVE' => 'Y',
        'CATALOG_AVAILABLE' => 'Y',
    );
    $sort = array(
        'id' => $accessoriesAndAdditionalServicesId,
    );
    $select = array(
        'IBLOCK_ID',
        'ID',
        'NAME',
        'DETAIL_PAGE_URL',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE',
    );
    $result = CIBlockElement::GetList($sort, $filter, false, false, $select);
    while ($rowResult = $result->getNextElement(true, false)) {
        $row = $rowResult->getFields();
        foreach ($properties as $code) {
            $row['PROPERTIES'][$code] = $rowResult->getProperty($code);
        }

        $imageId = 0;
        if (!empty((int)$row['PREVIEW_PICTURE'])) {
            $imageId = (int)$row['PREVIEW_PICTURE'];
        } elseif (!empty((int)$row['DETAIL_PICTURE'])) {
            $imageId = (int)$row['DETAIL_PICTURE'];
        } elseif (!empty($row['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
            $imageId = (int)current($row['PROPERTIES']['MORE_PHOTO']['VALUE']);
        }

        $image = array('src' => '');
        if (!empty($imageId)) {
            $image = CFile::ResizeImageGet(
                $imageId,
                array('width' => 350, 'height' => 350),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        }

        /** @var StoreData $storeData */
        $storeData = $ecommerceData[(int)$row['ID']]['storeData'];
        $prices = $storeData->getPrices();

        $accessoriesAndAdditionalServices[(int)$row['ID']] = array(
            'id' => (int)$row['ID'],
            'name' => $row['NAME'],
            'url' => $row['DETAIL_PAGE_URL'],
            'img' => $image['src'],
            'prices'   => $prices,
            'preOrder' => $ecommerceData[(int)$row['ID']]['preOrder'],
        );
    }
}

foreach ($arResult['GRID']['ROWS'] as $i => $item) {
    $item['MODEL'] = '';

    $productId = $item['PRODUCT_ID'];
    $offerId = 0;
    if (!empty($item['IBLOCK_ID'])) {
        $productId = $offersProductId[$item['PRODUCT_ID']];
        $offerId = $item['PRODUCT_ID'];
    }

    if (!empty($item['IBLOCK_ID'])) {
        if (!empty($productsImage[$productId])) {
            $imageId = $productsImage[$productId];
        } elseif (!empty($offersImage[$offerId])) {
            $imageId = $offersImage[$offerId];
        }

        if (!empty($productsModel[$productId])) {
            $item['MODEL'] = $productsModel[$productId];
        } elseif (!empty($offersModel[$offerId])) {
            $item['MODEL'] = $offersModel[$offerId];
        }
    } else {
        if (!empty($productsImage[$productId])) {
            $imageId = $productsImage[$productId];
        }

        if (!empty($productsModel[$productId])) {
            $item['MODEL'] = $productsModel[$productId];
        }
    }

    if (empty($item['PIC']['src']) && !empty($imageId)) {
        $item['PIC'] = CFile::ResizeImageGet(
            $imageId,
            array('width' => 350, 'height' => 350),
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
    }

    foreach ($productsAccessoriesId[$productId] as $id) {
        if (empty($accessoriesAndAdditionalServices[$id])) {
            continue;
        }

        $item['ACCESSORIES'][] = $accessoriesAndAdditionalServices[$id];
    }

    foreach ($productsAdditionalServicesId[$productId] as $id) {
        if (empty($accessoriesAndAdditionalServices[$id])) {
            continue;
        }

        $item['ADDITIONAL_SERVICES'][] = $accessoriesAndAdditionalServices[$id];
    }

    /** @var StoreData $storeData */
    $storeData = $basketEcommerceData[$productId]['storeData'];
    $mainStore = $storeData->getMain();
    $rrcStore = $storeData->getRrc();
    $prices = $storeData->getPrices();

    $item['price'] = $item['PRICE'];
    $item['oldPrice'] = 0;

    /** @var StoreItem|null $currentStore */
    $currentStore = null;
    if ((int)$mainStore['price']['ID'] === (int)$item['PRODUCT_PRICE_ID']) {
        $item['price'] = $mainStore['price']['PRICE'];
        $item['oldPrice'] = $prices["oldPrice"];
        $currentStore = $mainStore["store"];
    } elseif ((int)$rrcStore['price']['ID'] === (int)$item['PRODUCT_PRICE_ID']) {
        $item['price'] = $rrcStore['price']['PRICE'];
        $currentStore = $rrcStore["store"];
    }

    $item['sum'] = (int)$item['QUANTITY'] * $item['price'];
    $item['oldSum'] = (int)$item['QUANTITY'] * $item['oldPrice'];
    $item['isService'] = (bool)$basketEcommerceData[$productId]["isService"];
    $item["disableUpButton"] = !(($item['QUANTITY'] < (int)$item['AVAILABLE_QUANTITY']) || $storeData->isUnlimited());
    $item["disableDownButton"] = (int)$item['QUANTITY'] === 1;
    $item["outOfStock"] = in_array($item['PRODUCT_ID'], $arParams['productsOutOfStock']);
    $item["canBuy"] = $item['CAN_BUY'] === "Y";

    if ($item["outOfStock"] || !$item["canBuy"]) {
        $item["disableUpButton"] = true;
        $item["disableDownButton"] = true;
    }

    $item['assemblyTime'] = $currentStore instanceof StoreItem ?
        $currentStore->getAssemblyTime() : 0;

    $arResult['GRID']['ROWS'][$i] = $item;
}

$arResult["MAIN_CART"] = $arParams["MAIN_CART"] === "Y";