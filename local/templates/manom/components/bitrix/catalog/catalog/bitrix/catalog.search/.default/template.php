<?php

use Bitrix\Main\Loader;
use \Manom\Price;

$this->setFrameMode(true);

global $searchFilter;

$elementOrder = array();
if ($arParams['USE_SEARCH_RESULT_ORDER'] === 'N')
{
	$elementOrder = array(
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
		"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
	);
}

$elementOrder = array();

$sort = '';
$order = 'ASC';
$sortCode = $_REQUEST['sort_by'];
switch ($_REQUEST['sort_by']) {
    case "price_asc":
        $sort = 'SCALED_PRICE_' . Price::CURRENT_TYPE_ID;
        break;
    case "price_desc":
        $sort = 'SCALED_PRICE_' . Price::CURRENT_TYPE_ID;
        $order = 'DESC';
        break;
    case "pop":
        $sort = 'propertysort_SALELEADER';
        break;
    case "name":
        $sort = 'NAME';
        break;
    default:
        $sort = "ID";
        $order = $arElements;
        $sortCode = "relevance";
        break;
}


$elementOrder = [
    "ELEMENT_SORT_FIELD" => $sort,
    "ELEMENT_SORT_ORDER" => $order
];

$pageCountList = [
    "3"    => [
        "NAME" => "3",
    ],
    "6"    => [
        "NAME" => "6",
    ],
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

$pageCount = 12;
if (array_key_exists($_REQUEST["countOnPage"], $pageCountList)) {
    $pageCount = $_REQUEST["countOnPage"];
}
$pageCountList[$pageCount]["SELECTED"] = true;

if (Loader::includeModule('search'))
{
	$arElements = $APPLICATION->IncludeComponent(
		"bitrix:search.page",
		"catalog",
		Array(
            'ELEMENT_SORT_FIELD' => 'NAME',
            'TAGS_PAGE_ELEMENTS' => 150,
            'TAGS_PERIOD' => 30,
            'TAGS_URL_SEARCH' => '/search/index.php',
            'TAGS_INHERIT' => 'Y',
            'FONT_MAX' => 50,
            'FONT_MIN' => 10,
            'COLOR_NEW' => '000000',
            'COLOR_OLD' => 'C8C8C8',
            'PERIOD_NEW_TAGS' => '',
            'SHOW_CHAIN' => 'Y',
            'COLOR_TYPE' => 'Y',
            'WIDTH' => '100%',
            'USE_SUGGEST' => 'Y',
            'SHOW_RATING' => 'Y',
            'PATH_TO_USER_PROFILE' => '',
            'AJAX_MODE' => 'Y',
            'RESTART' => 'Y',
            'NO_WORD_LOGIC' => 'N',
            'USE_LANGUAGE_GUESS' => 'N',
            'CHECK_DATES' => 'Y',
            'USE_TITLE_RANK' => 'Y',
            'DEFAULT_SORT' => (int)$_REQUEST['search_sort'] === 1 ? 'date' : 'rank',
            'FILTER_NAME' => '',
            'arrFILTER' => array('iblock_catalog'),
            'SHOW_WHERE' => 'N',
            'arrWHERE' => '',
            'SHOW_WHEN' => 'N',
            'PAGE_RESULT_COUNT' => 9999,
            'CACHE_TYPE' => 'A',
            'CACHE_TIME' => 3600,
            'DISPLAY_TOP_PAGER' => 'Y',
            'DISPLAY_BOTTOM_PAGER' => 'Y',
            'PAGER_TITLE' => 'Результаты поиска',
            'PAGER_SHOW_ALWAYS' => 'Y',
            'PAGER_TEMPLATE' => 'catalog_section_alt',
            'AJAX_OPTION_SHADOW' => 'Y',
            'AJAX_OPTION_JUMP' => 'Y',
            'AJAX_OPTION_STYLE' => 'Y',
            'AJAX_OPTION_HISTORY' => 'Y',
            'AJAX_OPTION_ADDITIONAL' => '',
            'COMPONENT_TEMPLATE' => 'catalog',
            'SHOW_ITEM_TAGS' => 'Y',
            'SHOW_ITEM_DATE_CHANGE' => 'Y',
            'SHOW_ORDER_BY' => 'Y',
            'SHOW_TAGS_CLOUD' => 'N',
            'STRUCTURE_FILTER' => 'structure',
            'NAME_TEMPLATE' => '',
            'SHOW_LOGIN' => 'Y',
            'PATH_TO_SONET_MESSAGES_CHAT' => '/company/personal/messages/chat/#USER_ID#/',
            'arrFILTER_iblock_catalog' => array(6),
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);

    global $searchFilter;

    if (!isset($_REQUEST["q"])) {
        LocalRedirect(SITE_DIR . "catalog");
    } elseif (!empty($arElements) && is_array($arElements)) {
        $searchFilter = array(
            "ID" => $arElements,
        );
    } elseif (is_array($arElements) || (empty($_REQUEST["q"] && $arElements === false))) {
        ?>
        <div class="message-incorrect" style="text-align:center;">
            <p>Упс 🙁 По запросу «<?= $_REQUEST['q'] ?>» ничего не нашли</p>
            <br>
            <p>Попробуй изменить формулировку или воспользуйся нашим каталогом</p>
            Исправьте поисковую фразу и повторите поиск.
        </div>
        <?
        return;
    }
}
else
{
	$searchQuery = '';
	if (isset($_REQUEST['q']) && is_string($_REQUEST['q']))
		$searchQuery = trim($_REQUEST['q']);
	if ($searchQuery !== '')
	{
		$searchFilter = array(
			'*SEARCHABLE_CONTENT' => $searchQuery
		);
	}
	unset($searchQuery);
}

if (!empty($searchFilter) && is_array($searchFilter))
{
	$componentParams = array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
        'PAGE_ELEMENT_COUNT' => $pageCount,
        'SORT_CODE' => $sortCode,
        'PAGE_COUNT_LIST' => $pageCountList,
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
		"PROPERTY_CODE_MOBILE" => (isset($arParams["PROPERTY_CODE_MOBILE"]) ? $arParams["PROPERTY_CODE_MOBILE"] : []),
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
		"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
		"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
		"SECTION_URL" => $arParams["SECTION_URL"],
		"DETAIL_URL" => $arParams["DETAIL_URL"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
		"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
		"PRICE_CODE" => $arParams["~PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
		"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
		"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
		"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
		"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
		"CURRENCY_ID" => $arParams["CURRENCY_ID"],
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		"HIDE_NOT_AVAILABLE_OFFERS" => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"LAZY_LOAD" => (isset($arParams["LAZY_LOAD"]) ? $arParams["LAZY_LOAD"] : 'N'),
		"MESS_BTN_LAZY_LOAD" => (isset($arParams["~MESS_BTN_LAZY_LOAD"]) ? $arParams["~MESS_BTN_LAZY_LOAD"] : ''),
		"LOAD_ON_SCROLL" => (isset($arParams["LOAD_ON_SCROLL"]) ? $arParams["LOAD_ON_SCROLL"] : 'N'),
		"FILTER_NAME" => "searchFilter",
		"SECTION_ID" => "",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(),
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y",
		"META_KEYWORDS" => "",
		"META_DESCRIPTION" => "",
		"BROWSER_TITLE" => "",
		"ADD_SECTIONS_CHAIN" => "N",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",

		'LABEL_PROP' => (isset($arParams['LABEL_PROP']) ? $arParams['LABEL_PROP'] : ''),
		'LABEL_PROP_MOBILE' => (isset($arParams['LABEL_PROP_MOBILE']) ? $arParams['LABEL_PROP_MOBILE'] : ''),
		'LABEL_PROP_POSITION' => (isset($arParams['LABEL_PROP_POSITION']) ? $arParams['LABEL_PROP_POSITION'] : ''),
		'ADD_PICT_PROP' => (isset($arParams['ADD_PICT_PROP']) ? $arParams['ADD_PICT_PROP'] : ''),
		'PRODUCT_DISPLAY_MODE' => (isset($arParams['PRODUCT_DISPLAY_MODE']) ? $arParams['PRODUCT_DISPLAY_MODE'] : ''),
		'PRODUCT_BLOCKS_ORDER' => (isset($arParams['PRODUCT_BLOCKS_ORDER']) ? $arParams['PRODUCT_BLOCKS_ORDER'] : ''),
		'PRODUCT_ROW_VARIANTS' => (isset($arParams['PRODUCT_ROW_VARIANTS']) ? $arParams['PRODUCT_ROW_VARIANTS'] : ''),
		'ENLARGE_PRODUCT' => (isset($arParams['ENLARGE_PRODUCT']) ? $arParams['ENLARGE_PRODUCT'] : ''),
		'ENLARGE_PROP' => (isset($arParams['ENLARGE_PROP']) ? $arParams['ENLARGE_PROP'] : ''),
		'SHOW_SLIDER' => (isset($arParams['SHOW_SLIDER']) ? $arParams['SHOW_SLIDER'] : 'Y'),
		'SLIDER_INTERVAL' => (isset($arParams['SLIDER_INTERVAL']) ? $arParams['SLIDER_INTERVAL'] : '3000'),
		'SLIDER_PROGRESS' => (isset($arParams['SLIDER_PROGRESS']) ? $arParams['SLIDER_PROGRESS'] : 'N'),

		'OFFER_ADD_PICT_PROP' => (isset($arParams['OFFER_ADD_PICT_PROP']) ? $arParams['OFFER_ADD_PICT_PROP'] : ''),
		'OFFER_TREE_PROPS' => (isset($arParams['OFFER_TREE_PROPS']) ? $arParams['OFFER_TREE_PROPS'] : []),
		'PRODUCT_SUBSCRIPTION' => (isset($arParams['PRODUCT_SUBSCRIPTION']) ? $arParams['PRODUCT_SUBSCRIPTION'] : ''),
		'SHOW_DISCOUNT_PERCENT' => (isset($arParams['SHOW_DISCOUNT_PERCENT']) ? $arParams['SHOW_DISCOUNT_PERCENT'] : ''),
		'SHOW_OLD_PRICE' => (isset($arParams['SHOW_OLD_PRICE']) ? $arParams['SHOW_OLD_PRICE'] : ''),
		'SHOW_MAX_QUANTITY' => (isset($arParams['SHOW_MAX_QUANTITY']) ? $arParams['SHOW_MAX_QUANTITY'] : ''),
		'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
		'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
		'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
		'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
		'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
		'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
		'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
		'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
		'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
		'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

		'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
		'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
		'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

		'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
		'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
		'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
		'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),
		'COMPARE_NAME' => (isset($arParams['COMPARE_NAME']) ? $arParams['COMPARE_NAME'] : ''),
		'USE_COMPARE_LIST' => (isset($arParams['USE_COMPARE_LIST']) ? $arParams['USE_COMPARE_LIST'] : ''),
		'AJAX' => $_REQUEST['ajaxCal'] === 'Y',
		'BLOCK_STYLE' => $_REQUEST['styleBlock'],
		'IS_SEARCH' => "Y",
	) + $elementOrder;

    if ($_REQUEST['ajaxCal'] === 'Y') {
        $GLOBALS['APPLICATION']->RestartBuffer();
    }

	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		".default",
		$componentParams,
		$arResult["THEME_COMPONENT"],
		array('HIDE_ICONS' => 'Y')
	);
}