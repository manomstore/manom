<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\Price;

$arResult['TAGS_CHAIN'] = array();
if ($arResult['REQUEST']['~TAGS']) {
    $res = array_unique(explode(',', $arResult['REQUEST']['~TAGS']));
    $url = array();
    foreach ($res as $key => $tags) {
        $tags = trim($tags);
        if (!empty($tags)) {
            $url_without = $res;
            unset($url_without[$key]);
            $url[$tags] = $tags;
            $result = array(
                'TAG_NAME' => htmlspecialcharsex($tags),
                'TAG_PATH' => $APPLICATION->GetCurPageParam('tags='.urlencode(implode(',', $url)), array('tags')),
                'TAG_WITHOUT' => $APPLICATION->GetCurPageParam(
                    (count($url_without) > 0 ? 'tags='.urlencode(implode(',', $url_without)) : ''),
                    array('tags')
                ),
            );
            $arResult['TAGS_CHAIN'][] = $result;
        }
    }
}

$price = new Price;
$userGroups = $price->getUserGroups();
$price->setPricesIdByName('Розничная');
$pricesId = $price->getPricesId();
$iblockId = 6;

$itemsId = array();
foreach ($arResult['SEARCH'] as $i => $item) {
    if (!in_array((int)$item['ITEM_ID'], $itemsId, true)) {
        $itemsId[] = (int)$item['ITEM_ID'];
    }
}

$rating = array();
$items = array();
if (!empty($itemsId)) {
    $rating = getRatingAndCountReviewForList($itemsId);

    $properties = array('MORE_PHOTO', 'PRODUCT_OF_THE_DAY', 'SELL_PROD');
    $filter = array('IBLOCK_ID' => $iblockId, 'ID' => $itemsId);
    $select = array('IBLOCK_ID', 'ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'CATALOG_AVAILABLE');
    $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
    while ($rowResult = $result->getNextElement(true, false)) {
        $row = $rowResult->getFields();
        foreach ($properties as $code) {
            $row['PROPERTIES'][$code] = $rowResult->getProperty($code);
        }

        $items[(int)$row['ID']] = array(
            'id' => (int)$row['ID'],
            'canBuy' => $row['CATALOG_AVAILABLE'] === 'Y',
            'previewPicture' => (int)$row['PREVIEW_PICTURE'],
            'detailPicture' => (int)$row['DETAIL_PICTURE'],
            'morePhoto' => $row['PROPERTIES']['MORE_PHOTO']['VALUE'],
            'productOfTheDay' => $row['PROPERTIES']['PRODUCT_OF_THE_DAY']['VALUE'] === 'Да',
            'sale' => $row['PROPERTIES']['SELL_PROD']['VALUE'] === 'Да',
        );
    }
}

foreach ($arResult['SEARCH'] as $i => $item) {
    $images = array();
    $imagesId = array();
    foreach ($items[$item['ITEM_ID']]['morePhoto'] as $id) {
        $imagesId[] = (int)$id;
    }

    if (empty($imagesId)) {
        if (!empty($items[$item['ITEM_ID']]['previewPicture'])) {
            $imagesId[] = $items[$item['ITEM_ID']]['previewPicture'];
        } elseif (!empty($items[$item['ITEM_ID']]['detailPicture'])) {
            $imagesId[] = $items[$item['ITEM_ID']]['detailPicture'];
        }
    }

    foreach ($imagesId as $id) {
        $images[] = \CFile::ResizeImageGet(
            $id,
            array('width' => 350, 'height' => 350),
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
    }

    $item['PRICE'] = $price->getItemPrices($item['ITEM_ID'], $iblockId, $pricesId, $userGroups);

    $productId = (int)$item['ITEM_ID'];
    if (!empty((int)$item['PRICE']['OFFER_ID'])) {
        $productId = (int)$item['PRICE']['OFFER_ID'];
    }

    $item['PRODUCT_ID'] = $productId;
    $item['RATING'] = $rating[$item['ITEM_ID']];
    $item['CAN_BUY'] = $items[$item['ITEM_ID']]['canBuy'];
    $item['PRODUCT_OF_THE_DAY'] = $items[$item['ITEM_ID']]['productOfTheDay'];
    $item['SALE'] = $items[$item['ITEM_ID']]['sale'];
    $item['IMAGES'] = $images;
    $item['IN_FAVORITE_AND_COMPARE'] = checkProdInFavoriteAndCompareList((int)$item['ITEM_ID'], 'UF_FAVORITE_ID');

    $arResult['SEARCH'][$i] = $item;
}
