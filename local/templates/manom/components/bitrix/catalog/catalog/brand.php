<?php

use Manom\Content;
use Manom\References\Brand;
use Manom\Price;


if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

global $catalogFilter;
global $sectionListFilter;

$sortCode = $_REQUEST['sort_by'];
$order = 'ASC';
if ($_REQUEST['sort_by'] === 'price_asc') {
    $sort = 'SCALED_PRICE_' . Price::CURRENT_TYPE_ID;
} elseif ($_REQUEST['sort_by'] === 'price_desc') {
    $sort = 'SCALED_PRICE_' . Price::CURRENT_TYPE_ID;
    $order = 'DESC';
} elseif ($_REQUEST['sort_by'] === 'pop') {
    $sort = 'propertysort_SALELEADER';
} elseif ($_REQUEST['sort_by'] === 'name') {
    $sort = 'NAME';
} else {
    $sort = 'propertysort_SALELEADER';
    $sortCode = "pop";
}


$pageCountList = [

    "12" => [
        "NAME" => "12",
    ],
    "24" => [
        "NAME" => "24",
    ],
    "9999" => [
        "NAME" => "все",
    ],
];

$pageCount = 12;
if (array_key_exists($_REQUEST["countOnPage"], $pageCountList)) {
    $pageCount = $_REQUEST["countOnPage"];
}
$pageCountList[$pageCount]["SELECTED"] = true;

$section = Content::returnResultCache(
    'section'.$arParams['IBLOCK_ID'].$arResult['VARIABLES']['SECTION_ID'].$arResult['VARIABLES']['SECTION_CODE'],
    'getSection',
    array(
        'iblockId' => $arParams['IBLOCK_ID'],
        'sectionId' => $arResult['VARIABLES']['SECTION_ID'],
        'sectionCode' => $arResult['VARIABLES']['SECTION_CODE'],
    )
);

/**
 * @param array $params
 * @return array
 */
function getSection($params): array
{
    $section = array();

    if (empty((int)$params['iblockId'])) {
        return $section;
    }

    if (empty((int)$params['sectionId']) && empty($params['sectionCode'])) {
        return $section;
    }

    $filter = array('ACTIVE' => 'Y', 'IBLOCK_ID' => (int)$params['iblockId']);
    if (!empty($params['sectionId'])) {
        $filter['ID'] = $params['sectionId'];
    } elseif (!empty((int)$params['sectionCode'])) {
        $filter['CODE'] = (int)$params['sectionCode'];
    }
    $select = array('IBLOCK_ID', 'ID', 'NAME');
    $result = CIBlockSection::GetList(array(), $filter, false, $select);
    if ($row = $result->fetch()) {
        $section = array(
            'id' => (int)$row['ID'],
            'name' => $row['NAME'],
        );
    }

    return $section;
}

try {
    $brand = new Brand();
    $brand->setBrandFilter($arParams['FILTER_NAME'], $arResult["VARIABLES"]["BRAND_CODE"]);
    $brand->setBrandSectionFilter("sectionListFilter", $arResult["VARIABLES"]["BRAND_CODE"]);
    $brandData = $brand->getByCode($arResult["VARIABLES"]["BRAND_CODE"]);
} catch (\Exception $e) {
    $brandData = [];
}
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:breadcrumb',
    'articles',
    array(
        'START_FROM' => 0,
        'PATH' => '',
        'SITE_ID' => 's1',
    ),
    false
); ?>

