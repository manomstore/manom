<?php

namespace Manom;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Context;
use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Manom\Store\StoreData;
use \Manom\Store\StoreList;

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
     * @var array
     */
    private $sortData = [];
    /**
     * @var array
     */
    private $pageCountData = [];

    /**
     * Content constructor.
     */
    public function __construct()
    {
        $this->initSortData();
        $this->initPageCountData();
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
            /** @var StoreData $storeData */
            $storeData = $item['ecommerceData']['storeData'];

            $prices = $storeData->getPrices();

            $item['price'] = $prices['price'];
            $item['oldPrice'] = $prices['oldPrice'];
            $item['showOldPrice'] = !empty((int)$prices['oldPrice'])
                && (int)$item['price'] !== (int)$prices['oldPrice'];

            if (
                !$storeData->canBuy() &&
                !$item['ecommerceData']["preOrder"]["active"]
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
        $schedule = StoreList::getInstance()->getShop()->getSchedule();

        if (empty($schedule)) {
            return false;
        }

        $week = new WeekTools();
        $scheduleData = $week->parseScheduleShop($schedule);

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

    /**
     *
     */
    private function initSortData(): void
    {
        $request = Context::getCurrent()->getRequest();
        $sortData =& $this->sortData;
        $sortData["code"] = $request->get("sort_by");
        $sortData["order"] = 'ASC';
        $sortData["field2"] = 'SORT';
        $sortData["order2"] = 'ASC';

        switch ($sortData["code"]) {
            case "price_asc":
                $sortData["field"] = 'SCALED_PRICE_' . Price::CURRENT_TYPE_ID;
                break;
            case "price_desc":
                $sortData["field"] = 'SCALED_PRICE_' . Price::CURRENT_TYPE_ID;
                $sortData["order"] = 'DESC';
                break;
            case "pop":
                $sortData["field"] = $sortData["field2"];
                $sortData["order"] = $sortData["order2"];
                $sortData["field2"] = 'show_counter';
                $sortData["order2"] = 'DESC';
                break;
            case "name":
                $sortData["field"] = 'NAME';
                break;
            default:
                if ($sortData["relevanceOrder"] !== null) {
                    $sortData["field"] = "ID";
                    $sortData["order"] = $sortData["relevanceOrder"];
                    $sortData["code"] = "relevance";
                } else {
                    $sortData["field"] = $sortData["field2"];
                    $sortData["order"] = $sortData["order2"];
                    $sortData["field2"] = 'show_counter';
                    $sortData["order2"] = 'DESC';
                    $sortData["code"] = "pop";
                }
                break;
        }

        $sortData["list"] = [
            [
                "code" => "relevance",
                "name" => "по релевантности",
            ],
            [
                "code" => "pop",
                "name" => "по популярности",
            ],
            [
                "code" => "price_desc",
                "name" => "сначала дорогие",
            ],
            [
                "code" => "price_asc",
                "name" => "сначала дешевые",
            ],
            [
                "code" => "name",
                "name" => "по названию",
            ],
        ];

        foreach ($sortData["list"] as &$item) {
            if ($item["code"] === "relevance" && $sortData["relevanceOrder"] === null) {
                $item = null;
                continue;
            }

            $item["selected"] = $item["code"] === $sortData["code"];
        }
        unset($item);

        $sortData["list"] = array_values(array_filter($sortData["list"]));

        unset($sortData);
    }

    /**
     * @param array $elements
     */
    public function setRelevanceOrder(array $elements): void
    {
        $this->sortData["relevanceOrder"] = $elements;
        $this->initSortData();
    }

    /**
     * @param string $fieldName
     * @return mixed
     */
    public function getSortValue(string $fieldName)
    {
        $value = "";
        switch ($fieldName) {
            case "field":
                $value = $this->sortData["field"];
                break;
            case "field2":
                $value = $this->sortData["field2"];
                break;
            case "order":
                $value = $this->sortData["order"];
                break;
            case "order2":
                $value = $this->sortData["order2"];
                break;
            case "code":
                $value = $this->sortData["code"];
                break;
            case "list":
                $value = $this->sortData["list"];
                break;
        }

        return $value;
    }

    /**
     * @return array
     */
    public function getSortList(): array
    {
        return $this->sortData["list"];
    }

    /**
     *
     */
    private function initPageCountData(): void
    {
        $request = Context::getCurrent()->getRequest();
        $this->pageCountData["list"] = [
            "12"   => [
                "NAME" => "12",
            ],
            "24"   => [
                "NAME" => "24",
            ],
            "9999" => [
                "NAME" => "все",
            ],
        ];

        $this->pageCountData["current"] = array_key_first($this->pageCountData["list"]);
        if (array_key_exists($request->get("countOnPage"), $this->pageCountData["list"])) {
            $this->pageCountData["current"] = $request->get("countOnPage");
        }
        $this->pageCountData["list"][$this->pageCountData["current"]]["SELECTED"] = true;
    }

    /**
     * @return int
     */
    public function getPageCount(): int
    {
        return (int)$this->pageCountData["current"];
    }

    /**
     * @return array
     */
    public function getPageCountList(): array
    {
        return (array)$this->pageCountData["list"];
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    public static function renderYMHandler(): void
    {
        global $APPLICATION;
        if ($APPLICATION->GetCurPage() !== "/bitrix/admin/yamarket_setup_edit.php") {
            return;
        }

        $request = Context::getCurrent()->getRequest();
        $feedData = json_decode(Option::get("yandex.market", "feed_data"), true);
        if (!is_array($feedData)) {
            $feedData = [];
        }

        $feedId = (int)$request->get("id");

        if ($request->isPost() && check_bitrix_sessid() && $feedId > 0) {
            $feedData[$feedId]["USE_MIRROR"] = (int)$request->get("USE_MIRROR") === 1;
            $feedData[$feedId]["USE_PRICE_GOODS"] = (int)$request->get("USE_PRICE_GOODS") === 1;
            Option::set("yandex.market", "feed_data", json_encode($feedData));
        }

        $feedData = $feedData[$feedId];

        \CJSCore::Init(["jquery"]);
        ?>
        <script>
            var rowTemplate = `<tr>
	<td class="adm-detail-content-cell-l" width="40%" align="right" valign="top">#TEXT#</td>
	<td class="adm-detail-content-cell-r" width="60%">
		<input class="adm-designed-checkbox" type="checkbox" value="1" #CHECKED# name="#FIELD_NAME#" id="#FIELD_NAME#">
		<label class="adm-designed-checkbox-label" for="#FIELD_NAME#"></label>
    </td>
</tr>`;
            var feedData = <?=\CUtil::PhpToJSObject($feedData)?>;
            if (typeof feedData !== "object") {
                feedData = {};
            }

            $(document).ready(function () {
                $(".adm-detail-content#tab0").find("tr:last")
                    .after(
                        rowTemplate.replaceAll("#TEXT#", "Использовать зеркало")
                            .replaceAll("#FIELD_NAME#", "USE_MIRROR")
                            .replaceAll("#CHECKED#", feedData.USE_MIRROR === true ? "checked" : "")
                    )
                    .after(
                        rowTemplate.replaceAll("#TEXT#", "Использовать цены Goods")
                            .replaceAll("#FIELD_NAME#", "USE_PRICE_GOODS")
                            .replaceAll("#CHECKED#", feedData.USE_PRICE_GOODS === true ? "checked" : "")
                    );
            });
        </script>
        <?
    }
}
