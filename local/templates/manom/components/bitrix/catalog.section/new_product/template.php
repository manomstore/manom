<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if (!empty($arResult['ITEMS'])): ?>
    <section class="new-product">
        <div class="container">
            <h2 class="promotion-h2">Новинки</h2>
            <div class="row new-product__block arrow-fix">
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <?php
                    if (!$item['canBuy']) {
                        continue;
                    }

                    $class1 = $item['inFavoriteAndCompare'] ? '' : 'notActive';
                    $class2 = $item['inFavoriteAndCompare'] ? 'alt-img' : 'notActive';
                    ?>
                    <div class="col-3">
                        <div class="product-card border <?=$item['canBuy'] ? 'enable' : 'disable'?>">
                            <div class="product-card__img">
                                <?php foreach ($item['images'] as $image): ?>
                                    <div class="product-card__slide">
                                        <img
                                                src="<?=$image['src']?>"
                                                alt="<?=$item['name']?>"
                                                data-product-list="new_items"
                                                data-product-id="<?=$item['id']?>"
                                                data-href="<?=$item['url']?>"
                                        >
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="p-nav-top">
                                <label>
                                    <input
                                            class="p-nav-top__checkbox"
                                            type="checkbox"
                                        <?=$item['inFavoriteAndCompare'] ? 'checked' : ''?>
                                    >
                                    <div
                                            class="p-nav-top__favorite addToFavoriteList <?=$class1?>"
                                            data-id='<?=$item['id']?>'
                                            title="Добавить в избранное"
                                    ></div>
                                </label>
                                <div   title="Добавить в сравнение"
                                        class="p-nav-top__list addToCompareList <?=$class2?>"
                                        data-id='<?=$item['id']?>'
                                ></div>
                            </div>
                            <div class="p-nav-middle">
                                <?php if (!$item['canBuy']): ?>
                                    <div class="p-nav-middle__sale active">
                                        <?=$arResult['ORIGINAL_PARAMETERS']['MESS_NOT_AVAILABLE']?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($item['sale']): ?>
                                    <div class="product-label product-label--sale active">Распродажа</div>
                                <?php endif; ?>
                                <?php if ($item['productOfTheDay']): ?>
                                    <div class="product-label product-label--day-offer active">Товар дня</div>
                                <?php endif; ?>
                                <?php if ($item['newProduct']): ?>
                                    <div class="product-label product-label--new active">Новинка</div>
                                <?php endif; ?>
                                <?php if ($item['productPreorder']): ?>
                                    <div class="product-label product-label--preorder active">Предзаказ</div>
                                <?php endif; ?>

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
                                <a href="<?= $item['url'] ?>"
                                   data-product-list="new_items"
                                   data-product-id="<?=$item['id']?>"
                                ><?= $item['name'] ?></a>
                            </h3>
                            <div class="p-nav-bottom">
                                <!-- <div class="p-nav-bottom__price"> -->
                                    <?php if ($item['showOldPrice']): ?>
                                        <div class="p-nav-bottom__price">
                                            <?=number_format($item['price'], 0, '', ' ')?>
                                            <span> ₽</span>
                                            <div class="p-nav-bottom__oldprice">
                                                <?=number_format($item['oldPrice'], 0, '', ' ')?>
                                                <span> ₽</span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-nav-bottom__price">
                                            <?=number_format($item['price'], 0, '', ' ')?>
                                            <span> ₽</span>
                                        </div>
                                    <?php endif; ?>
                                <!-- </div> -->
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
        <?
        \Manom\GTM::addListsItems("new_items", $arResult["ELEMENTS"]);
        ?>
    </section>
<?php endif; ?>