<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
global $glob_sectionInfo, $new_offer_filter;
?>
<section class="catalog-block" <?=$arParams['is_brand'] ? 'style="width:100%;"' : '' ;?>>
    <h2 class="cb-title"><?=$glob_sectionInfo['name']?></h2>
    <input class="filter-burger__checkbox" type="checkbox" id="filter-burger">
    <label class="filter-burger" for="filter-burger" title="Фильтр"></label>
    <div class="cb-nav">
        <div class="cb-nav-sort">
            <span class="cb-nav__text">Сортировать</span>
            <select required name="sort_by">
                <option selected value="pop">по популярности</option>
                <option value="price">по цене</option>
                <option value="name">по названию</option>
            </select>
        </div>
        <div class="cb-nav-count catTopCount">
            <?=$arResult["NAV_STRING"]?>
            <span class="cb-nav__text">Товаров на странице </span>
            <select name="countOnPage" required>
                <option value="3">3</option>
                <option value="6">6</option>
                <option value="12" selected>12</option>
                <option value="24">24</option>
                <option value="9999">все</option>
            </select>
        </div>
        <div class="cb-nav-style">
            Вид
            <div class="cb-nav-style__block">
                <label>
                    <input class="radio" type="radio" name="style" id="v-single">
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/v-single.svg" alt="" class="v-single">
                </label>
                <label>
                    <input class="radio" type="radio" name="style" id="v-block" checked>
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/v-block.svg" alt="" class="v-block">
                </label>
                <label>
                    <input class="radio" type="radio" name="style" id="v-line">
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/v-line.svg" alt="" class="v-line">
                </label>
            </div>

        </div>
    </div>
    <div id='PROPDS_BLOCK'>
        <?if($_REQUEST['ajaxCal'] == 'Y') $GLOBALS['APPLICATION']->RestartBuffer();?>
        <?//print_r($new_offer_filter);?>
        <div class="cb-single no-gutters"<?=$_REQUEST['styleBlock'] == 'v-single' ? 'style="display: flex;"' : '';?>>
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
                <div class="cb-single__item col-6">
                    <div class="product-card cb-single-card <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>">
                        <div class="product-card__img cb-single-card__img">
                            <?foreach ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE'] as $val => $img ) {?>
                                <?if ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE']) {
                                    $file = CFile::ResizeImageGet($img, array('width'=>350, 'height'=>350), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                }?>
                                <div class="product-card__slide cb-single-card__slide">
                                    <img src="<?=$file['src']?>" alt="" onclick="location.href = '<?=$arItems['DETAIL_PAGE_URL']?>'">
                                </div>
                            <?}?>
                        </div>
                        <p class="p-label-top active">
                            <?if ($arItems['PROPERTIES']['PRODUCT_OF_THE_DAY']['VALUE'] == 'Да') {?>
                                Товар дня
                            <?}?>
                        </p>
                        <div class="cb-single-nav-top">
                            <label>
                                <input class="p-nav-top__checkbox" type="checkbox" <?=checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'checked' : '';?>>
                                <div class="p-nav-top__favorite addToFavoriteList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'notActive' : '';?>" data-id='<?=$arPrice['PRODUCT_ID']?>' title="Добавить в избранное"></div>
                            </label>
                            <div  title="Добавить в сравнение" class="p-nav-top__list addToCompareList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_COMPARE_ID') ? 'notActive' : 'alt-img';?>" data-id='<?=$arPrice['PRODUCT_ID']?>'></div>
                        </div>
                        <div class="p-nav-middle">
                            <?if ($arItems['PROPERTIES']['SELL_PROD']['VALUE']){?>
                                <div class="p-nav-middle__sale active">
                                    <?=$arItems['PROPERTIES']['SELL_PROD']['NAME']?>
                                </div>
                            <?}?>
