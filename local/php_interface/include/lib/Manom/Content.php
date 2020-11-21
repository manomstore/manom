<?php

namespace Manom;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ArgumentException;
use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use \Manom\Nextjs\Api\Store;

/**
 * Class Content
 * @package Manom
 */
class Content
{
    /**
     * @var bool|array
     */
    private $propertiesToShow = false;

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

            $item['ecommerceData'] = $ecommerceData[$item['ID']];

            $prices = self::getPricesFromStoreData($item['ecommerceData']['storeData']);

            $item['price'] = $prices['price'];
            $item['oldPrice'] = $prices['oldPrice'];
            $item['showOldPrice'] = !empty((int)$prices['oldPrice'])
                && (int)$item['price'] !== (int)$prices['oldPrice'];

            if (
                empty($item['ecommerceData']['amounts']['main']) &&
                empty($item['ecommerceData']['amounts']['second'])
            ) {
                $item['CAN_BUY'] = false;
            }

            $arResult['ITEMS'][$itemNum] = $item;
        }

        return $arResult;
    }

    /**
     * @param array $storeData
     * @return array
     */
    public static function getPricesFromStoreData($storeData): array
    {
        $return = array(
            'price' => 0,
            'oldPrice' => 0,
        );

        $mainStoreData = $storeData['main'];
        $secondStoreData = $storeData['second'];

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
            $return['price'] = $mainPrice;
            $return['oldPrice'] = $secondPrice;
        } elseif (!empty($mainStoreData['amount'])) {
            $return['price'] = $mainPrice;
        } elseif (!empty($secondStoreData['amount'])) {
            $return['price'] = $secondPrice;
        } elseif ($mainPrice > $secondPrice) {
            $return['price'] = $mainPrice;
        } else {
            $return['price'] = $secondPrice;
        }

        if ($return['price'] === $return['oldPrice']) {
            $return['oldPrice'] = 0;
        }

        return $return;
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

    /**
     * @return array
     */
    public static function getPropertyCodes(): array
    {
        $items = array();

        $result = PropertyTable::getList(array(
            "order" => array('SORT' => 'ASC', 'ID' => 'ASC'),
            "filter" => array('IBLOCK_ID' => \Helper::CATALOG_IB_ID),
            "select" => array('CODE'),

        ));
        while ($row = $result->fetch()) {
            $items[] = $row['CODE'];
        }

        return $items;
    }

    public static function showCallbackForm()
    {
        if (!Loader::includeModule("manom.nextjs")) {
            return false;
        }

        $mainStore = (new Store())->getMain();

        if (empty($mainStore) || empty($mainStore["schedule"])) {
            return false;
        }

        $week = new WeekTools();
        $scheduleData = $week->parseScheduleShop($mainStore["schedule"]);

        return !$scheduleData["isOpen"];
    }

    /**
     * @return bool
     * @throws LoaderException
     */
    public function setPropertiesToShow(): bool
    {
        if (!Loader::includeModule('redsign.grupper')) {
            return false;
        }

        $this->propertiesToShow = [];

        $rsGroups = \CRSGGroups::GetList(["SORT" => "ASC", "ID" => "ASC"], []);
        while ($arGroup = $rsGroups->Fetch()) {
            $rsBinds = \CRSGBinds::GetList(["SORT" => "ASC"], ["GROUP_ID" => $arGroup["ID"]]);
            while ($arBind = $rsBinds->Fetch()) {
                $this->propertiesToShow[] = $arBind["IBLOCK_PROPERTY_ID"];
            }
        }

        return true;
    }

    /**
     * @param $properties
     * @return array
     */
    public function getDisplayedProperties($properties): array
    {
        if ($this->propertiesToShow === false) {
            try {
                $this->setPropertiesToShow();
            } catch (\Exception $e) {
            }
        }

        if (!is_array($this->propertiesToShow)) {
            return $properties;
        }

        $idToCode = array_map(function ($properties) {
            return $properties["ID"];
        }, $properties);
        $idToCode = array_flip($idToCode);

        $resultProperties = [];

        $intersectSlice = array_intersect($this->propertiesToShow, array_keys($idToCode));
        foreach ($intersectSlice as $propertyId) {
            if (!array_key_exists($propertyId, $idToCode)) {
                continue;
            }
            $property = $properties[$idToCode[$propertyId]];
            if (is_array($property['VALUE'])) {
                continue;
            }
            $resultProperties[] = $property;
        }
        return $resultProperties;
    }
}
