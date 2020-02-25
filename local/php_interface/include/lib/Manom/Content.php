<?php

namespace Manom;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;

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
     */
    public static function setCatalogItemsPrice($arResult): array
    {
        $price = new Price;
        $userGroups = $price->getUserGroups();
        $price->setPricesIdByName($arResult['ORIGINAL_PARAMETERS']['PRICE_CODE']);
        $pricesId = $price->getPricesId();

        foreach ($arResult['ITEMS'] as $itemNum => $item) {
            foreach ($item['OFFERS'] as $iOfferNum => $offer) {
                $arResult['ITEMS'][$itemNum]['OFFERS'][$iOfferNum]['PRICE'] = $price->getItemPrices(
                    $offer['ID'],
                    $offer['IBLOCK_ID'],
                    $pricesId,
                    $userGroups
                );
            }

            $arResult['ITEMS'][$itemNum]['PRICE'] = $price->getItemPrices(
                $item['ID'],
                $arResult['IBLOCK_ID'],
                $pricesId,
                $userGroups
            );
        }

        return $arResult;
    }

    /**
     * @param array $item
     * @param int $width
     * @param int $height
     * @return array
     */
    public static function getCatalogItemImages($item, $width = 350, $height = 350): array
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

        foreach ($imagesId as $id) {
            $images[] = \CFile::ResizeImageGet(
                $id,
                array('width' => $width, 'height' => $height),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        }

        return $images;
    }
}