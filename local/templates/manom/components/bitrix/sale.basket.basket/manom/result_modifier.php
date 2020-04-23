<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Product;
use Manom\Content;

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

foreach ($arResult['GRID']['ROWS'] as $i => $item) {
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

    $item['SUM'] = \CCurrencyLang::CurrencyFormat(
        $item['SUM_VALUE'],
        $item['CURRENCY'],
        false
    );

    $item['PRICE_FORMATED'] = \CCurrencyLang::CurrencyFormat(
        $item['PRICE'],
        $item['CURRENCY'],
        false
    );

    if ($item['DISCOUNT_PRICE_PERCENT'] > 0) {
        $item['SUM_FULL_PRICE_FORMATED'] = \CCurrencyLang::CurrencyFormat(
            $item['SUM_FULL_PRICE'],
            $item['CURRENCY'],
            false
        );
    }

    $arResult['GRID']['ROWS'][$i] = $item;
}

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
    $select = array('IBLOCK_ID', 'ID');
    $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
    while ($rowResult = $result->getNextElement(true, false)) {
        $row = $rowResult->getFields();
        foreach ($properties as $code) {
            $row['PROPERTIES'][$code] = $rowResult->getProperty($code);
        }

        if (!empty($row['PROPERTIES']['MORE_PHOTO']['VALUE'])) {
            $productsImage[(int)$row['ID']] = (int)current($row['PROPERTIES']['MORE_PHOTO']['VALUE']);
        }

        if (!empty($row['PROPERTIES']['ACESS']['VALUE'])) {
            $productsAccessoriesId[(int)$row['ID']] = $row['PROPERTIES']['ACESS']['VALUE'];
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
    $select = array(
        'IBLOCK_ID',
        'ID',
        'NAME',
        'DETAIL_PAGE_URL',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE',
    );
    $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
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

        $prices = Content::getPricesFromStoreData($ecommerceData[(int)$row['ID']]['storeData']);

        $accessoriesAndAdditionalServices[(int)$row['ID']] = array(
            'id' => (int)$row['ID'],
            'name' => $row['NAME'],
            'url' => $row['DETAIL_PAGE_URL'],
            'img' => $image['src'],
            'prices' => $prices,

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

    $arResult['GRID']['ROWS'][$i] = $item;
}

$arResult["MAIN_CART"] = $arParams["MAIN_CART"] === "Y";