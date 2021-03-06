<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Context;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;
use Manom\Product;
use \Manom\WeekTools;

$request = Context::getCurrent()->getRequest();

global $userCityByGeoIP;

$detailProperties = PropertyTable::getList(
    [
        'filter' => ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'],
        'select' => ['IBLOCK_ID', 'ID', 'CODE'],
    ]
)->fetchAll();

$detailProperties = array_map(
    static function ($item) {
        return $item['CODE'];
    },
    $detailProperties
);

$basket = array();
$basketItems = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite())->getOrderableItems();
foreach ($basketItems as $basketItem) {
    $basket[$basketItem->getProductId()] = $basketItem->getFieldValues();
}

if (empty($arResult['VARIABLES']['ELEMENT_ID'])) {
    $filter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $arResult['VARIABLES']['ELEMENT_CODE']);
    $select = array('IBLOCK_ID', 'ID');
    $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
    if ($row = $result->Fetch()) {
        $id = $row['ID'];
    }
} else {
    $id = $arResult['VARIABLES']['ELEMENT_ID'];
}

$ecommerceData = array();
if (!empty($id)) {
    $product = new Product;
    $ecommerceData = $product->getEcommerceData(array($id), $arParams['IBLOCK_ID']);
    $ecommerceData = $ecommerceData[$id];
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
<?php if ($request->get('ajax') === 'Y') {
    $APPLICATION->RestartBuffer();
} ?>
    <main class="product container">
<?php $productId = $APPLICATION->IncludeComponent(
    'bitrix:catalog.element',
    '',
    array(
        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        'PROPERTY_CODE' => $detailProperties,
        'META_KEYWORDS' => $arParams['DETAIL_META_KEYWORDS'],
        'META_DESCRIPTION' => $arParams['DETAIL_META_DESCRIPTION'],
        'BROWSER_TITLE' => $arParams['DETAIL_BROWSER_TITLE'],
        'SET_CANONICAL_URL' => $arParams['DETAIL_SET_CANONICAL_URL'],
        'BASKET_URL' => $arParams['BASKET_URL'],
        'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
        'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
        'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
        'CHECK_SECTION_ID_VARIABLE' => $arParams['DETAIL_CHECK_SECTION_ID_VARIABLE'] ?? '',
        'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
        'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
        'CACHE_TIME' => $arParams['CACHE_TIME'],
        'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
        'SET_TITLE' => $arParams['SET_TITLE'],
        'SET_LAST_MODIFIED' => $arParams['SET_LAST_MODIFIED'],
        'MESSAGE_404' => $arParams['~MESSAGE_404'],
        'SET_STATUS_404' => $arParams['SET_STATUS_404'],
        'SHOW_404' => $arParams['SHOW_404'],
        'FILE_404' => $arParams['FILE_404'],
        'PRICE_CODE' => $arParams['~PRICE_CODE'],
        'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
        'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
        'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
        'PRICE_VAT_SHOW_VALUE' => $arParams['PRICE_VAT_SHOW_VALUE'],
        'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
        'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES'] ?? [],
        'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'] ?? '',
        'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'] ?? '',
        'LINK_IBLOCK_TYPE' => $arParams['LINK_IBLOCK_TYPE'],
        'LINK_IBLOCK_ID' => $arParams['LINK_IBLOCK_ID'],
        'LINK_PROPERTY_SID' => $arParams['LINK_PROPERTY_SID'],
        'LINK_ELEMENTS_URL' => $arParams['LINK_ELEMENTS_URL'],
        'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'] ?? [],
        'OFFERS_FIELD_CODE' => $arParams['DETAIL_OFFERS_FIELD_CODE'],
        'OFFERS_PROPERTY_CODE' => $arParams['DETAIL_OFFERS_PROPERTY_CODE'] ?? [],
        'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
        'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
        'OFFERS_SORT_FIELD2' => $arParams['OFFERS_SORT_FIELD2'],
        'OFFERS_SORT_ORDER2' => $arParams['OFFERS_SORT_ORDER2'],
        'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
        'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
        'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
        'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
        'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
        'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['element'],
        'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
        'CURRENCY_ID' => $arParams['CURRENCY_ID'],
        'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
        'HIDE_NOT_AVAILABLE_OFFERS' => $arParams['HIDE_NOT_AVAILABLE_OFFERS'],
        'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],
        'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
        'USE_MAIN_ELEMENT_SECTION' => $arParams['USE_MAIN_ELEMENT_SECTION'],
        'STRICT_SECTION_CHECK' => $arParams['DETAIL_STRICT_SECTION_CHECK'] ?? '',
        'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
        'LABEL_PROP' => $arParams['LABEL_PROP'],
        'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
        'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
        'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
        'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'] ?? [],
        'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
        'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'] ?? '',
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
        'MESS_PRICE_RANGES_TITLE' => $arParams['~MESS_PRICE_RANGES_TITLE'] ?? '',
        'MESS_DESCRIPTION_TAB' => $arParams['~MESS_DESCRIPTION_TAB'] ?? '',
        'MESS_PROPERTIES_TAB' => $arParams['~MESS_PROPERTIES_TAB'] ?? '',
        'MESS_COMMENTS_TAB' => $arParams['~MESS_COMMENTS_TAB'] ?? '',
        'MAIN_BLOCK_PROPERTY_CODE' => $arParams['DETAIL_MAIN_BLOCK_PROPERTY_CODE'] ?? '',
        'MAIN_BLOCK_OFFERS_PROPERTY_CODE' => $arParams['DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE'] ?? '',
        'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
        'VOTE_DISPLAY_AS_RATING' => $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] ?? '',
        'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
        'BLOG_USE' => $arParams['DETAIL_BLOG_USE'] ?? '',
        'BLOG_URL' => $arParams['DETAIL_BLOG_URL'] ?? '',
        'BLOG_EMAIL_NOTIFY' => $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] ?? '',
        'VK_USE' => $arParams['DETAIL_VK_USE'] ?? '',
        'VK_API_ID' => $arParams['DETAIL_VK_API_ID'] ?? 'API_ID',
        'FB_USE' => $arParams['DETAIL_FB_USE'] ?? '',
        'FB_APP_ID' => $arParams['DETAIL_FB_APP_ID'] ?? '',
        'BRAND_USE' => $arParams['DETAIL_BRAND_USE'] ?? 'N',
        'BRAND_PROP_CODE' => $arParams['DETAIL_BRAND_PROP_CODE'] ?? '',
        'DISPLAY_NAME' => $arParams['DETAIL_DISPLAY_NAME'] ?? '',
        'IMAGE_RESOLUTION' => $arParams['DETAIL_IMAGE_RESOLUTION'] ?? '',
        'PRODUCT_INFO_BLOCK_ORDER' => $arParams['DETAIL_PRODUCT_INFO_BLOCK_ORDER'] ?? '',
        'PRODUCT_PAY_BLOCK_ORDER' => $arParams['DETAIL_PRODUCT_PAY_BLOCK_ORDER'] ?? '',
        'ADD_DETAIL_TO_SLIDER' => $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] ?? '',
        'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'] ?? '',
        'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'] ?? '',
        'ADD_ELEMENT_CHAIN' => $arParams['ADD_ELEMENT_CHAIN'] ?? '',
        'DISPLAY_PREVIEW_TEXT_MODE' => $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] ?? '',
        'DETAIL_PICTURE_MODE' => $arParams['DETAIL_DETAIL_PICTURE_MODE'] ?? array(),
        'ADD_TO_BASKET_ACTION' => $arParams['DETAIL_ADD_TO_BASKET_ACTION'] ?? array(),
        'ADD_TO_BASKET_ACTION_PRIMARY' => $arParams['DETAIL_ADD_TO_BASKET_ACTION_PRIMARY'] ?? null,
        'SHOW_CLOSE_POPUP' => $arParams['COMMON_SHOW_CLOSE_POPUP'] ?? '',
        'DISPLAY_COMPARE' => $arParams['USE_COMPARE'] ?? '',
        'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
        'USE_COMPARE_LIST' => 'N',
        'BACKGROUND_IMAGE' => $arParams['DETAIL_BACKGROUND_IMAGE'] ?? '',
        'COMPATIBLE_MODE' => $arParams['COMPATIBLE_MODE'] ?? '',
        'DISABLE_INIT_JS_IN_COMPONENT' => $arParams['DISABLE_INIT_JS_IN_COMPONENT'] ?? '',
        'SET_VIEWED_IN_COMPONENT' => $arParams['DETAIL_SET_VIEWED_IN_COMPONENT'] ?? '',
        'SHOW_SLIDER' => $arParams['DETAIL_SHOW_SLIDER'] ?? '',
        'SLIDER_INTERVAL' => $arParams['DETAIL_SLIDER_INTERVAL'] ?? '',
        'SLIDER_PROGRESS' => $arParams['DETAIL_SLIDER_PROGRESS'] ?? '',
        'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'] ?? '',
        'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'] ?? '',
        'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'] ?? '',
        'USE_GIFTS_DETAIL' => $arParams['USE_GIFTS_DETAIL'] ?: 'Y',
        'USE_GIFTS_MAIN_PR_SECTION_LIST' => $arParams['USE_GIFTS_MAIN_PR_SECTION_LIST'] ?: 'Y',
        'GIFTS_SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
        'GIFTS_SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
        'GIFTS_DETAIL_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_DETAIL_PAGE_ELEMENT_COUNT'],
        'GIFTS_DETAIL_HIDE_BLOCK_TITLE' => $arParams['GIFTS_DETAIL_HIDE_BLOCK_TITLE'],
        'GIFTS_DETAIL_TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],
        'GIFTS_DETAIL_BLOCK_TITLE' => $arParams['GIFTS_DETAIL_BLOCK_TITLE'],
        'GIFTS_SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
        'GIFTS_SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
        'GIFTS_MESS_BTN_BUY' => $arParams['~GIFTS_MESS_BTN_BUY'],
        'GIFTS_PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
        'GIFTS_SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
        'GIFTS_SLIDER_INTERVAL' => $arParams['LIST_SLIDER_INTERVAL'] ?? '',
        'GIFTS_SLIDER_PROGRESS' => $arParams['LIST_SLIDER_PROGRESS'] ?? '',
        'GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT'],
        'GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE'],
        'GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE' => $arParams['GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE'],
        'USER_CONSENT' => $arParams['USER_CONSENT'],
        'USER_CONSENT_ID' => $arParams['USER_CONSENT_ID'],
        'USER_CONSENT_IS_CHECKED' => $arParams['USER_CONSENT_IS_CHECKED'],
        'USER_CONSENT_IS_LOADED' => $arParams['USER_CONSENT_IS_LOADED'],
        'LOCATION' => $userCityByGeoIP,
        'BASKET' => $basket,
        'CURRENT_DIR' => $APPLICATION->GetCurDir(),
        'ECOMMERCE_DATA' => $ecommerceData,
        'CACHE_CLEANER' => (new WeekTools())->getDateCacheCleaner(),
    ),
    $component
); ?>

