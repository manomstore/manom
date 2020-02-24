<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Content;

$arResult = Content::setCatalogItemsPrice($arResult);

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

    $images = array();
    foreach ($item['PROPERTIES']['MORE_PHOTO']['VALUE'] as $id) {
        $images[] = CFile::ResizeImageGet(
            $id,
            array('width' => 350, 'height' => 350),
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
    }

    if (empty($images) && !empty($item['OFFERS'])) {
        foreach ($item['OFFERS'] as $offer) {
            foreach ($offer['PROPERTIES']['MORE_PHOTO']['VALUE'] as $id) {
                $images[] = CFile::ResizeImageGet(
                    $id,
                    array('width' => 350, 'height' => 350),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
            }
        }
    }

    if (empty($images)) {
        if (!empty($item['PREVIEW_PICTURE']['ID'])) {
            $images[] = CFile::ResizeImageGet(
                $item['PREVIEW_PICTURE']['ID'],
                array('width' => 350, 'height' => 350),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        } elseif (!empty($item['DETAIL_PICTURE']['ID'])) {
            $images[] = CFile::ResizeImageGet(
                $item['DETAIL_PICTURE']['ID'],
                array('width' => 350, 'height' => 350),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        }
    }

    if (empty($images) && !empty($item['OFFERS'])) {
        foreach ($item['OFFERS'] as $offer) {
            if (!empty($images)) {
                break;
            }

            if (!empty($offer['PREVIEW_PICTURE']['ID'])) {
                $images[] = CFile::ResizeImageGet(
                    $offer['PREVIEW_PICTURE']['ID'],
                    array('width' => 350, 'height' => 350),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
            } elseif (!empty($offer['DETAIL_PICTURE']['ID'])) {
                $images[] = CFile::ResizeImageGet(
                    $offer['DETAIL_PICTURE']['ID'],
                    array('width' => 350, 'height' => 350),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
            }
        }
    }

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
        'price' => $item['PRICE'],
        'canBuy' => $canBuy,
        'productOfTheDay' => $item['PROPERTIES']['PRODUCT_OF_THE_DAY']['VALUE'] === 'Да',
        'sale' => $item['PROPERTIES']['SELL_PROD']['VALUE'] === 'Да',
        'inFavoriteAndCompare' => checkProdInFavoriteAndCompareList((int)$item['ID'], 'UF_FAVORITE_ID'),
        'rating' => $rating[(int)$item['ID']],
    );
}

$arResult['ITEMS'] = $items;
