<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
    <?$start = 0;?>
    <?foreach ($arResult['ITEMS'] as $key => $arItems) {?>
        <?$arCanBuy = CCatalogSKU::IsExistOffers($arItems['ID'], $arResult['IBLOCK_ID']);?>
        <?
        $arPrice = CCatalogProduct::GetOptimalPrice($arItems['ID'], 1, $USER->GetUserGroupArray(), 'N');
        if (!$arPrice || count($arPrice) <= 0)
        {
            if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($arItems['ID'], $key, $USER->GetUserGroupArray()))
            {
                $quantity = $nearestQuantity;
                $arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
            }
        }
        ?>
        <?if ($arItems['PROPERTIES']['PRODUCT_DAY']['VALUE'] == 'YES') {?>
            <?$start++?>
        <?}?>
        <?if ($arItems['PROPERTIES']['PRODUCT_DAY']['VALUE'] == 'YES' and $arItems['CATALOG_QUANTITY'] > 0) {?>
            <div class="product-card border">
                <div class="product-card__img">
                    <?foreach ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE'] as $val => $img ) {?>
                        <?if ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE']) {
                            $file = CFile::ResizeImageGet($img, array('width'=>200, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        }?>
                        <div class="product-card__slide">
                            <img src="<?=$file['src']?>" alt="" onclick="location.href = '<?=$arItems['~DETAIL_PAGE_URL']?>'">
                        </div>
                    <?}?>
                </div>
                <p class="p-label-top active">
                    <?if ($arItems['PROPERTIES']['PRODUCT_DAY']['VALUE'] == 'YES') {?>
                        Товар дня
                    <?}?>
                </p>
                <div class="p-nav-top">
                    <label>
                        <input class="p-nav-top__checkbox" type="checkbox" <?=checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'checked' : '';?>>
                        <div class="p-nav-top__favorite addToFavoriteList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'notActive' : '';?>" data-id='<?=$arPrice['PRODUCT_ID']?>' title="в избранное"></div>
                    </label>
                    <div class="p-nav-top__list addToCompareList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_COMPARE_ID') ? 'notActive' : 'alt-img';?>" data-id='<?=$arPrice['PRODUCT_ID']?>'></div>
                </div>
                <div class="p-nav-middle">
                    <?if ($arPrice['RESULT_PRICE']['DISCOUNT'] > 0){?>
                        <div class="p-nav-middle__sale active">
                            Распродажа
                        </div>
                    <?}?>
<!--                    <div class="p-nav-middle__rating">-->
<!--                        --><?//for ($i=0; $i < 5; $i++) {
//                            if ($i >= $arResult['REVIEW'][$arItems['ID']]['rating']) {
//                                ?><!-- <span> ★ </span> --><?//
//                            }else{
//                                ?><!-- ★ --><?//
//                            }
//                        }?><!--</div>-->
<!--                    <div class="p-nav-middle__comments"><span>--><?//=$arResult['REVIEW'][$arItems['ID']]['count']?><!--</span></div>-->
                </div>
                <h3 class="p-name">
                    <a href="<?=$arItems['~DETAIL_PAGE_URL']?>"><?=$arItems['NAME']?></a>
                </h3>
                <div class="p-nav-bottom">
                    <div class="p-nav-bottom__price">
                        <?if (!$arItems['OFFERS']){?>
                            <?if ($arPrice['RESULT_PRICE']['DISCOUNT'] > 0){?>
                                <div class="p-nav-bottom__price">
                                    <?=$arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];?>
                                    <span> ₽</span>
                                    <div class="p-nav-bottom__oldprice"><?=$arPrice['RESULT_PRICE']['BASE_PRICE']?></div>
                                </div>
                            <?}else{?>
                                <div class="p-nav-bottom__price">
                                    <?=$arPrice['RESULT_PRICE']['BASE_PRICE'];?>
                                    <span> ₽</span>
                                </div>
                            <?}?>
                        <?}else{?>
                            <?if ($arPrice['RESULT_PRICE']['DISCOUNT_PRICE'] != $arPrice['RESULT_PRICE']['BASE_PRICE']){?>
                                <div class="p-nav-bottom__price">
                                    <?=$arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];?>
                                    <span> ₽</span>
                                    <div class="p-nav-bottom__oldprice"><?=$arPrice['RESULT_PRICE']['BASE_PRICE']?> руб.</div>
                                </div>
                            <?}else{?>
                                <div class="p-nav-bottom__price">
                                    <?=$arPrice['RESULT_PRICE']['BASE_PRICE'];?>
                                    <span> ₽</span>
                                </div>
                            <?}?>
                        <?}?>
                    </div>
                    <div class="p-nav-bottom__shopcart <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : ''?>" data-id='<?=$arPrice['PRODUCT_ID']?>' <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>></div>
                </div>
            </div>
        <?if ($start >= 1){
            break;
        }?>
    <?}?>
<?}?>