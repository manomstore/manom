<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if ($arResult['ITEMS']): ?>
    <section class="additionally">
        <div class="container">
            <h2 class="additionally-h2">Вас может заинтересовать</h2>
            <div class="additionally__block row">
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <?php
                    $class1 = $item['inFavoriteAndCompare'] ? '' : 'notActive';
                    $class2 = $item['inFavoriteAndCompare'] ? 'alt-img' : 'notActive';
                    ?>
                    <div class="col-3">
                        <div class="product-card border">
                            <div class="product-card__img">
                                <?php foreach ($item['images'] as $image): ?>
                                    <div class="product-card__slide">
                                        <a href="<?=$item['url']?>">
                                            <img
                                                    src="<?=$image['src']?>"
                                                    alt="<?=$item['name']?>"
                                            >
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <p class="p-label-top active">
                                <?php if ($item['productOfTheDay']): ?>
                                    Товар дня
                                <?php endif; ?>
                            </p>
                            <div class="p-nav-top">
                                <label>
                                    <input class="p-nav-top__checkbox" type="checkbox" <?=checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'checked' : '';?>>
                                    <div class="p-nav-top__favorite addToFavoriteList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'notActive' : '';?>" data-id='<?=$arPrice['PRODUCT_ID']?>' title="в избранное"></div>
                                </label>
                                <div class="p-nav-top__list addToCompareList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_COMPARE_ID') ? 'notActive' : 'alt-img';?>" data-id='<?=$arPrice['PRODUCT_ID']?>'></div>
                            </div>
                            <!--  -->
                            <div class="p-nav-middle">


                                <?php /*
                                <div class="p-nav-middle__rating">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <?php if ($i >= $item['rating']['rating']): ?>
                                            <span> ★</span>
                                        <?php else: ?>
                                            ★
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="p-nav-middle__comments">
                                    <span><?=$item['rating']['count']?></span>
                                </div>
                                */ ?>
                            </div>
                            <h3 class="p-name">
                                <a href="<?=$item['url']?>"><?=$item['name']?></a>
                            </h3>
                            <div class="p-nav-bottom">
                                <div class="p-nav-bottom__price">
                                    <?=number_format($item['price'], 0, '', ' ')?>
                                    <span> ₽</span>
                                    <?php if (
                                        !empty((int)$item['oldPrice']) &&
                                        (int)$item['price'] !== (int)$item['oldPrice']
                                    ): ?>
                                        <div class="p-nav-bottom__oldprice">
                                            <?=number_format($item['oldPrice'], 0, '', ' ')?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div
                                        class="p-nav-bottom__shopcart <?=$item['canBuy'] ? 'addToCartBtn' : ''?>"
                                        data-id='<?=$item['productId']?>'
                                    <?=$item['canBuy'] ? 'enable' : 'disable'?>
                                ></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>