<main class="catalog container">
    <div class="catalog-main">
        <div class="preloaderCatalog">
            <div class="windows8">
                <div class="wBall" id="wBall_1">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_2">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_3">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_4">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_5">
                    <div class="wInnerBall"></div>
                </div>
            </div>
        </div>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:catalog.section.list',
                '',
                array(
                    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
                    'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
                    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                    'CACHE_TIME' => $arParams['CACHE_TIME'],
                    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                    'COUNT_ELEMENTS' => $arParams['SECTION_COUNT_ELEMENTS'],
                    'TOP_DEPTH' => $arParams['SECTION_TOP_DEPTH'],
                    'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
                    'VIEW_MODE' => $arParams['SECTIONS_VIEW_MODE'],
                    'SHOW_PARENT_NAME' => $arParams['SECTIONS_SHOW_PARENT_NAME'],
                    'HIDE_SECTION_NAME' => $arParams['SECTIONS_HIDE_SECTION_NAME'] ?? 'N',
                    'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'] ?? '',
                    'DISCOUNTED_SECTION_ID' => $arParams['DISCOUNTED_SECTION_ID'],
                    'TITLE' => $APPLICATION->GetTitle(),
                    'BRAND_DATA' => $brandData,
                    'FILTER_NAME' => "sectionListFilter",
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            ); ?>

        <?php

        if ($_REQUEST['ajaxCal'] === 'Y') {
            $GLOBALS['APPLICATION']->RestartBuffer();
        }
        ?>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:catalog.smart.filter',
            '',
            array(
                'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'SECTION_ID' => $section['id'],
                'FILTER_NAME' => $arParams['FILTER_NAME'],
                'PREFILTER_NAME' => $arParams['FILTER_NAME'],
                'PRICE_CODE' => $arParams['FILTER_PRICE_CODE'],
                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                'CACHE_TIME' => $arParams['CACHE_TIME'],
                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                'SAVE_IN_SESSION' => 'N',
                'FILTER_VIEW_MODE' => $arParams['FILTER_VIEW_MODE'],
                'XML_EXPORT' => 'N',
                'SECTION_TITLE' => 'NAME',
                'SECTION_DESCRIPTION' => 'DESCRIPTION',
                'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
                'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                'SEF_MODE' => 'N', //$arParams['SEF_MODE'],
                'SEF_RULE' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['smart_filter'],
                'SMART_FILTER_PATH' => $arResult['VARIABLES']['SMART_FILTER_PATH'],
                'PAGER_PARAMS_NAME' => $arParams['PAGER_PARAMS_NAME'],
                'INSTANT_RELOAD' => $arParams['INSTANT_RELOAD'],
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        ); ?>
            <?php
            global $hideSmartFilter;
            $APPLICATION->IncludeComponent(
            'bitrix:catalog.section',
            '',
            array(
                'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'ELEMENT_SORT_FIELD' => $sort,//$arParams['ELEMENT_SORT_FIELD'],
                'ELEMENT_SORT_ORDER' => $order,//$arParams['ELEMENT_SORT_ORDER'],
                'ELEMENT_SORT_FIELD2' => "SORT",
                'ELEMENT_SORT_ORDER2' => "ASC",
                'SORT_CODE' => $sortCode,
                'PROPERTY_CODE' => $arParams['LIST_PROPERTY_CODE'] ?? [],
                'PROPERTY_CODE_MOBILE' => $arParams['LIST_PROPERTY_CODE_MOBILE'],
                'META_KEYWORDS' => $arParams['LIST_META_KEYWORDS'],
                'META_DESCRIPTION' => $arParams['LIST_META_DESCRIPTION'],
                'BROWSER_TITLE' => $arParams['LIST_BROWSER_TITLE'],
                'SET_LAST_MODIFIED' => $arParams['SET_LAST_MODIFIED'],
                'INCLUDE_SUBSECTIONS' => $arParams['INCLUDE_SUBSECTIONS'],
                'BASKET_URL' => $arParams['BASKET_URL'],
                'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
                'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
                'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
                'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
                'FILTER_NAME' => $arParams['FILTER_NAME'],
                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                'CACHE_TIME' => $arParams['CACHE_TIME'],
                'CACHE_FILTER' => $arParams['CACHE_FILTER'],
                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                'SET_TITLE' => $arParams['SET_TITLE'],
                'MESSAGE_404' => $arParams['~MESSAGE_404'],
                'SET_STATUS_404' => $arParams['SET_STATUS_404'],
                'SHOW_404' => $arParams['SHOW_404'],
                'FILE_404' => $arParams['FILE_404'],
                'DISPLAY_COMPARE' => $arParams['USE_COMPARE'],
                'PAGE_ELEMENT_COUNT' => $pageCount,
                'PAGE_COUNT_LIST' => $pageCountList,
                'LINE_ELEMENT_COUNT' => $arParams['LINE_ELEMENT_COUNT'],
                'PRICE_CODE' => $arParams['~PRICE_CODE'],
                'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
                'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
                'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
                'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
                'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'] ?? '',
                'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'] ?? '',
                'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES'] ?? [],
                'DISPLAY_TOP_PAGER' => $arParams['DISPLAY_TOP_PAGER'],
                'DISPLAY_BOTTOM_PAGER' => $arParams['DISPLAY_BOTTOM_PAGER'],
                'PAGER_TITLE' => $arParams['PAGER_TITLE'],
                'PAGER_SHOW_ALWAYS' => $arParams['PAGER_SHOW_ALWAYS'],
                'PAGER_TEMPLATE' => $arParams['PAGER_TEMPLATE'],
                'PAGER_DESC_NUMBERING' => $arParams['PAGER_DESC_NUMBERING'],
                'PAGER_DESC_NUMBERING_CACHE_TIME' => $arParams['PAGER_DESC_NUMBERING_CACHE_TIME'],
                'PAGER_SHOW_ALL' => $arParams['PAGER_SHOW_ALL'],
                'PAGER_PARAMS_NAME' => $arParams['PAGER_PARAMS_NAME'],
                'LAZY_LOAD' => $arParams['LAZY_LOAD'],
                'MESS_BTN_LAZY_LOAD' => $arParams['~MESS_BTN_LAZY_LOAD'],
                'LOAD_ON_SCROLL' => $arParams['LOAD_ON_SCROLL'],
                'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'] ?? [],
                'OFFERS_FIELD_CODE' => $arParams['LIST_OFFERS_FIELD_CODE'],
                'OFFERS_PROPERTY_CODE' => $arParams['LIST_OFFERS_PROPERTY_CODE'] ?? [],
                'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
                'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
                'OFFERS_SORT_FIELD2' => $arParams['OFFERS_SORT_FIELD2'],
                'OFFERS_SORT_ORDER2' => $arParams['OFFERS_SORT_ORDER2'],
                'OFFERS_LIMIT' => $arParams['LIST_OFFERS_LIMIT'] ?? 0,
                'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
                'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
                'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
                'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
                'USE_MAIN_ELEMENT_SECTION' => $arParams['USE_MAIN_ELEMENT_SECTION'],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
                'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
                'LABEL_PROP' => $arParams['LABEL_PROP'],
                'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
                'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
                'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
                'ENLARGE_PROP' => $arParams['LIST_ENLARGE_PROP'] ?? '',
                'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
                'SLIDER_INTERVAL' => $arParams['LIST_SLIDER_INTERVAL'] ?? '',
                'SLIDER_PROGRESS' => $arParams['LIST_SLIDER_PROGRESS'] ?? '',
                'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'] ?? [],
                'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
                'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'] ?? '',
                'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'] ?? '',
                'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?? '',
                'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?? '',
                'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'] ?? '',
                'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'] ?? '',
                'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'] ?? '',
                'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'] ?? '',
                'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'] ?? '',
                'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'] ?? '',
                'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'] ?? '',
                'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'] ?? '',
                'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'] ?? '',
                'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'] ?? '',
                'ADD_SECTIONS_CHAIN' => 'N',
                'ADD_TO_BASKET_ACTION' => '',
                'SHOW_CLOSE_POPUP' => $arParams['COMMON_SHOW_CLOSE_POPUP'] ?? '',
                'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                'USE_COMPARE_LIST' => 'Y',
                'BACKGROUND_IMAGE' => $arParams['SECTION_BACKGROUND_IMAGE'] ?? '',
                'COMPATIBLE_MODE' => $arParams['COMPATIBLE_MODE'] ?? '',
                'DISABLE_INIT_JS_IN_COMPONENT' => $arParams['DISABLE_INIT_JS_IN_COMPONENT'] ?? '',
                'BRAND_DATA' => $brandData,
                'PAGER_BASE_LINK' => $APPLICATION->GetCurPage(),
                'PAGER_BASE_LINK_ENABLE' => "Y",
                'AJAX' => $_REQUEST['ajaxCal'] === 'Y',
                'BLOCK_STYLE' => $_REQUEST['styleBlock'],
                    'HIDE_SMART_FILTER' => (bool)$hideSmartFilter,
            ),
            $component
        );

            if (!empty($brandData)) {
                $APPLICATION->SetPageProperty("title", $brandData["name"]);
            }
            ?>
    </div>
</main>