<!--                            <div class="p-nav-middle__rating">-->
<!--                                --><?//for ($i=0; $i < 5; $i++) {
//                                    if ($i >= $arResult['REVIEW'][$arItems['ID']]['rating']) {
//                                        ?><!-- <span> ★ </span> --><?//
//                                    }else{
//                                        ?><!-- ★ --><?//
//                                    }
//                                }?><!--</div>-->
<!--                            <div class="p-nav-middle__comments"><span>--><?//=$arResult['REVIEW'][$arItems['ID']]['count']?><!--</span></div>-->
                        </div>
                        <h3 class="p-name cb-single-name">
                            <a href="<?=$arItems['DETAIL_PAGE_URL']?>"><?=$arItems['NAME']?></a>
                        </h3>
                        <div class="p-cart-properties">
                            <?foreach ($arItems['DISPLAY_PROPERTIES'] as $g => $arProp ) {?>
                                <?if (!is_array($arProp['VALUE'])){?>
                                    <p>
                                        <span class="p-cart-properties__name"><?=$arProp['NAME']?></span>
                                        <span class="p-cart-properties__value"><?=$arProp['VALUE']?></span>
                                    </p>
                                <?}?>
                            <?}?>
                        </div>
                        <div class="p-nav-bottom">
                            <?if (!$arItems['OFFERS']){?>
                                <?if ($arItems['MIN_PRICE']['DISCOUNT_DIFF']){?>
                                    <div class="p-nav-bottom__price">
                                        <?=$arItems['MIN_PRICE']['DISCOUNT_VALUE'];?>
                                        <span> ₽</span>
                                        <div class="p-nav-bottom__oldprice"><?=$arItems['MIN_PRICE']['PRINT_VALUE_VAT']?></div>
                                    </div>
                                <?}else{?>
                                    <div class="p-nav-bottom__price">
                                        <?=$arItems['MIN_PRICE']['VALUE'];?>
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
                            <button class="p-nav-bottom__shopcart <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : ''?>" data-id='<?=$arPrice['PRODUCT_ID']?>' <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>></button>
                        </div>
                    </div>
                </div>
            <?}?>
        </div>
        <div class="cb-block no-gutters"<?=$_REQUEST['styleBlock'] == 'v-block' ? 'style="display: flex;"' : '';?>>
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
                <div class="cb-block__item col-3<?=$arParams['is_brand'] ? ' block__item__brand' : ''?>">
                    <div class="product-card <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>">
                        <div class="product-card__img">
                            <?foreach ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE'] as $val => $img ) {?>
                                <?if ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE']) {
                                    $file = CFile::ResizeImageGet($img, array('width'=>250, 'height'=>250), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                }?>
                                <div class="product-card__slide">
                                    <img src="<?=$file['src']?>" alt="" onclick="location.href = '<?=$arItems['DETAIL_PAGE_URL']?>'">
                                </div>
                            <?}?>
                        </div>
                        <p class="p-label-top active">
                            <?if ($arItems['PROPERTIES']['PRODUCT_OF_THE_DAY']['VALUE'] == 'Да') {?>
                                Товар дня
                            <?}?>
                        </p>
                        <div class="p-nav-top">
                            <label>
                                <input class="p-nav-top__checkbox" type="checkbox" <?=checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'checked' : '';?>>
                                <div class="p-nav-top__favorite addToFavoriteList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'notActive' : '';?>" data-id='<?=$arPrice['PRODUCT_ID']?>' title="Добавить в избранное"></div>
                            </label>
                            <div  title="Добавить в сравнение" class="p-nav-top__list addToCompareList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_COMPARE_ID') ? 'notActive' : 'alt-img';?>" data-id='<?=$arPrice['PRODUCT_ID']?>'></div>
                        </div>
                        <div class="p-nav-middle">
                            <?if ($arItems['CATALOG_QUANTITY'] < 1 and $arCanBuy == 0){?>
                                <div class="p-nav-middle__sale active">
                                    Нет в наличии
                                </div>
                            <?}?>
                            <?if ($arPrice['RESULT_PRICE']['DISCOUNT'] > 0){?>
                                <div class="p-nav-middle__sale active">
                                    Распродажа
                                </div>
                            <?}?>
