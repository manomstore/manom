<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$start = 0;
?>
<?php foreach ($arResult['ITEMS'] as $item): ?>
    <?php
    if (!$item['canBuy']) {
        continue;
    }

    if ($start > 0) {
        break;
    }

    $start++;

    $class1 = $item['inFavoriteAndCompare'] ? '' : 'notActive';
    $class2 = $item['inFavoriteAndCompare'] ? 'alt-img' : 'notActive';

    [$price, $oldPrice] = $item['price']['PRICES'];
    ?>
    <div class="product-card border">
        <div class="product-card__img">
            <?php foreach ($item['images'] as $image): ?>
                <div class="product-card__slide">
                    <img
                            src="<?=$image['src']?>"
                            alt="<?=$item['name']?>"
                            data-product-list="product_day"
                            data-product-id="<?= $item['id'] ?>"
                            data-href="<?=$item['url']?>"
                    >
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
                <input
                        class="p-nav-top__checkbox"
                        type="checkbox"
                    <?=$item['inFavoriteAndCompare'] ? 'checked' : ''?>
                >
                <div
                        class="p-nav-top__favorite addToFavoriteList <?=$class1?>"
                        data-id='<?=$item['id']?>'
                        title="в избранное"
                ></div>
            </label>
            <div
                    class="p-nav-top__list addToCompareList <?=$class2?>"
                    data-id='<?=$item['id']?>'
            ></div>
        </div>
        <div class="p-nav-middle">
            <?php if ($item['sale']): ?>
                <div class="p-nav-middle__sale active">Распродажа</div>
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
               data-product-list="product_day"
               data-product-id="<?= $item['id'] ?>"
            ><?= $item['name'] ?></a>
        </h3>
        <div class="p-nav-bottom">
            <div class="p-nav-bottom__price">
                <?php if (
                    !empty((int)$oldPrice) &&
                    (int)$price !== (int)$oldPrice
                ): ?>
                    <div class="p-nav-bottom__price">
                        <?=number_format($price, 0, '', ' ')?>
                        <span> ₽</span>
                        <div class="p-nav-bottom__oldprice">
                            <?=number_format($oldPrice, 0, '', ' ')?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-nav-bottom__price">
                        <?=number_format($price, 0, '', ' ')?>
                        <span> ₽</span>
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
    <?
    GTM::addListsItems("product_day", [
        $item["id"]
    ]);
    ?>
<?php endforeach; ?>