<? global $viewedFilter;
$viewedFilter = [
    '!ID' => $productId,
    '>CATALOG_PRICE_1' => 0,
];

$APPLICATION->IncludeComponent(
    "bitrix:catalog.products.viewed",
    "product_card",
    Array(
        "ACTION_VARIABLE" => "",
        "ADDITIONAL_PICT_PROP_2" => "MORE_PHOTO",
        "ADDITIONAL_PICT_PROP_3" => "-",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "ADD_TO_BASKET_ACTION" => "BUY",
        "BASKET_URL" => "",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A",
        "CART_PROPERTIES_2" => array("NEWPRODUCT", "NEWPRODUCT,SALELEADER", ""),
        "CART_PROPERTIES_3" => array("COLOR_REF", "SIZES_SHOES", ""),
        "CONVERT_CURRENCY" => "Y",
        "CURRENCY_ID" => "RUB",
        "DATA_LAYER_NAME" => "dataLayer",
        "DEPTH" => "",
        "DISCOUNT_PERCENT_POSITION" => "",
        "ENLARGE_PRODUCT" => "",
        "ENLARGE_PROP_2" => "",
        "HIDE_NOT_AVAILABLE" => "Y",
        "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
        "FILTER_NAME" => "viewedFilter",
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "IBLOCK_MODE" => "single",
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "LABEL_PROP_2" => array("NEWPRODUCT"),
        "LABEL_PROP_MOBILE_2" => array(),
        "LABEL_PROP_POSITION" => "top-left",
        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
        "MESS_BTN_BUY" => "Купить",
        "MESS_BTN_DETAIL" => "Подробнее",
        "MESS_BTN_SUBSCRIBE" => "Подписаться",
        "MESS_NOT_AVAILABLE" => "Нет в наличии",
        "MESS_RELATIVE_QUANTITY_FEW" => "мало",
        "MESS_RELATIVE_QUANTITY_MANY" => "много",
        "MESS_SHOW_MAX_QUANTITY" => "Наличие",
        "OFFER_TREE_PROPS_3" => array("COLOR_REF", "SIZES_SHOES", "SIZES_CLOTHES"),
        "PAGE_ELEMENT_COUNT" => 10,
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "PRICE_CODE" => array('Цена продажи', 'РРЦ'),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_BLOCKS_ORDER" => "price,props,quantityLimit,sku,quantity,buttons,compare",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PRODUCT_QUANTITY_VARIABLE" => "",
        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
        "PRODUCT_SUBSCRIPTION" => "Y",
        "PROPERTY_CODE_" . $arParams["IBLOCK_ID"] => array("MORE_PHOTO"),
        "PROPERTY_CODE_MOBILE_2" => array(),
        "RELATIVE_QUANTITY_FACTOR" => "5",
        "SECTION_CODE" => "",
        "SECTION_ELEMENT_CODE" => "",
        "SECTION_ELEMENT_ID" => "",
        "SECTION_ID" => "",
        "SHOW_CLOSE_POPUP" => "N",
        "SHOW_DISCOUNT_PERCENT" => "Y",
        "SHOW_FROM_SECTION" => "N",
        "SHOW_MAX_QUANTITY" => "N",
        "SHOW_OLD_PRICE" => "N",
        "SHOW_PRICE_COUNT" => "1",
        "SHOW_PRODUCTS_2" => "N",
        "SHOW_SLIDER" => "Y",
        "SLIDER_INTERVAL" => "3000",
        "SLIDER_PROGRESS" => "Y",
        "TEMPLATE_THEME" => "blue",
        "USE_ENHANCED_ECOMMERCE" => "N",
        "USE_PRICE_COUNT" => "N",
        "USE_PRODUCT_QUANTITY" => "N"
    )
);
?>
    </main>