<!--		                        <div class="p-nav-middle__rating">-->
<!--			                        --><?//for ($i=0; $i < 5; $i++) {
//				                        if ($i >= $arResult['REVIEW'][$arItems['ID']]['rating']) {
//					                        ?><!-- <span> ★ </span> --><?//
//				                        }else{
//					                        ?><!-- ★ --><?//
//				                        }
//			                        }?><!--</div>-->
<!--		                        <div class="p-nav-middle__comments">-->
<!--			                        <span>--><?//=$arResult['REVIEW'][$arItems['ID']]['count']?><!--</span>-->
<!--		                        </div>-->
                        </div>
                        <h3 class="p-name">
                            <a href="<?=$arItems['DETAIL_PAGE_URL']?>"><?=$arItems['NAME']?></a>
                        </h3>
                        <div class="p-nav-bottom">
                            <?if (!$arItems['OFFERS']){?>
                                <?if ($arItems['MIN_PRICE']['DISCOUNT_DIFF']){?>
                                    <div class="p-nav-bottom__price">
                                        <?=$arItems['MIN_PRICE']['DISCOUNT_VALUE'];?>
                                        <span> ₽</span>
                                        <div class="p-nav-bottom__oldprice"><?=$arItems['MIN_PRICE']['PRINT_VALUE_VAT']?></div>
                                    </div>
                                <?}else{?>
                                    <div class="p-nav-bottom__price">
                                        <?=$arItems['MIN_PRICE']['VALUE'];?>
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
                            <button class="p-nav-bottom__shopcart <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : ''?>" data-id='<?=$arPrice['PRODUCT_ID']?>' <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>></button>
                        </div>
                    </div>
                </div>
            <?}?>
        </div>
        <div class="cb-line no-gutters"<?=$_REQUEST['styleBlock'] == 'v-line' ? 'style="display: flex;"' : '';?>>
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
                <div class="cb-line__item col-12">
                    <div class="product-card cb-line-card <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>">  <!-- disable -->
                        <div class="product-card__img cb-line-card__img">
                            <?foreach ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE'] as $val => $img ) {?>
                                <?if ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE']) {
                                    $file = CFile::ResizeImageGet($img, array('width'=>170, 'height'=>170), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                }?>
                                <div class="product-card__slide">
                                    <img src="<?=$file['src']?>" alt="" onclick="location.href = '<?=$arItems['DETAIL_PAGE_URL']?>'">
                                </div>
                            <?}?>
                        </div>
                        <div class="cb-line-card__main">
                            <div class="p-nav-middle">
                                <?if ($arItems['PROPERTIES']['SELL_PROD']['VALUE']){?>
                                    <div class="p-nav-middle__sale active">
                                        <?=$arItems['PROPERTIES']['SELL_PROD']['NAME']?>
                                    </div>
                                <?}?>
                                <div class="p-nav-middle__rating cb-line-card__rating">
                                    <?for ($i=0; $i < 5; $i++) {
                                        if ($i >= $arResult['REVIEW'][$arItems['ID']]['rating']) {
                                            ?> <span> ★ </span> <?
                                        }else{
                                            ?> ★ <?
                                        }
                                    }?></div>
                                <div class="p-nav-middle__comments"><span><?=$arResult['REVIEW'][$arItems['ID']]['count']?></span></div>
                            </div>
                            <div class="cb-line-card__text">
                                <h3 class="p-name cb-line-name">
                                    <a href="<?=$arItems['DETAIL_PAGE_URL']?>"><?=$arItems['NAME']?></a>
                                </h3>
                                <div class="p-cart-properties cb-line-properties">
                                    <?foreach ($arItems['DISPLAY_PROPERTIES'] as $g => $arProp ) {?>
                                        <?if (!is_array($arProp['VALUE'])){?>
                                            <p>
                                                <span class="p-cart-properties__name"><?=$arProp['NAME']?></span>
                                                <span class="p-cart-properties__value"><?=$arProp['VALUE']?></span>
                                            </p>
                                        <?}?>
                                    <?}?>
                                </div>
                            </div>
                            <div class="cb-line-nav-top">
                                <?if ($arItems['CATALOG_QUANTITY'] < 1 and $arCanBuy == 0){?>
                                    <div class="cb-line-nav-top__available">
                                        <?=$arResult['ORIGINAL_PARAMETERS']['MESS_NOT_AVAILABLE']?>
                                    </div>
                                <?}?>
                                <label>
                                    <input class="cb-line-nav-top__checkbox" type="checkbox" <?=checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'checked' : '';?>>
                                    <div class="cb-line-nav-top__favorite addToFavoriteList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'notActive' : '';?>" data-id='<?=$arPrice['PRODUCT_ID']?>'>В&nbsp;избранное</div>
                                </label>
                                <div title="Добавить в сравнение" class="cb-line-nav-top__list addToCompareList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_COMPARE_ID') ? 'notActive' : 'alt-img';?>" data-id='<?=$arPrice['PRODUCT_ID']?>'>Сравнить</div>
                            </div>
                        </div>
                        <div class="p-nav-bottom cb-line-bottom">
                            <div class="p-nav-bottom">
                                <?if (!$arItems['OFFERS']){?>
                                    <?if ($arItems['MIN_PRICE']['DISCOUNT_DIFF']){?>
                                        <div class="p-nav-bottom__price">
                                            <?=$arItems['MIN_PRICE']['DISCOUNT_VALUE'];?>
                                            <span> ₽</span>
                                            <div class="p-nav-bottom__oldprice"><?=$arItems['MIN_PRICE']['PRINT_VALUE_VAT']?></div>
                                        </div>
                                    <?}else{?>
                                        <div class="p-nav-bottom__price">
                                            <?=$arItems['MIN_PRICE']['VALUE'];?>
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
                            <button class="cb-line-bottom__buy <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : ''?>" data-id='<?=$arPrice['PRODUCT_ID']?>' <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>>Купить<?//=$arResult['ORIGINAL_PARAMETERS']['MESS_BTN_BUY']?></button>
                        </div>
                    </div>
                </div>
            <?}?>
        </div>

        <!-- Пагинация -->
        <?=$arResult["NAV_STRING"]?>
        <?if($_REQUEST['ajaxCal'] == 'Y') die();?>
    </div>
    <!-- <div class="cb-nav-bottom">
        <div class="cb-nav-pagination">
            <div class="cb-nav-pagination__item active">1</div>
            <div class="cb-nav-pagination__item">2</div>
            <div class="cb-nav-pagination__item">3</div>
        </div>
        <div class="cb-nav-count">
            Товары <span class="cb-nav-count__current">1—12</span> из <span class="cb-nav-count__total">125</span>
        </div>
    </div> -->

</section>
