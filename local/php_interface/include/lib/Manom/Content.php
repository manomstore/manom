<?php

namespace Manom;

use Bitrix\Main\ArgumentException;
use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

/**
 * Class Content
 * @package Manom
 */
class Content
{
    /**
     * Content constructor.
     */
    public function __construct()
    {
    }

    /**
     * Метод возвращает окончание для множественного числа слова на основании числа и массива окончаний
     * @param int $iNumber - Число на основе которого нужно сформировать окончание
     * @param array $aEnding - Массив слов или окончаний для чисел (1, 4, 5), например array("яблоко", "яблока", "яблок")
     * @return string
     */
    public static function getNumEnding(int $iNumber, array $aEnding): string
    {
        $iNumber = $iNumber % 100;
        if ($iNumber >= 11 && $iNumber <= 19) {
            $sEnding = $aEnding[2];
        } else {
            $i = $iNumber % 10;
            switch ($i) {
                case (1):
                    $sEnding = $aEnding[0];
                    break;
                case (2):
                case (3):
                case (4):
                    $sEnding = $aEnding[1];
                    break;
                default:
                    $sEnding = $aEnding[2];
            }
        }

        return $sEnding;
    }

    /**
     * @param int $sectionId
     * @return array
     * @throws Exception
     * @throws LoaderException
     */
    public static function getSectionBanner($sectionId = 0): array
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception('Не подключен модуль iblock');
        }

        $sectionBanner = array();
        $sectionBannerWithoutCategory = array();

        $order = array('SORT' => 'ASC');
        $filter = array(
            'IBLOCK_ID' => 10,
            'ACTIVE' => 'Y',
        );

        if ((int)$sectionId > 0) {
            $filter[] = array(
                'LOGIC' => 'OR',
                array('PROPERTY_CB_CATEGORY' => false),
                array('PROPERTY_CB_CATEGORY' => $sectionId),
            );
        } else {
            $filter['PROPERTY_CB_CATEGORY'] = false;
        }

        $select = array(
            'IBLOCK_ID',
            'ID',
            'PREVIEW_PICTURE',
            'PROPERTY_CB_BTN_TEXT',
            'PROPERTY_CB_BTN_LINK',
            'PROPERTY_CB_TEXT',
            'PROPERTY_CB_CATEGORY',
        );
        $result = \CIBlockElement::GetList($order, $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            if (empty($sectionBannerWithoutCategory) && empty($row['PROPERTY_CB_CATEGORY_VALUE'])) {
                $img = \CFile::ResizeImageGet(
                    $row['PREVIEW_PICTURE'],
                    array('width' => 1170, 'height' => 390),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
                $sectionBannerWithoutCategory = array(
                    'btn_link' => $row['PROPERTY_CB_BTN_LINK_VALUE'],
                    'btn_text' => $row['PROPERTY_CB_BTN_TEXT_VALUE'],
                    'text' => $row['PROPERTY_CB_TEXT_VALUE'],
                    'img' => $img['src'],
                );
            } elseif (empty($sectionBanner) && (int)$sectionId > 0 && (int)$row['PROPERTY_CB_CATEGORY_VALUE'] === (int)$sectionId) {
                $img = \CFile::ResizeImageGet(
                    $row['PREVIEW_PICTURE'],
                    array('width' => 1170, 'height' => 390),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
                $sectionBanner = array(
                    'btn_link' => $row['PROPERTY_CB_BTN_LINK_VALUE'],
                    'btn_text' => $row['PROPERTY_CB_BTN_TEXT_VALUE'],
                    'text' => $row['PROPERTY_CB_TEXT_VALUE'],
                    'img' => $img['src'],
                );
            }
        }

        return empty($sectionBanner) ? $sectionBannerWithoutCategory : $sectionBanner;
    }

    /**
     * @param string $id
     * @param string $callback
     * @param array $callbackParams
     * @param int $time
     * @return array
     */
    public static function returnResultCache($id, $callback, $callbackParams = array(), $time = 86400): array
    {
        $result = array();

        $cache = new \CPHPCache();
        if ($cache->InitCache($time, $id, '/'.SITE_ID.'/'.$id)) {
            $result = $cache->GetVars();
        } elseif ($cache->StartDataCache()) {
            $result = $callback($callbackParams);
            $cache->EndDataCache($result);
        }

        return $result;
    }

    /**
     * @param $arResult
     * @return array
     * @throws Exception
     * @throws LoaderException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function setCatalogItemsPrice($arResult): array
    {
//        $price = new Price;
//        $userGroups = $price->getUserGroups();
//        $price->setPricesIdByName($arResult['ORIGINAL_PARAMETERS']['PRICE_CODE']);
//        $pricesId = $price->getPricesId();
//
//        foreach ($arResult['ITEMS'] as $itemNum => $item) {
//            foreach ($item['OFFERS'] as $iOfferNum => $offer) {
//                $arResult['ITEMS'][$itemNum]['OFFERS'][$iOfferNum]['PRICE'] = $price->getItemPrices(
//                    $offer['ID'],
//                    $offer['IBLOCK_ID'],
//                    $pricesId,
//                    $userGroups
//                );
//            }
//
//            $arResult['ITEMS'][$itemNum]['PRICE'] = $price->getItemPrices(
//                $item['ID'],
//                $arResult['IBLOCK_ID'],
//                $pricesId,
//                $userGroups
//            );
//        }

        return $arResult;
    }

    /**
     * @param array $arResult
     * @return array
     * @throws ArgumentException
     * @throws Exception
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function setCatalogItemsEcommerceData($arResult): array
    {
        $productsId = array();
        foreach ($arResult['ITEMS'] as $itemNum => $item) {
            if (in_array((int)$item['ID'], $productsId, true)) {
                continue;
            }
            $productsId[] = (int)$item['ID'];
        }

        if (!empty($productsId)) {
            $product = new Product;
            $ecommerceData = $product->getEcommerceData($productsId, $arResult['IBLOCK_ID']);
        }

        foreach ($arResult['ITEMS'] as $itemNum => $item) {
            if (empty($ecommerceData[$item['ID']])) {
                continue;
            }

            $mainStoreData = $ecommerceData[$item['ID']]['storeData']['main'];
            $secondStoreData = $ecommerceData[$item['ID']]['storeData']['second'];

            $item['price'] = 0;
            $item['oldPrice'] = 0;

            if (
                !empty($mainStoreData['price']['DISCOUNT_PRICE']) &&
                $mainStoreData['price']['DISCOUNT_PRICE'] !== $mainStoreData['price']['PRICE']
            ) {
                $mainPrice = $mainStoreData['price']['DISCOUNT_PRICE'];
            } else {
                $mainPrice = $mainStoreData['price']['PRICE'];
            }

            if (
                !empty($secondStoreData['price']['DISCOUNT_PRICE']) &&
                $secondStoreData['price']['DISCOUNT_PRICE'] !== $secondStoreData['price']['PRICE']
            ) {
                $secondPrice = $secondStoreData['price']['DISCOUNT_PRICE'];
            } else {
                $secondPrice = $secondStoreData['price']['PRICE'];
            }

            if (!empty($mainStoreData['amount']) && !empty($secondStoreData['amount'])) {
                $item['price'] = $mainPrice;
                $item['oldPrice'] = $secondPrice;
            } elseif (!empty($mainStoreData['amount'])) {
                $item['price'] = $mainPrice;
            } elseif (!empty($secondStoreData['amount'])) {
                $item['price'] = $secondPrice;
            }

            if ($item['price'] === $item['oldPrice']) {
                $item['oldPrice'] = 0;
            }

            if (
                empty($ecommerceData[$item['ID']]['amounts']['main']) &&
                empty($ecommerceData[$item['ID']]['amounts']['second'])
            ) {
                $item['CAN_BUY'] = false;
            }

            $arResult['ITEMS'][$itemNum] = $item;
        }

        return $arResult;
    }

    /**
     * @param array $item
     * @param bool $resize
     * @param int $width
     * @param int $height
     * @return array
     */
    public static function getCatalogItemImages($item, $resize = true, $width = 350, $height = 350): array
    {
        $images = array();
        $imagesId = array();

        foreach ($item['PROPERTIES']['MORE_PHOTO']['VALUE'] as $id) {
            $imagesId[] = (int)$id;
        }

        if (empty($imagesId) && !empty($item['OFFERS'])) {
            foreach ($item['OFFERS'] as $offer) {
                foreach ($offer['PROPERTIES']['MORE_PHOTO']['VALUE'] as $id) {
                    $imagesId[] = (int)$id;
                }
            }
        }

        if (empty($imagesId)) {
            if (!empty((int)$item['PREVIEW_PICTURE']['ID'])) {
                $imagesId[] = (int)$item['PREVIEW_PICTURE']['ID'];
            } elseif (!empty($item['DETAIL_PICTURE']['ID'])) {
                $imagesId[] = (int)$item['DETAIL_PICTURE']['ID'];
            }
        }

        if (empty($imagesId) && !empty($item['OFFERS'])) {
            foreach ($item['OFFERS'] as $offer) {
                if (!empty($imagesId)) {
                    break;
                }

                if (!empty((int)$offer['PREVIEW_PICTURE']['ID'])) {
                    $imagesId[] = (int)$offer['PREVIEW_PICTURE']['ID'];
                } elseif (!empty((int)$offer['DETAIL_PICTURE']['ID'])) {
                    $imagesId[] = (int)$offer['DETAIL_PICTURE']['ID'];
                }
            }
        }

        if ($resize) {
            foreach ($imagesId as $id) {
                $images[] = \CFile::ResizeImageGet(
                    $id,
                    array('width' => $width, 'height' => $height),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
            }
        } else {
            $images = $imagesId;
        }

        return $images;
    }
}