<?
global $productCardRecommendIds;
if (!empty($productCardRecommendIds)) {
    global $recommendedFilter;
    $recommendedFilter = array(
        'ID' => $productCardRecommendIds,
        '>CATALOG_PRICE_1' => 0,
    );
    $APPLICATION->IncludeComponent(
        'bitrix:catalog.section',
        'recom',
        Array(
            'ACTION_VARIABLE' => '',
            'ADD_PICT_PROP' => '',
            'ADD_PROPERTIES_TO_BASKET' => 'N',
            'ADD_SECTIONS_CHAIN' => 'N',
            'ADD_TO_BASKET_ACTION' => '',
            'AJAX_MODE' => 'N',
            'AJAX_OPTION_ADDITIONAL' => '',
            'AJAX_OPTION_HISTORY' => 'N',
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_STYLE' => 'N',
            'BACKGROUND_IMAGE' => '',
            'BASKET_URL' => '',
            'BRAND_PROPERTY' => '',
            'BROWSER_TITLE' => '',
            'CACHE_FILTER' => 'N',
            'CACHE_GROUPS' => 'N',
            'CACHE_TIME' => 36000000,
            'CACHE_TYPE' => 'A',
            'COMPATIBLE_MODE' => 'Y',
            'CONVERT_CURRENCY' => 'Y',
            'CURRENCY_ID' => 'RUB',
            'CUSTOM_FILTER' => '',
            'DATA_LAYER_NAME' => '',
            'DETAIL_URL' => '',
            'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
            'DISCOUNT_PERCENT_POSITION' => '',
            'DISPLAY_BOTTOM_PAGER' => 'N',
            'DISPLAY_TOP_PAGER' => 'N',
            'ELEMENT_SORT_FIELD' => 'sort',
            'ELEMENT_SORT_FIELD2' => 'id',
            'ELEMENT_SORT_ORDER' => 'asc',
            'ELEMENT_SORT_ORDER2' => 'desc',
            'ENLARGE_PRODUCT' => '',
            'ENLARGE_PROP' => '',
            'FILTER_NAME' => 'recommendedFilter',
            'HIDE_NOT_AVAILABLE' => 'Y',
            'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
            'IBLOCK_ID' => 6,
            'IBLOCK_TYPE' => 'catalog',
            'INCLUDE_SUBSECTIONS' => 'Y',
            'LABEL_PROP' => '',
            'LABEL_PROP_MOBILE' => '',
            'LABEL_PROP_POSITION' => '',
            'LAZY_LOAD' => 'N',
            'LINE_ELEMENT_COUNT' => 0,
            'LOAD_ON_SCROLL' => 'N',
            'MESSAGE_404' => '',
            'MESS_BTN_ADD_TO_BASKET' => '',
            'MESS_BTN_BUY' => '',
            'MESS_BTN_DETAIL' => '',
            'MESS_BTN_LAZY_LOAD' => '',
            'MESS_BTN_SUBSCRIBE' => '',
            'MESS_NOT_AVAILABLE' => '',
            'META_DESCRIPTION' => '',
            'META_KEYWORDS' => '',
            'OFFERS_CART_PROPERTIES' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'OFFERS_FIELD_CODE' => array(),
            'OFFERS_LIMIT' => 0,
            'OFFERS_PROPERTY_CODE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'OFFERS_SORT_FIELD' => 'sort',
            'OFFERS_SORT_FIELD2' => 'id',
            'OFFERS_SORT_ORDER' => 'asc',
            'OFFERS_SORT_ORDER2' => 'desc',
            'OFFER_ADD_PICT_PROP' => '',
            'OFFER_TREE_PROPS' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'PAGER_BASE_LINK_ENABLE' => 'N',
            'PAGER_DESC_NUMBERING' => 'N',
            'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
            'PAGER_SHOW_ALL' => 'N',
            'PAGER_SHOW_ALWAYS' => 'N',
            'PAGER_TEMPLATE' => '',
            'PAGER_TITLE' => '',
            'PAGE_ELEMENT_COUNT' => 6,
            'PARTIAL_PRODUCT_PROPERTIES' => 'N',
            'PRICE_CODE' => array('Цена продажи', 'РРЦ'),
            'PRICE_VAT_INCLUDE' => 'Y',
            'PRODUCT_BLOCKS_ORDER' => '',
            'PRODUCT_DISPLAY_MODE' => '',
            'PRODUCT_ID_VARIABLE' => '',
            'PRODUCT_PROPERTIES' => '',
            'PRODUCT_PROPS_VARIABLE' => '',
            'PRODUCT_QUANTITY_VARIABLE' => '',
            'PRODUCT_ROW_VARIANTS' => '',
            'PRODUCT_SUBSCRIPTION' => 'N',
            'PROPERTY_CODE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'PROPERTY_CODE_MOBILE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'RCM_PROD_ID' => '',
            'RCM_TYPE' => '',
            'SECTION_CODE' => '',
            'SECTION_ID' => '',
            'SECTION_ID_VARIABLE' => '',
            'SECTION_URL' => '',
            'SECTION_USER_FIELDS' => array(),
            'SEF_MODE' => 'N',
            'SET_BROWSER_TITLE' => 'N',
            'SET_LAST_MODIFIED' => 'N',
            'SET_META_DESCRIPTION' => 'N',
            'SET_META_KEYWORDS' => 'N',
            'SET_STATUS_404' => 'N',
            'SET_TITLE' => 'N',
            'SHOW_404' => 'N',
            'SHOW_ALL_WO_SECTION' => 'Y',
            'SHOW_CLOSE_POPUP' => 'N',
            'SHOW_DISCOUNT_PERCENT' => 'N',
            'SHOW_FROM_SECTION' => 'N',
            'SHOW_MAX_QUANTITY' => 'N',
            'SHOW_OLD_PRICE' => 'N',
            'SHOW_PRICE_COUNT' => 1,
            'SHOW_SLIDER' => 'N',
            'SLIDER_INTERVAL' => 3000,
            'SLIDER_PROGRESS' => 'N',
            'TEMPLATE_THEME' => '',
            'USE_ENHANCED_ECOMMERCE' => 'N',
            'USE_MAIN_ELEMENT_SECTION' => 'N',
            'USE_PRICE_COUNT' => 'N',
            'USE_PRODUCT_QUANTITY' => 'N',
            'DISPLAY_COMPARE' => 'N',
        ),
        false
    );
} ?>

<?php if ($request->get('ajax') === 'Y') {
    die();
} ?>