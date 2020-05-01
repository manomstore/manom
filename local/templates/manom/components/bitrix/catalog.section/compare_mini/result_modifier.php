<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Content;

$arResult = Content::setCatalogItemsEcommerceData($arResult);

foreach ($arResult['ITEMS'] as $i => $item) {
    $images = Content::getCatalogItemImages($item);

    $item['image'] = array('src' => '');
    if (!empty($images)) {
        $item['image'] = current($images);
    }

    $arResult['ITEMS'][$i] = $item;
}
