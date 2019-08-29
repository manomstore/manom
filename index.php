<?
//die();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Manom");
$APPLICATION->SetPageProperty("description", "Manom");
$APPLICATION->SetPageProperty("title", "Manom");
$APPLICATION->SetTitle("Manom");
?>

<div class="content">
	<!-- Слайдер -->
	<section class='first-scr'>
			<div class="container">
					<div class="row first-scr__block">
							<?$APPLICATION->IncludeComponent(
									"bitrix:news.list",
									"main_slider",
									Array(
											"DISPLAY_DATE" => "Y",
											"DISPLAY_NAME" => "Y",
											"DISPLAY_PICTURE" => "Y",
											"DISPLAY_PREVIEW_TEXT" => "Y",
											"AJAX_MODE" => "N",
											"IBLOCK_TYPE" => "content",
											"IBLOCK_ID" => "1",
											"NEWS_COUNT" => "20",
											"SORT_BY1" => "ACTIVE_FROM",
											"SORT_ORDER1" => "DESC",
											"SORT_BY2" => "SORT",
											"SORT_ORDER2" => "ASC",
											"FILTER_NAME" => "",
											"FIELD_CODE" => array("SLIDER_LINK"),
											"PROPERTY_CODE" => array("SLIDER_LINK"),
											"CHECK_DATES" => "Y",
											"DETAIL_URL" => "",
											"PREVIEW_TRUNCATE_LEN" => "",
											"ACTIVE_DATE_FORMAT" => "",
											"SET_TITLE" => "N",
											"SET_STATUS_404" => "N",
											"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
											"ADD_SECTIONS_CHAIN" => "N",
											"HIDE_LINK_WHEN_NO_DETAIL" => "N",
											"PARENT_SECTION" => "",
											"PARENT_SECTION_CODE" => "",
											"CACHE_TYPE" => "A",
											"CACHE_TIME" => "36000000",
											"CACHE_NOTES" => "",
											"CACHE_FILTER" => "N",
											"CACHE_GROUPS" => "N",
											"DISPLAY_TOP_PAGER" => "N",
											"DISPLAY_BOTTOM_PAGER" => "N",
											"PAGER_TITLE" => "Слайдер",
											"PAGER_SHOW_ALWAYS" => "N",
											"PAGER_TEMPLATE" => "",
											"PAGER_DESC_NUMBERING" => "N",
											"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
											"PAGER_SHOW_ALL" => "N",
											"AJAX_OPTION_JUMP" => "N",
											"AJAX_OPTION_STYLE" => "Y",
											"AJAX_OPTION_HISTORY" => "N",
											"AJAX_OPTION_ADDITIONAL" => ""
									)
							);?>
							<div class="first-scr__right">
									<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"item_day",
		array(
			"ACTION_VARIABLE" => "action",
			"ADD_PICT_PROP" => "MORE_PHOTO",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_SECTIONS_CHAIN" => "N",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
			"BASKET_URL" => "/personal/basket.php",
			"BRAND_PROPERTY" => "-",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "N",
			"COMPATIBLE_MODE" => "Y",
			"CONVERT_CURRENCY" => "Y",
			"CURRENCY_ID" => "RUB",
			"CUSTOM_FILTER" => "",
			"DATA_LAYER_NAME" => "dataLayer",
			"DETAIL_URL" => "",
			"DISABLE_INIT_JS_IN_COMPONENT" => "N",
			"DISCOUNT_PERCENT_POSITION" => "bottom-right",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "sort",
			"ELEMENT_SORT_FIELD2" => "id",
			"ELEMENT_SORT_ORDER" => "asc",
			"ELEMENT_SORT_ORDER2" => "desc",
			"ENLARGE_PRODUCT" => "PROP",
			"ENLARGE_PROP" => "-",
			"FILTER_NAME" => "arrFilter",
			"HIDE_NOT_AVAILABLE" => "N",
			"HIDE_NOT_AVAILABLE_OFFERS" => "N",
			"IBLOCK_ID" => "7",
			"IBLOCK_TYPE" => "catalog",
			"INCLUDE_SUBSECTIONS" => "Y",
			"LABEL_PROP" => array(
			),
			"LABEL_PROP_MOBILE" => "",
			"LABEL_PROP_POSITION" => "top-left",
			"LAZY_LOAD" => "Y",
			"LINE_ELEMENT_COUNT" => "3",
			"LOAD_ON_SCROLL" => "N",
			"MESSAGE_404" => "",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_LAZY_LOAD" => "Показать ещё",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_CART_PROPERTIES" => array(
				0 => "COLOR_REF",
			),
			"OFFERS_FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"OFFERS_LIMIT" => "5",
			"OFFERS_PROPERTY_CODE" => array(
				0 => "COLOR_REF",
				1 => "SIZES_SHOES",
				2 => "SIZES_CLOTHES",
				3 => "",
			),
			"OFFERS_SORT_FIELD" => "sort",
			"OFFERS_SORT_FIELD2" => "id",
			"OFFERS_SORT_ORDER" => "asc",
			"OFFERS_SORT_ORDER2" => "desc",
			"OFFER_ADD_PICT_PROP" => "-",
			"OFFER_TREE_PROPS" => array(
				0 => "COLOR_REF",
			),
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => "6",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array(
			),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_BLOCKS_ORDER" => "",
			"PRODUCT_DISPLAY_MODE" => "Y",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(
			),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_QUANTITY_VARIABLE" => "",
			"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
			"PRODUCT_SUBSCRIPTION" => "Y",
			"PROPERTY_CODE" => array(
				0 => "",
				1 => "SELL_PROD",
				2 => "NEWPRODUCT",
				3 => "",
			),
			"PROPERTY_CODE_MOBILE" => array(
			),
			"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
			"RCM_TYPE" => "personal",
			"SECTION_CODE" => "",
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array(
				0 => "",
				1 => "",
			),
			"SEF_MODE" => "N",
			"SET_BROWSER_TITLE" => "Y",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "Y",
			"SET_META_KEYWORDS" => "Y",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "Y",
			"SHOW_404" => "N",
			"SHOW_ALL_WO_SECTION" => "Y",
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "Y",
			"SHOW_FROM_SECTION" => "N",
			"SHOW_MAX_QUANTITY" => "N",
			"SHOW_OLD_PRICE" => "Y",
			"SHOW_PRICE_COUNT" => "",
			"SHOW_SLIDER" => "Y",
			"SLIDER_INTERVAL" => "3000",
			"SLIDER_PROGRESS" => "N",
			"TEMPLATE_THEME" => "blue",
			"USE_ENHANCED_ECOMMERCE" => "Y",
			"USE_MAIN_ELEMENT_SECTION" => "N",
			"USE_PRICE_COUNT" => "Y",
			"USE_PRODUCT_QUANTITY" => "N",
			"COMPONENT_TEMPLATE" => "item_day",
			"DISPLAY_COMPARE" => "N"
		),
		false
	);?>
							</div>
					</div>
			</div>
	</section>

	<!-- Выгоды -->
	<section class="benefit">
			<?$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"benefit",
				Array(
						"DISPLAY_DATE" => "Y",
						"DISPLAY_NAME" => "Y",
						"DISPLAY_PICTURE" => "Y",
						"DISPLAY_PREVIEW_TEXT" => "Y",
						"AJAX_MODE" => "N",
						"IBLOCK_TYPE" => "content",
						"IBLOCK_ID" => "2",
						"NEWS_COUNT" => "20",
						"SORT_BY1" => "ACTIVE_FROM",
						"SORT_ORDER1" => "DESC",
						"SORT_BY2" => "SORT",
						"SORT_ORDER2" => "ASC",
						"FILTER_NAME" => "",
						"FIELD_CODE" => array("SLIDER_LINK"),
						"PROPERTY_CODE" => array("SLIDER_LINK"),
						"CHECK_DATES" => "Y",
						"DETAIL_URL" => "",
						"PREVIEW_TRUNCATE_LEN" => "",
						"ACTIVE_DATE_FORMAT" => "",
						"SET_TITLE" => "N",
						"SET_STATUS_404" => "N",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"ADD_SECTIONS_CHAIN" => "N",
						"HIDE_LINK_WHEN_NO_DETAIL" => "N",
						"PARENT_SECTION" => "",
						"PARENT_SECTION_CODE" => "",
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "36000000",
						"CACHE_NOTES" => "",
						"CACHE_FILTER" => "N",
						"CACHE_GROUPS" => "N",
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "N",
						"PAGER_TITLE" => "Слайдер",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_TEMPLATE" => "",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "N",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "Y",
						"AJAX_OPTION_HISTORY" => "N",
						"AJAX_OPTION_ADDITIONAL" => ""
				)
			);?>
	</section>

	<!-- Товары по акции -->

				<?$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"product_stock",
		array(
			"ACTION_VARIABLE" => "action",
			"ADD_PICT_PROP" => "MORE_PHOTO",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"ADD_SECTIONS_CHAIN" => "N",
			"ADD_TO_BASKET_ACTION" => "ADD",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
			"BASKET_URL" => "/personal/basket.php",
			"BRAND_PROPERTY" => "-",
			"BROWSER_TITLE" => "-",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "N",
			"COMPATIBLE_MODE" => "Y",
			"CONVERT_CURRENCY" => "Y",
			"CURRENCY_ID" => "RUB",
			"CUSTOM_FILTER" => "",
			"DATA_LAYER_NAME" => "dataLayer",
			"DETAIL_URL" => "",
			"DISABLE_INIT_JS_IN_COMPONENT" => "N",
			"DISCOUNT_PERCENT_POSITION" => "bottom-right",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"ELEMENT_SORT_FIELD" => "sort",
			"ELEMENT_SORT_FIELD2" => "id",
			"ELEMENT_SORT_ORDER" => "asc",
			"ELEMENT_SORT_ORDER2" => "desc",
			"ENLARGE_PRODUCT" => "PROP",
			"ENLARGE_PROP" => "-",
			"FILTER_NAME" => "arrFilter",
			"HIDE_NOT_AVAILABLE" => "N",
			"HIDE_NOT_AVAILABLE_OFFERS" => "N",
			"IBLOCK_ID" => "7",
			"IBLOCK_TYPE" => "catalog",
			"INCLUDE_SUBSECTIONS" => "Y",
			"LABEL_PROP" => array(
			),
			"LABEL_PROP_MOBILE" => "",
			"LABEL_PROP_POSITION" => "top-left",
			"LAZY_LOAD" => "Y",
			"LINE_ELEMENT_COUNT" => "3",
			"LOAD_ON_SCROLL" => "N",
			"MESSAGE_404" => "",
			"MESS_BTN_ADD_TO_BASKET" => "В корзину",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_BTN_LAZY_LOAD" => "Показать ещё",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"META_DESCRIPTION" => "-",
			"META_KEYWORDS" => "-",
			"OFFERS_CART_PROPERTIES" => array(
				0 => "COLOR_REF",
			),
			"OFFERS_FIELD_CODE" => array(
				0 => "",
				1 => "",
			),
			"OFFERS_LIMIT" => "5",
			"OFFERS_PROPERTY_CODE" => array(
				0 => "COLOR_REF",
				1 => "SIZES_SHOES",
				2 => "SIZES_CLOTHES",
				3 => "",
			),
			"OFFERS_SORT_FIELD" => "sort",
			"OFFERS_SORT_FIELD2" => "id",
			"OFFERS_SORT_ORDER" => "asc",
			"OFFERS_SORT_ORDER2" => "desc",
			"OFFER_ADD_PICT_PROP" => "-",
			"OFFER_TREE_PROPS" => array(
				0 => "COLOR_REF",
			),
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "",
			"PAGER_TITLE" => "Товары",
			"PAGE_ELEMENT_COUNT" => "6",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"PRICE_CODE" => array(
			),
			"PRICE_VAT_INCLUDE" => "Y",
			"PRODUCT_BLOCKS_ORDER" => "",
			"PRODUCT_DISPLAY_MODE" => "Y",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_PROPERTIES" => array(
			),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_QUANTITY_VARIABLE" => "",
			"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
			"PRODUCT_SUBSCRIPTION" => "Y",
			"PROPERTY_CODE" => array(
				0 => "",
				1 => "STOCK_PRODUCT",
				2 => "SELL_PROD",
				3 => "PRODUCT_OF_THE_DAY",
				4 => "NEWPRODUCT",
				5 => "",
			),
			"PROPERTY_CODE_MOBILE" => array(
			),
			"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
			"RCM_TYPE" => "personal",
			"SECTION_CODE" => "",
			"SECTION_ID" => "",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "",
			"SECTION_USER_FIELDS" => array(
				0 => "",
				1 => "",
			),
			"SEF_MODE" => "N",
			"SET_BROWSER_TITLE" => "Y",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "Y",
			"SET_META_KEYWORDS" => "Y",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "Y",
			"SHOW_404" => "N",
			"SHOW_ALL_WO_SECTION" => "Y",
			"SHOW_CLOSE_POPUP" => "N",
			"SHOW_DISCOUNT_PERCENT" => "Y",
			"SHOW_FROM_SECTION" => "N",
			"SHOW_MAX_QUANTITY" => "N",
			"SHOW_OLD_PRICE" => "Y",
			"SHOW_PRICE_COUNT" => "1",
			"SHOW_SLIDER" => "Y",
			"SLIDER_INTERVAL" => "3000",
			"SLIDER_PROGRESS" => "N",
			"TEMPLATE_THEME" => "blue",
			"USE_ENHANCED_ECOMMERCE" => "Y",
			"USE_MAIN_ELEMENT_SECTION" => "N",
			"USE_PRICE_COUNT" => "Y",
			"USE_PRODUCT_QUANTITY" => "N",
			"COMPONENT_TEMPLATE" => "product_stock",
			"DISPLAY_COMPARE" => "N"
		),
		false
	);?>

	<!-- Новинки -->

					<?$APPLICATION->IncludeComponent(
							"bitrix:catalog.section",
							"new_product",
							array(
									"ACTION_VARIABLE" => "action",
									"ADD_PICT_PROP" => "MORE_PHOTO",
									"ADD_PROPERTIES_TO_BASKET" => "Y",
									"ADD_SECTIONS_CHAIN" => "N",
									"ADD_TO_BASKET_ACTION" => "ADD",
									"AJAX_MODE" => "N",
									"AJAX_OPTION_ADDITIONAL" => "",
									"AJAX_OPTION_HISTORY" => "N",
									"AJAX_OPTION_JUMP" => "N",
									"AJAX_OPTION_STYLE" => "Y",
									"BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
									"BASKET_URL" => "/personal/basket.php",
									"BRAND_PROPERTY" => "-",
									"BROWSER_TITLE" => "-",
									"CACHE_FILTER" => "N",
									"CACHE_GROUPS" => "Y",
									"CACHE_TIME" => "36000000",
									"CACHE_TYPE" => "A",
									"COMPATIBLE_MODE" => "Y",
									"CONVERT_CURRENCY" => "Y",
									"CURRENCY_ID" => "RUB",
									"CUSTOM_FILTER" => "",
									"DATA_LAYER_NAME" => "dataLayer",
									"DETAIL_URL" => "",
									"DISABLE_INIT_JS_IN_COMPONENT" => "N",
									"DISCOUNT_PERCENT_POSITION" => "bottom-right",
									"DISPLAY_BOTTOM_PAGER" => "Y",
									"DISPLAY_TOP_PAGER" => "N",
									"ELEMENT_SORT_FIELD" => "sort",
									"ELEMENT_SORT_FIELD2" => "id",
									"ELEMENT_SORT_ORDER" => "asc",
									"ELEMENT_SORT_ORDER2" => "desc",
									"ENLARGE_PRODUCT" => "PROP",
									"ENLARGE_PROP" => "-",
									"FILTER_NAME" => "arrFilter",
									"HIDE_NOT_AVAILABLE" => "N",
									"HIDE_NOT_AVAILABLE_OFFERS" => "N",
									"IBLOCK_ID" => "7",
									"IBLOCK_TYPE" => "catalog",
									"INCLUDE_SUBSECTIONS" => "Y",
									"LABEL_PROP" => array(
									),
									"LABEL_PROP_MOBILE" => "",
									"LABEL_PROP_POSITION" => "top-left",
									"LAZY_LOAD" => "Y",
									"LINE_ELEMENT_COUNT" => "3",
									"LOAD_ON_SCROLL" => "N",
									"MESSAGE_404" => "",
									"MESS_BTN_ADD_TO_BASKET" => "В корзину",
									"MESS_BTN_BUY" => "Купить",
									"MESS_BTN_DETAIL" => "Подробнее",
									"MESS_BTN_LAZY_LOAD" => "Показать ещё",
									"MESS_BTN_SUBSCRIBE" => "Подписаться",
									"MESS_NOT_AVAILABLE" => "Нет в наличии",
									"META_DESCRIPTION" => "-",
									"META_KEYWORDS" => "-",
									"OFFERS_CART_PROPERTIES" => array(
											0 => "COLOR_REF",
									),
									"OFFERS_FIELD_CODE" => array(
											0 => "",
											1 => "",
									),
									"OFFERS_LIMIT" => "5",
									"OFFERS_PROPERTY_CODE" => array(
											0 => "COLOR_REF",
											1 => "SIZES_SHOES",
											2 => "SIZES_CLOTHES",
											3 => "",
									),
									"OFFERS_SORT_FIELD" => "sort",
									"OFFERS_SORT_FIELD2" => "id",
									"OFFERS_SORT_ORDER" => "asc",
									"OFFERS_SORT_ORDER2" => "desc",
									"OFFER_ADD_PICT_PROP" => "-",
									"OFFER_TREE_PROPS" => array(
											0 => "COLOR_REF",
									),
									"PAGER_BASE_LINK_ENABLE" => "N",
									"PAGER_DESC_NUMBERING" => "N",
									"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
									"PAGER_SHOW_ALL" => "N",
									"PAGER_SHOW_ALWAYS" => "N",
									"PAGER_TEMPLATE" => "",
									"PAGER_TITLE" => "Товары",
									"PAGE_ELEMENT_COUNT" => "500",
									"PARTIAL_PRODUCT_PROPERTIES" => "N",
									"PRICE_CODE" => array(
									),
									"PRICE_VAT_INCLUDE" => "Y",
									"PRODUCT_BLOCKS_ORDER" => "",
									"PRODUCT_DISPLAY_MODE" => "Y",
									"PRODUCT_ID_VARIABLE" => "id",
									"PRODUCT_PROPERTIES" => array(
									),
									"PRODUCT_PROPS_VARIABLE" => "prop",
									"PRODUCT_QUANTITY_VARIABLE" => "",
									"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
									"PRODUCT_SUBSCRIPTION" => "Y",
									"PROPERTY_CODE" => array(
											0 => "STOCK_PRODUCT",
											1 => "SELL_PROD",
											2 => "PRODUCT_OF_THE_DAY",
											3 => "NEWPRODUCT",
											4 => "",
									),
									"PROPERTY_CODE_MOBILE" => array(
									),
									"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
									"RCM_TYPE" => "personal",
									"SECTION_CODE" => "",
									"SECTION_ID" => "",
									"SECTION_ID_VARIABLE" => "SECTION_ID",
									"SECTION_URL" => "",
									"SECTION_USER_FIELDS" => array(
											0 => "",
											1 => "",
									),
									"SEF_MODE" => "N",
									"SET_BROWSER_TITLE" => "Y",
									"SET_LAST_MODIFIED" => "N",
									"SET_META_DESCRIPTION" => "Y",
									"SET_META_KEYWORDS" => "Y",
									"SET_STATUS_404" => "N",
									"SET_TITLE" => "Y",
									"SHOW_404" => "N",
									"SHOW_ALL_WO_SECTION" => "Y",
									"SHOW_CLOSE_POPUP" => "N",
									"SHOW_DISCOUNT_PERCENT" => "Y",
									"SHOW_FROM_SECTION" => "N",
									"SHOW_MAX_QUANTITY" => "N",
									"SHOW_OLD_PRICE" => "Y",
									"SHOW_PRICE_COUNT" => "1",
									"SHOW_SLIDER" => "Y",
									"SLIDER_INTERVAL" => "3000",
									"SLIDER_PROGRESS" => "N",
									"TEMPLATE_THEME" => "blue",
									"USE_ENHANCED_ECOMMERCE" => "Y",
									"USE_MAIN_ELEMENT_SECTION" => "N",
									"USE_PRICE_COUNT" => "Y",
									"USE_PRODUCT_QUANTITY" => "N",
									"COMPONENT_TEMPLATE" => "product_stock",
									"DISPLAY_COMPARE" => "N"
							),
							false
					);?>

	<? /*
	<!-- Бренды -->
	<section class="brands">
			<?$APPLICATION->IncludeComponent(
					"bitrix:news.list",
					"brand",
					Array(
							"DISPLAY_DATE" => "Y",
							"DISPLAY_NAME" => "Y",
							"DISPLAY_PICTURE" => "Y",
							"DISPLAY_PREVIEW_TEXT" => "Y",
							"AJAX_MODE" => "N",
							"IBLOCK_TYPE" => "content",
							"IBLOCK_ID" => "3",
							"NEWS_COUNT" => "20",
							"SORT_BY1" => "ACTIVE_FROM",
							"SORT_ORDER1" => "DESC",
							"SORT_BY2" => "SORT",
							"SORT_ORDER2" => "ASC",
							"FILTER_NAME" => "",
							"FIELD_CODE" => array("SLIDER_LINK"),
							"PROPERTY_CODE" => array("SLIDER_LINK"),
							"CHECK_DATES" => "Y",
							"DETAIL_URL" => "",
							"PREVIEW_TRUNCATE_LEN" => "",
							"ACTIVE_DATE_FORMAT" => "",
							"SET_TITLE" => "N",
							"SET_STATUS_404" => "N",
							"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
							"ADD_SECTIONS_CHAIN" => "N",
							"HIDE_LINK_WHEN_NO_DETAIL" => "N",
							"PARENT_SECTION" => "",
							"PARENT_SECTION_CODE" => "",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "36000000",
							"CACHE_NOTES" => "",
							"CACHE_FILTER" => "N",
							"CACHE_GROUPS" => "N",
							"DISPLAY_TOP_PAGER" => "N",
							"DISPLAY_BOTTOM_PAGER" => "N",
							"PAGER_TITLE" => "Слайдер",
							"PAGER_SHOW_ALWAYS" => "N",
							"PAGER_TEMPLATE" => "",
							"PAGER_DESC_NUMBERING" => "N",
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
							"PAGER_SHOW_ALL" => "N",
							"AJAX_OPTION_JUMP" => "N",
							"AJAX_OPTION_STYLE" => "Y",
							"AJAX_OPTION_HISTORY" => "N",
							"AJAX_OPTION_ADDITIONAL" => ""
					)
			);?>
	</section>
	*/ ?>

	<? /*
	<!-- Бестселлеры -->
	<section class="bestsellers">
			<?$APPLICATION->IncludeComponent(
		"bitrix:sale.bestsellers",
		"bestmanom",
		array(
			"LINE_ELEMENT_COUNT" => "3",
			"TEMPLATE_THEME" => "blue",
			"BY" => "QUANTITY",
			"PERIOD" => "0",
			"FILTER" => array(
				0 => "N",
				1 => "F",
			),
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "86400",
			"AJAX_MODE" => "N",
			"DETAIL_URL" => "",
			"BASKET_URL" => "/cart/",
			"ACTION_VARIABLE" => "action",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"ADD_PROPERTIES_TO_BASKET" => "Y",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PARTIAL_PRODUCT_PROPERTIES" => "N",
			"DISPLAY_COMPARE" => "N",
			"SHOW_OLD_PRICE" => "N",
			"SHOW_DISCOUNT_PERCENT" => "N",
			"PRICE_CODE" => array(
			),
			"SHOW_PRICE_COUNT" => "1",
			"PRODUCT_SUBSCRIPTION" => "N",
			"PRICE_VAT_INCLUDE" => "Y",
			"USE_PRODUCT_QUANTITY" => "N",
			"SHOW_NAME" => "Y",
			"SHOW_IMAGE" => "Y",
			"MESS_BTN_BUY" => "Купить",
			"MESS_BTN_DETAIL" => "Подробнее",
			"MESS_NOT_AVAILABLE" => "Нет в наличии",
			"MESS_BTN_SUBSCRIBE" => "Подписаться",
			"PAGE_ELEMENT_COUNT" => "30",
			"SHOW_PRODUCTS_3" => "Y",
			"PROPERTY_CODE_3" => array(
				0 => "MANUFACTURER",
				1 => "MATERIAL",
			),
			"CART_PROPERTIES_3" => array(
				0 => "CORNER",
			),
			"ADDITIONAL_PICT_PROP_3" => "MORE_PHOTO",
			"LABEL_PROP_3" => "SPECIALOFFER",
			"PROPERTY_CODE_4" => array(
				0 => "COLOR",
			),
			"CART_PROPERTIES_4" => "",
			"OFFER_TREE_PROPS_4" => array(
				0 => "-",
			),
			"HIDE_NOT_AVAILABLE" => "N",
			"CONVERT_CURRENCY" => "Y",
			"CURRENCY_ID" => "RUB",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"COMPONENT_TEMPLATE" => "bestmanom",
			"AJAX_OPTION_ADDITIONAL" => "",
			"SHOW_PRODUCTS_6" => "Y",
			"PROPERTY_CODE_6" => array(
				0 => "",
				1 => ",",
				2 => "",
			),
			"CART_PROPERTIES_6" => array(
				0 => "",
				1 => ",",
				2 => "",
			),
			"ADDITIONAL_PICT_PROP_6" => "MORE_PHOTO",
			"LABEL_PROP_6" => "-",
			"PROPERTY_CODE_7" => array(
				0 => "",
				1 => "",
			),
			"CART_PROPERTIES_7" => array(
				0 => "",
				1 => "",
			),
			"ADDITIONAL_PICT_PROP_7" => "MORE_PHOTO",
			"OFFER_TREE_PROPS_7" => array(
			)
		),
		false
	);?>

	</section>
	*/ ?>

	<!-- О магазине -->
	<section id="about-shops" class="about-shops">
		<div class="container">

			<?$APPLICATION->IncludeFile("/include_area/about_shop.php",Array(),Array("MODE"=>"html"));?>
		</div>
	</section>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
