<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Профиль");
if ($USER->IsAuthorized()){?>
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
                  <p id="personal-nav__item1" class="personal-nav__item personal-nav__name" data-id="pb-info">Мои настройки</p>
                  <p class="personal-nav__name">Покупки:</p>
                  <a href="/user/history.php" id="personal-nav__item2" class="personal-nav__item">История покупок</a>
                  <a href="/user/favorite/" id="personal-nav__item4" class="personal-nav__item">Товары в избранном</a>
                  <a href="/catalog/compare/" id="personal-nav__item4" class="personal-nav__item">Сравнение товаров</a>
                  <p class="personal-nav__name">Моя активность:</p>
                  <a href="/user/review/add-list/" id="personal-nav__item4" class="personal-nav__item">Добавить отзыв товару</a>
                  <a href="/user/review/" id="personal-nav__item4" class="personal-nav__item">Мои отзывы</a>
                <?}else{?>
                  <a href="/auth/" class="personal-nav__item">Авторизация</a>
                <?}?>
            </aside>
            <main class="personal-block">
              <?
              global $customFilt;
              $prodID = getAllProdByReview();
              $customFilt = array('ID' => $prodID);
              ?>
              <?if(!$prodID):?>
                <section class="catalog-block">
                  <h2 class="pb-info__title">Мои отзывы:</h2>
                  <p style="padding: 20px 0;">Отсутствуют отзывы</p>
                </section>
              <?else:?>
                <?$APPLICATION->IncludeComponent(
                	"bitrix:catalog.section",
                	"product_review",
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
                		"ENLARGE_PRODUCT" => "PROP",
                		"ENLARGE_PROP" => "-",
                		"FILTER_NAME" => "customFilt",
                		"HIDE_NOT_AVAILABLE" => "N",
                		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
                		"IBLOCK_ID" => "6",
                		"IBLOCK_TYPE" => "catalog",
                		"INCLUDE_SUBSECTIONS" => "Y",
                		"LABEL_PROP" => "",
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
                		"PROPERTY_CODE_MOBILE" => "",
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
                		"SHOW_ALL_WO_SECTION" => "Y",
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
                		"COMPONENT_TEMPLATE" => "prod_for_review",
                		"DISPLAY_COMPARE" => "N",
                		"ELEMENT_SORT_FIELD" => "sort",
                		"ELEMENT_SORT_ORDER" => "asc",
                		"ELEMENT_SORT_FIELD2" => "id",
                		"ELEMENT_SORT_ORDER2" => "desc",
                		"PAGE_ELEMENT_COUNT" => "999",
                		"OFFERS_FIELD_CODE" => array(
                			0 => "",
                			1 => "",
                		),
                		"OFFERS_CART_PROPERTIES" => array(
                		)
                	),
                	false
                );?>
              <?endif;?>
            </main>
        </div>

    </div>
<?}else{
    LocalRedirect("/");
}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
