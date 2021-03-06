<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<section class="compare container">
    <div class="compare__main">
        <?php /*
        <aside class="personal__aside">
            <h1 class="personal__title">Личный кабинет</h1>
            <a href="personal.html" id="personal-nav__item1" class="personal-nav__item personal-nav__name" data-id="pb-info">
                Мои настройки
            </a>
            <p class="personal-nav__name">Покупки:</p>
            <a href="personal.html" id="personal-nav__item2" class="personal-nav__item" data-id="pb-info">История
                покупок
            </a>
            <a href="personal.html" id="personal-nav__item3" class="personal-nav__item" data-id="pb-favour">Товары в
                избранном
            </a>
            <a href="compare.html" id="personal-nav__item4" class="personal-nav__item">Сравнение товаров</a>
            <p class="personal-nav__name">Моя активность:</p>
            <a href="personal.html" id="personal-nav__item4" class="personal-nav__item" data-id="pb-comments">Мои отзывы
            </a>
        </aside>
        */ ?>
        <?
        \Manom\GTM::setProductsOnPage($arResult['ITEMS'], true);
        ?>
        <?php if (empty($arResult['ITEMS'])): ?>
            <p class="notetext">Нет товаров для сравнения</p>
        <?php else: ?>
            <?php /*
            <h2 class="compare-h2">Сравнение товаров</h2>
            */ ?>

            <div class="compare__wrap">
                <h2 class="compare-h2">Сравнение товаров</h2>
                <div class="row compare__block">
                    <?php foreach ($arResult['ITEMS'] as $item): ?>
                        <?php
                        $class1 = $item['inFavoriteAndCompare'] ? '' : 'notActive';
                        $class2 = $item['inFavoriteAndCompare'] ? 'alt-img' : 'notActive';
                        ?>
                        <div class="col4 compare-page-item" data-id="<?=$item['productId']?>">
                            <div class="product-card border <?=$item['canBuy'] ? 'enable' : 'disable'?>">
                                <div class="product-card__img">
                                    <?php foreach ($item['images'] as $image): ?>
                                        <div class="product-card__slide">
                                            <img src="<?=$image['src']?>" alt="<?=$item['name']?>">
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
                                </div>
                                <div class="p-nav-middle">
                                    <?php if ($item['productPreorder']): ?>
                                        <div class="product-label product-label--preorder active">Предзаказ</div>
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
                                    <a
                                            data-product-list="compare"
                                            data-product-id="<?= $item['id'] ?>"
                                            href="<?=$item['url']?>"
                                    ><?=$item['name']?></a>
                                </h3>
                                <div class="p-nav-bottom">
                                    <div class="p-nav-bottom__price">
                                        <?=number_format($item['price'], 0, '', ' ')?>
                                        <span> ₽</span>
                                        <?php if ($item['showOldPrice']): ?>
                                            <div class="p-nav-bottom__oldprice">
                                                <?=number_format($item['oldPrice'], 0, '', ' ')?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <? if (!$item["productPreorder"]): ?>
                                        <div
                                                class="p-nav-bottom__shopcart <?= $item['canBuy'] ? 'addToCartBtn' : '' ?>"
                                                data-id='<?= $item['productId'] ?>'
                                            <?= $item['canBuy'] ? 'enable' : 'disable' ?>
                                        ></div>
                                    <? endif; ?>
                                </div>
                                <div class="product-content">
                                    <div class="p-cart-properties cb-line-properties">
                                        <?php foreach ($item['properties'] as $property): ?>
                                            <p>
                                                <span class="p-cart-properties__name"><?=$property['NAME']?></span>
                                                <span class="p-cart-properties__value bgreen"><?=$property['DISPLAY_VALUE']?></span>
                                            </p>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <span class="compare__basket hidden-remove" style="display:none;"></span>
                                <div
                                        class="compare__basket addToCompareList <?=$class1?>"
                                        data-id='<?= $item['productId'] ?>'
                                >
                                    Удалить из сравнения
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<script>
    $(function () {
        window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("other")?>);
    });
</script>