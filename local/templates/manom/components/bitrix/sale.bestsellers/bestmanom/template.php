<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

$templateData = array(
    'TEMPLATE_THEME' => $this->GetFolder() . '/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css',
    'TEMPLATE_CLASS' => 'bx_' . $arParams['TEMPLATE_THEME']
);
?>
<? if ($arResult['ITEMS']) { ?>
    <div class="container">
        <h2 class="bestsellers-h2">Бестселлер</h2>
        <div class="row bestsellers__block">
            <? foreach ($arResult['ITEMS'] as $key => $arItems) { ?>
                <div class="col-3">
                    <div class="product-card border">
                        <div class="product-card__img" onclick="location.href = '<?=$arItems['DETAIL_PAGE_URL']?>'">
                            <?foreach ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE'] as $i => $arPhoto) {?>
                                <?$file = CFile::ResizeImageGet($arPhoto, array('width'=>250, 'height'=>250), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                                <div class="product-card__slide bestseller">
                                    <img src="<?=$file['src']?>" alt="">
                                </div>
                            <?}?>
                        </div>
<!--                        <p class="p-label-top active">Товар дня</p>-->
                        <div class="p-nav-top">

                        </div>
                        <h3 class="p-name">
                            <a href="<?=$arItems['DETAIL_PAGE_URL']?>"><?=$arItems['NAME']?></a>
                        </h3>
                        <?$arAllPriceOffers = array();
                        foreach ($arItems['OFFERS'] as $arOffers) {
                            $arPrice = CCatalogProduct::GetOptimalPrice($arOffers['ID'], 1, $USER->GetUserGroupArray(), 'N');
                            if (!$arPrice || count($arPrice) <= 0)
                            {
                                if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($arItems['ID'], $key, $USER->GetUserGroupArray()))
                                {
                                    $quantity = $nearestQuantity;
                                    $arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
                                }
                            }
                            $arAllPriceOffers[] = $arPrice;
                        }?>

                        <div class="p-nav-bottom">
                            <div class="p-nav-bottom__price">
                                <?$summa = 0;?>
                                <?$offers = '';?>
                                <?foreach ($arAllPriceOffers as $p => $priceOffer) {
                                    if(!$summa) {
                                        $summa = $priceOffer['PRICE']['PRICE'];
                                        $offers = $priceOffer;
                                    }
                                    if ($priceOffer['DISCOUNT_PRICE']){
                                        if($summa > $priceOffer['DISCOUNT_PRICE']) {
                                            $summa = $priceOffer['DISCOUNT_PRICE'];
                                            $offers = $priceOffer;
                                        }
                                    }else{
                                        if($summa > $priceOffer['PRICE']['PRICE']) {
                                            $summa = $priceOffer['PRICE']['PRICE'];
                                            $offers = $priceOffer;?></div><?
                                        }
                                    }
                                }?>
                            </div>
                            <div class="p-nav-bottom__price" style="margin-right: auto;">от <?=$summa?><span> ₽</span></div>
                            <a class="btn-bestseller" href="<?=$arItems['DETAIL_PAGE_URL']?>">Подробнее</a>
                            <a class="btn-bestseller-mobile" href="<?=$arItems['DETAIL_PAGE_URL']?>" ><i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            <? } ?>
        </div>
    </div>
<? } ?>
<script type="text/javascript">
    BX.message({
        MESS_BTN_BUY: '<? echo('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('SB_TPL_MESS_BTN_BUY')); ?>',
        MESS_BTN_ADD_TO_BASKET: '<? echo('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('SB_TPL_MESS_BTN_ADD_TO_BASKET')); ?>',
        MESS_BTN_DETAIL: '<? echo('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('SB_TPL_MESS_BTN_DETAIL')); ?>',
        MESS_NOT_AVAILABLE: '<? echo('' != $arParams['MESS_BTN_DETAIL'] ? CUtil::JSEscape($arParams['MESS_BTN_DETAIL']) : GetMessageJS('SB_TPL_MESS_BTN_DETAIL')); ?>',
        BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('SB_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
        BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
        ADD_TO_BASKET_OK: '<? echo GetMessageJS('SB_ADD_TO_BASKET_OK'); ?>',
        TITLE_ERROR: '<? echo GetMessageJS('SB_CATALOG_TITLE_ERROR') ?>',
        TITLE_BASKET_PROPS: '<? echo GetMessageJS('SB_CATALOG_TITLE_BASKET_PROPS') ?>',
        TITLE_SUCCESSFUL: '<? echo GetMessageJS('SB_ADD_TO_BASKET_OK'); ?>',
        BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('SB_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
        BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('SB_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
        BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('SB_CATALOG_BTN_MESSAGE_CLOSE') ?>'
    });
</script>
