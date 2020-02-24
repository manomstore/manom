<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$quantityAll = 0;
foreach ($arResult['CATEGORIES'] as $categoryNum => $category) {
    foreach ($category as $itemNum => $item) {
        $quantityAll += (int)$item['QUANTITY'];

        $imageId = 0;
        if (!empty((int)$item['PREVIEW_PICTURE'])) {
            $imageId = (int)$item['PREVIEW_PICTURE'];
        } elseif (!empty((int)$item['DETAIL_PICTURE'])) {
            $imageId = (int)$item['DETAIL_PICTURE'];
        } elseif (!empty($item['PROPERTY_MORE_PHOTO_VALUE'])) {
            $imageId = (int)current(explode(', ', $item['PROPERTY_MORE_PHOTO_VALUE']));
        }

        if (!empty($imageId)) {
            $item['PIC'] = CFile::ResizeImageGet(
                $imageId,
                array('width' => 350, 'height' => 350),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        }

        if ((int)$item['DISCOUNT_PRICE_PERCENT'] > 0) {
            $item['OLD_SUM_VALUE'] = $item['BASE_PRICE'] * $item['QUANTITY'];
            $item['EXIST_DISCOUNT'] = true;
        } else {
            $item['EXIST_DISCOUNT'] = false;
        }

        $arResult['CATEGORIES'][$categoryNum][$itemNum] = $item;
    }
}

$arResult['NUM_PRODUCTS'] = $quantityAll;
