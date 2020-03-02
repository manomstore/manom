<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

foreach ($arResult['CATEGORIES'] as $categoryNum => $category) {
    foreach ($category as $itemNum => $item) {
        $imageId = 0;
        if (!empty((int)$item['PREVIEW_PICTURE'])) {
            $imageId = (int)$item['PREVIEW_PICTURE'];
        } elseif (!empty((int)$item['DETAIL_PICTURE'])) {
            $imageId = (int)$item['DETAIL_PICTURE'];
        } elseif (!empty($item['PROPERTY_MORE_PHOTO_VALUE'])) {
            $imageId = (int)current(explode(', ', $item['PROPERTY_MORE_PHOTO_VALUE']));
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

        $item['SUM'] = CCurrencyLang::CurrencyFormat(
            $item['SUM_VALUE'],
            $item['CURRENCY'],
            false
        );

        if ($item['DISCOUNT_PRICE_PERCENT'] > 0) {
            $item['SUM_FULL_PRICE_FORMATTED'] = CCurrencyLang::CurrencyFormat(
                $item['BASE_PRICE'] * $item['QUANTITY'],
                $item['CURRENCY'],
                false
            );
        }

        $arResult['CATEGORIES'][$categoryNum][$itemNum] = $item;
    }
}
