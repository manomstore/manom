<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
//$this->addExternalCss('/bitrix/css/main/bootstrap.css');

$templateLibrary = array('popup');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList
);
unset($currencyList, $templateLibrary);

$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_CPV_TPL_ELEMENT_DELETE_CONFIRM'));

$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_CPV_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_CPV_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_CPV_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_CPV_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_CPV_CATALOG_RELATIVE_QUANTITY_FEW');

$generalParams = array(
	'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
	'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
	'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
	'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
	'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
	'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
	'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
	'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
	'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
	'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
	'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
	'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
	'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
	'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
	'COMPARE_PATH' => $arParams['COMPARE_PATH'],
	'COMPARE_NAME' => $arParams['COMPARE_NAME'],
	'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
	'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
	'LABEL_POSITION_CLASS' => $labelPositionClass,
	'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
	'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
	'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
	'~BASKET_URL' => $arParams['~BASKET_URL'],
	'~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
	'~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
	'~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
	'~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
	'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
	'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
	'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
	'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
	'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE']
);

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));
$containerName = 'catalog-products-viewed-container';
?>
<?if ($arResult['ITEMS']) {?>
    <section class="last-view">
        <div class="container">
            <h2 class="last-view-h2" style="border-top: none;">Последние просмотренные товары:</h2>
            <div class="last-view__block">
                <? foreach ($arResult['ITEMS'] as $key => $arItems) { ?>
                    <? foreach ($arItems['OFFERS'] as $i => $arOffers) {
                        $detailPageUrl = "{$arItems['~DETAIL_PAGE_URL']}?offer={$arOffers["ID"]}";
                        ?>
                        <? $arCanBuy = CCatalogSKU::IsExistOffers($arItems['ID'], $arResult['IBLOCK_ID']); ?>
                        <?
                        $arPrice = CCatalogProduct::GetOptimalPrice($arItems['ID'], 1, $USER->GetUserGroupArray(), 'N');
                        if (!$arPrice || count($arPrice) <= 0) {
                            if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($arItems['ID'], $key,
                                $USER->GetUserGroupArray())) {
                                $quantity = $nearestQuantity;
                                $arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity,
                                    $USER->GetUserGroupArray(), $renewal);
                            }
                        }
                        ?>
                        <? if ($arOffers['PROPERTIES']['NEW_ITEM']['VALUE'] == 'YES' and $arOffers['CATALOG_QUANTITY'] > 0) { ?>
                            <div class="col-3">
                                <div class="product-card border <?= $arOffers['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable' ?>">
                                    <div class="product-card__img">
                                        <? foreach ($arOffers['PROPERTIES']['MORE_PHOTO']['VALUE'] as $val => $img) { ?>
                                            <? if ($arOffers['PROPERTIES']['MORE_PHOTO']['VALUE']) {
                                                $file = CFile::ResizeImageGet($img,
                                                    array('width' => 200, 'height' => 200),
                                                    BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                            } ?>
                                            <div class="product-card__slide">
                                                <img src="<?= $file['src'] ?>" alt=""
                                                     onclick="location.href = '<?= $detailPageUrl ?>'">
                                            </div>
                                        <? } ?>
                                    </div>
                                    <p class="p-label-top active">
                                        <? if ($arOffers['PROPERTIES']['PRODUCT_DAY']['VALUE'] == 'YES') { ?>
                                            Товар дня
                                        <? } ?>
                                    </p>
                                    <div class="p-nav-top">
                                        <label>
                                            <input class="p-nav-top__checkbox"
                                                   type="checkbox" <?= checkProdInFavoriteAndCompareList($arOffers['ID'],
                                                'UF_FAVORITE_ID') ? 'checked' : ''; ?>>
                                            <div class="p-nav-top__favorite addToFavoriteList <?= !checkProdInFavoriteAndCompareList($arOffers['ID'],
                                                'UF_FAVORITE_ID') ? 'notActive' : ''; ?>"
                                                 data-id='<?= $arOffers['ID'] ?>' title="Добавить в избранное"></div>
                                        </label>
                                        <div  title="Добавить в сравнение" class="p-nav-top__list addToCompareList <?= !checkProdInFavoriteAndCompareList($arOffers['ID'],
                                            'UF_COMPARE_ID') ? 'notActive' : 'alt-img'; ?>"
                                             data-id='<?= $arOffers['ID'] ?>'></div>
                                    </div>
                                    <div class="p-nav-middle">
                                        <? if ($arOffers['CATALOG_QUANTITY'] < 1 and $arCanBuy == 0) { ?>
                                            <div class="p-nav-middle__sale active">
                                                <?= $arResult['ORIGINAL_PARAMETERS']['MESS_NOT_AVAILABLE'] ?>
                                            </div>
                                        <? } ?>
                                        <? if ($arPrice['RESULT_PRICE']['DISCOUNT'] > 0) { ?>
                                            <div class="p-nav-middle__sale active">
                                                Распродажа
                                            </div>
                                        <?}?>
<!--                                        <div class="p-nav-middle__rating">-->
<!--                                            --><?//for ($i=0; $i < 5; $i++) {
//                                                if ($i >= $arResult['REVIEW'][$arItems['ID']]['rating']) {
//                                                    ?><!-- <span> ★ </span> --><?//
//                                                }else{
//                                                    ?><!-- ★ --><?//
//                                                }
//                                            }?><!--</div>-->
<!--                                        <div class="p-nav-middle__comments"><span>--><?//=$arResult['REVIEW'][$arItems['ID']]['count']?><!--</span></div>-->

                                    </div>
                                    <h3 class="p-name">
                                        <a href="<?= $detailPageUrl ?>"><?= $arOffers['NAME'] ?></a>
                                    </h3>
                                    <div class="p-nav-bottom">
                                        <!-- <div class="p-nav-bottom__price"> -->
                                            <? if (!$arItems['OFFERS']) { ?>
                                                <? if ($arPrice['RESULT_PRICE']['DISCOUNT'] > 0) { ?>
                                                    <div class="p-nav-bottom__price">
                                                        <?= $arPrice['RESULT_PRICE']['DISCOUNT_PRICE']; ?>
                                                        <span> ₽</span>
                                                        <div class="p-nav-bottom__oldprice"><?= $arPrice['RESULT_PRICE']['BASE_PRICE'] ?></div>
                                                        <span> ₽</span>
                                                    </div>
                                                <? } else { ?>
                                                    <div class="p-nav-bottom__price">
                                                        <?= $arPrice['RESULT_PRICE']['BASE_PRICE']; ?>
                                                        <span> ₽</span>
                                                    </div>
                                                <? } ?>
                                            <? } else { ?>
                                                <? if ($arPrice['RESULT_PRICE']['DISCOUNT_PRICE'] != $arPrice['RESULT_PRICE']['BASE_PRICE']) { ?>
                                                    <div class="p-nav-bottom__price">
                                                        <?= $arPrice['RESULT_PRICE']['DISCOUNT_PRICE']; ?>
                                                        <span> ₽</span>
                                                        <div class="p-nav-bottom__oldprice"><?= $arPrice['RESULT_PRICE']['BASE_PRICE'] ?>
                                                            руб.
                                                        </div>
                                                    </div>
                                                <? } else { ?>
                                                    <div class="p-nav-bottom__price">
                                                        <?= $arPrice['RESULT_PRICE']['BASE_PRICE']; ?>
                                                        <span> ₽</span>
                                                    </div>
                                                <? } ?>
                                            <? } ?>
                                        <!-- </div> -->
                                        <div class="p-nav-bottom__shopcart <?= $arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : '' ?>"
                                             data-id='<?= $arOffers['ID'] ?>' <?= $arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable' ?>></div>
                                    </div>
                                </div>
                            </div>
                        <? } ?>
                    <? } ?>
                <? } ?>
            </div>
        </div>
    </section>
<?}?>
<!--<pre>--><?//print_r($arOffers)?><!--</pre>-->

<script>
	BX.message({
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BASKET_URL: '<?=$arParams['BASKET_URL']?>',
		ADD_TO_BASKET_OK: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_CPV_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_CPV_CATALOG_TITLE_BASKET_PROPS')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('ADD_TO_BASKET_OK')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_CPV_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_MESSAGE_SEND_PROPS: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_SEND_PROPS')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_CPV_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_CPV_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_CPV_CATALOG_MESS_COMPARE_TITLE')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_CPV_CATALOG_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_CPV_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		SITE_ID: '<?=$component->getSiteId()?>'
	});
	var <?=$obName?> = new JCCatalogProductsViewedComponent({
		initiallyShowHeader: '<?=!empty($arResult['ITEM_ROWS'])?>',
		container: '<?=$containerName?>'
	});
</script>
