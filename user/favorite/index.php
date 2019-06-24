<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Профиль");
// if ($USER->IsAuthorized()){?>
    <div class="personal container" style="position: relative;">
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
        <div class="personal-main">
            <aside class="personal__aside">
                <h1 class="personal__title">Личный кабинет</h1>
                <?if ($USER->IsAuthorized()){?>
                  <a href="/user/profile.php" id="personal-nav__item1" class="personal-nav__item personal-nav__name" data-id="pb-info">Мои настройки</a>
                  <p class="personal-nav__name">Покупки:</p>
                  <a href="/user/history.php" id="personal-nav__item2" class="personal-nav__item">История покупок</a>
                  <a href="/user/favorite/" id="personal-nav__item4" class="personal-nav__item">Товары в избранном</a>
                  <a href="/catalog/compare/" id="personal-nav__item4" class="personal-nav__item">Сравнение товаров</a>
                  <p class="personal-nav__name">Моя активность:</p>
                  <p id="personal-nav__item4" class="personal-nav__item" data-id="pb-comments">Мои отзывы</p>
                <?}else{?>
                  <a href="/auth/" class="personal-nav__item">Авторизация</a>
                <?}?>
            </aside>

      			<?
      			$sort = 'propertysort_SALELEADER';
      			$order = 'ASC';
      			if ($_REQUEST['sort_by'] == 'price') {
      				$sort = 'CATALOG_PRICE_1';
      			} elseif ($_REQUEST['sort_by'] == 'pop') {
      				$sort = 'propertysort_SALELEADER';
      			} elseif ($_REQUEST['sort_by'] == 'name') {
      				$sort = 'NAME';
      			}
            global $customFilt;
            $favList = getProdListFavoritAndCompare('UF_FAVORITE_ID');
            $customFilt = array('ID' => $favList);
      			?>
            <?if(!$favList):?>
              <?if($_REQUEST['ajaxCal'] == 'Y') $GLOBALS['APPLICATION']->RestartBuffer();?>
                <section class="catalog-block">
                  <?if($_REQUEST['ajaxCal'] != 'Y'){?>  <h2 class="pb-info__title">Избранные товары:</h2><?}?>
                  <div class="addToFavoriteListOnFP_NOT_ITEM ">

                  </div>
                  <p class="notetext" style="padding: 20px 0;">В избранном отсутствуют товары</p>
                </section>
              <?if($_REQUEST['ajaxCal'] == 'Y'){ die();}?>
            <?else:?>
              <?if($_REQUEST['ajaxCal'] == 'Y') $GLOBALS['APPLICATION']->RestartBuffer();?>

                <?$APPLICATION->IncludeComponent(
                	"bitrix:catalog.section",
                	"favorite",
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
                		"BACKGROUND_IMAGE" => "-",
                		"BASKET_URL" => "/personal/basket.php",
                		"BRAND_PROPERTY" => "-",
                		"BROWSER_TITLE" => "-",
                		"CACHE_FILTER" => "N",
                		"CACHE_GROUPS" => "N",
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
                		"DISPLAY_BOTTOM_PAGER" => "N",
                		"DISPLAY_TOP_PAGER" => "N",
                    "ELEMENT_SORT_FIELD" => $sort,//'CATALOG_PRICE_1',//$arParams["ELEMENT_SORT_FIELD"],
                    "ELEMENT_SORT_ORDER" => $order,//'DESC',//$arParams["ELEMENT_SORT_ORDER"],
                		"ENLARGE_PRODUCT" => "PROP",
                		"ENLARGE_PROP" => "-",
                		"FILTER_NAME" => "customFilt",
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
                		"OFFERS_PROPERTY_CODE" => array(
                			0 => "COLOR_REF",
                			1 => "SIZES_SHOES",
                			2 => "SIZES_CLOTHES",
                			3 => "",
                			4 => "",
                		),
                		"OFFERS_LIMIT" => "5",
                		"OFFERS_SORT_FIELD" => "sort",
                		"OFFERS_SORT_FIELD2" => "id",
                		"OFFERS_SORT_ORDER" => "asc",
                		"OFFERS_SORT_ORDER2" => "desc",
                		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
                		"OFFER_TREE_PROPS" => array(
                			0 => "COLOR_REF",
                			1 => "SIZES_SHOES",
                			2 => "SIZES_CLOTHES",
                		),
                		"PAGER_BASE_LINK_ENABLE" => "N",
                		"PAGER_DESC_NUMBERING" => "N",
                		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                		"PAGER_SHOW_ALL" => "N",
                		"PAGER_SHOW_ALWAYS" => "N",
                		"PAGER_TEMPLATE" => "catalog_section",
                		"PAGER_TITLE" => "Товары",
                    "PAGE_ELEMENT_COUNT" => $_REQUEST['countOnPage'] ? $_REQUEST['countOnPage'] : 12,//3,//$arParams["PAGE_ELEMENT_COUNT"],
                		"PARTIAL_PRODUCT_PROPERTIES" => "N",
                		"PRICE_CODE" => array(
                			0 => "Розничная",
                		),
                		"PRICE_VAT_INCLUDE" => "Y",
                		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
                		"PRODUCT_DISPLAY_MODE" => "Y",
                		"PRODUCT_ID_VARIABLE" => "id",
                		"PRODUCT_PROPERTIES" => array(
                		),
                		"PRODUCT_PROPS_VARIABLE" => "prop",
                		"PRODUCT_QUANTITY_VARIABLE" => "",
                		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
                		"PRODUCT_SUBSCRIPTION" => "N",
                		"PROPERTY_CODE" => array(
                			0 => "",
                			1 => "NEWPRODUCT",
                			2 => "",
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
                		"SET_BROWSER_TITLE" => "N",
                		"SET_LAST_MODIFIED" => "N",
                		"SET_META_DESCRIPTION" => "N",
                		"SET_META_KEYWORDS" => "N",
                		"SET_STATUS_404" => "N",
                		"SET_TITLE" => "N",
                		"SHOW_404" => "N",
                		"SHOW_ALL_WO_SECTION" => "N",
                		"SHOW_CLOSE_POPUP" => "N",
                		"SHOW_DISCOUNT_PERCENT" => "N",
                		"SHOW_FROM_SECTION" => "N",
                		"SHOW_MAX_QUANTITY" => "N",
                		"SHOW_OLD_PRICE" => "N",
                		"SHOW_PRICE_COUNT" => "1",
                		"SHOW_SLIDER" => "Y",
                		"SLIDER_INTERVAL" => "3000",
                		"SLIDER_PROGRESS" => "N",
                		"TEMPLATE_THEME" => "blue",
                		"USE_ENHANCED_ECOMMERCE" => "Y",
                		"USE_MAIN_ELEMENT_SECTION" => "N",
                		"USE_PRICE_COUNT" => "N",
                		"USE_PRODUCT_QUANTITY" => "N",
                		"COMPONENT_TEMPLATE" => "favorite",
                		"DISPLAY_COMPARE" => "N"
                	),
                	false
                );?>
                <?if($_REQUEST['ajaxCal'] == 'Y') die();?>
            <?endif;?>
            <!-- Личный кабинет - основной блок -->
            <!-- Последние просмотренные товары -->
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.products.viewed",
                "cart-product",
                array(
                    "ACTION_VARIABLE" => "action_cpv",
                    "ADDITIONAL_PICT_PROP_2" => "MORE_PHOTO",
                    "ADDITIONAL_PICT_PROP_3" => "-",
                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                    "ADD_TO_BASKET_ACTION" => "BUY",
                    "BASKET_URL" => "/personal/basket.php",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "3600",
                    "CACHE_TYPE" => "A",
                    "CART_PROPERTIES_2" => array(
                        0 => "NEWPRODUCT",
                        1 => "NEWPRODUCT,SALELEADER",
                        2 => "",
                    ),
                    "CART_PROPERTIES_3" => array(
                        0 => "COLOR_REF",
                        1 => "SIZES_SHOES",
                        2 => "",
                    ),
                    "CONVERT_CURRENCY" => "Y",
                    "CURRENCY_ID" => "RUB",
                    "DATA_LAYER_NAME" => "dataLayer",
                    "DEPTH" => "",
                    "DISCOUNT_PERCENT_POSITION" => "top-right",
                    "ENLARGE_PRODUCT" => "STRICT",
                    "ENLARGE_PROP_2" => "NEWPRODUCT",
                    "HIDE_NOT_AVAILABLE" => "N",
                    "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                    "IBLOCK_ID" => "6",
                    "IBLOCK_MODE" => "single",
                    "IBLOCK_TYPE" => "catalog",
                    "LABEL_PROP_2" => array(
                        0 => "NEWPRODUCT",
                    ),
                    "LABEL_PROP_MOBILE_2" => "",
                    "LABEL_PROP_POSITION" => "top-left",
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                    "MESS_BTN_BUY" => "Купить",
                    "MESS_BTN_DETAIL" => "Подробнее",
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",
                    "MESS_RELATIVE_QUANTITY_FEW" => "мало",
                    "MESS_RELATIVE_QUANTITY_MANY" => "много",
                    "MESS_SHOW_MAX_QUANTITY" => "Наличие",
                    "OFFER_TREE_PROPS_3" => array(
                        0 => "COLOR_REF",
                        1 => "SIZES_SHOES",
                        2 => "SIZES_CLOTHES",
                    ),
                    "PAGE_ELEMENT_COUNT" => "8",
                    "PARTIAL_PRODUCT_PROPERTIES" => "N",
                    "PRICE_CODE" => array(
                        0 => "Цена продажи",
                    ),
                    "PRICE_VAT_INCLUDE" => "Y",
                    "PRODUCT_BLOCKS_ORDER" => "price,props,quantityLimit,sku,quantity,buttons,compare",
                    "PRODUCT_ID_VARIABLE" => "id",
                    "PRODUCT_PROPS_VARIABLE" => "prop",
                    "PRODUCT_QUANTITY_VARIABLE" => "",
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
                    "PRODUCT_SUBSCRIPTION" => "N",
                    "PROPERTY_CODE_2" => array(
                        0 => "NEWPRODUCT",
                        1 => "SALELEADER",
                        2 => "SPECIALOFFER",
                        3 => "MANUFACTURER",
                        4 => "MATERIAL",
                        5 => "COLOR",
                        6 => "SALELEADER,SPECIALOFFER,MATERIAL,COLOR,KEYWORDS,BRAND_REF",
                        7 => "",
                    ),
                    "PROPERTY_CODE_3" => array(
                        0 => "ARTNUMBER",
                        1 => "COLOR_REF",
                        2 => "SIZES_SHOES",
                        3 => "SIZES_CLOTHES",
                        4 => "",
                    ),
                    "PROPERTY_CODE_MOBILE_2" => "",
                    "RELATIVE_QUANTITY_FACTOR" => "5",
                    "SECTION_CODE" => "",
                    "SECTION_ELEMENT_CODE" => "",
                    "SECTION_ELEMENT_ID" => "",
                    "SECTION_ID" => "",
                    "SHOW_CLOSE_POPUP" => "N",
                    "SHOW_DISCOUNT_PERCENT" => "Y",
                    "SHOW_FROM_SECTION" => "N",
                    "SHOW_MAX_QUANTITY" => "N",
                    "SHOW_OLD_PRICE" => "Y",
                    "SHOW_PRICE_COUNT" => "1",
                    "SHOW_PRODUCTS_2" => "N",
                    "SHOW_SLIDER" => "Y",
                    "SLIDER_INTERVAL" => "3000",
                    "SLIDER_PROGRESS" => "Y",
                    "TEMPLATE_THEME" => "",
                    "USE_ENHANCED_ECOMMERCE" => "N",
                    "USE_PRICE_COUNT" => "N",
                    "USE_PRODUCT_QUANTITY" => "Y",
                    "COMPONENT_TEMPLATE" => "cart-product",
                    "DISPLAY_COMPARE" => "Y",
                    "PROPERTY_CODE_6" => array(
                        0 => "",
                        1 => "",
                    ),
                    "PROPERTY_CODE_MOBILE_6" => array(
                    ),
                    "CART_PROPERTIES_6" => array(
                        0 => "",
                        1 => "",
                    ),
                    "ADDITIONAL_PICT_PROP_6" => "-",
                    "LABEL_PROP_6" => array(
                    ),
                    "PROPERTY_CODE_7" => array(
                        0 => "MORE_PHOTO",
                        1 => "",
                    ),
                    "CART_PROPERTIES_7" => array(
                        0 => "",
                        1 => "",
                    ),
                    "ADDITIONAL_PICT_PROP_7" => "-",
                    "OFFER_TREE_PROPS_7" => array(
                    ),
                    "SHOW_PRODUCTS_6" => "N",
                    "LABEL_PROP_MOBILE_6" => "",
                    "COMPARE_PATH" => "",
                    "MESS_BTN_COMPARE" => "Сравнить",
                    "COMPARE_NAME" => "CATALOG_COMPARE_LIST"
                ),
                false
            );?>
        </div>

    </div>
<?//}else{
  //  LocalRedirect("/");
//}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
