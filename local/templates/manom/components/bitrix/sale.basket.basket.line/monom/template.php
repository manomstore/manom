<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$class1 = $arResult['NUM_PRODUCTS'] !== 0 ? 'top-personal__cart--full' : '';
$class2 = $arResult['NUM_PRODUCTS'] === 0 ? 'preview-shopcart--empty' : '';

$count = 0;
?>
<?php if ($arParams['AJAX_MIN_CART']) {
    $APPLICATION->RestartBuffer();
} ?>
<!-- Если в корзине что-то есть добавляется класс top-personal__cart--full -->
<div class="top-personal__block">
    <a
        <?=$arResult['NUM_PRODUCTS'] > 0 ? "href='/cart/'" : ''?>
            class="top-personal__link top-personal__cart <?=$class?>"
            id="mini_cart_header_counter"
    >
        <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/cart.svg" alt="Иконка корзины" width="22" height="16">
        <?php if ($arResult['NUM_PRODUCTS'] !== 0): ?>
            <?=str_replace('руб.', '', $arResult['TOTAL_PRICE'])?>
            &#8381;
            (
            <span class="top-count top-personal__count"><?=$arResult['NUM_PRODUCTS']?></span>
            )
        <?php endif; ?>
    </a>
    <?
    \Manom\GTM::setProductsOnPage($arResult["PRODUCT_IDS"]);
    ?>
    <div class="personal-preview preview-shopcart <?=$class2?>" id="mini_cart_header">
        <div class="personal-preview__wrapper">
            <div class="personal-preview__top">
                <h2 class="personal-preview__title">Корзина</h2>
                <button class="personal-preview__link js-clear-cart" type="button">Очистить</button>
            </div>
            <?php if ($arResult['NUM_PRODUCTS'] > 0): ?>
                <?php foreach ($arResult['CATEGORIES'] as $category): ?>
                    <?php foreach ($category as $item): ?>
                        <?php
                        $count++;
                        if ($count > 5) {
                            continue;
                        }
                        ?>
                        <div class="preview-prod" data-cart-item="<?=$item['ID']?>">
                            <div class="preview-prod__picture">
                                <img src="<?=$item['PIC']['src']?>" alt="<?=$item['NAME']?>">
                            </div>
                            <div class="preview-prod__descr">
                                <div class="preview-prod-bottom">
                                    <div class="preview-prod-bottom__price">
                                        <?php if ($item['EXIST_DISCOUNT']): ?>
                                            <span class="preview-prod-bottom__value preview-prod-bottom__value--new">
                                                <?=str_replace('руб.', '', $item['SUM'])?> ₽
                                            </span>
                                            <span class="preview-prod-bottom__value preview-prod-bottom__value--sale">
                                                <?=number_format(
                                                    $item['OLD_SUM_VALUE'],
                                                    0,
                                                    '.',
                                                    ' '
                                                )?> ₽
                                            </span>
                                        <?php else: ?>
                                            <span class="preview-prod-bottom__value">
                                                <?=str_replace('руб.', '', $item['SUM'])?> ₽
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <button
                                            class="preview-prod-bottom__del preview-prod-bottom__button-cart"
                                            type="button"
                                            aria-label="Удалить товар"
                                            data-cart-item="<?= $item['ID'] ?>"
                                            data-product-id="<?= $item['PRODUCT_ID'] ?>"
                                    ></button>
                                    <?php /*
                                    <label>
                                        <input class="preview-prod-bottom__checkbox" type="checkbox" checked>
                                        <span
                                            class="preview-prod-bottom__button preview-prod-bottom__button-cart"
                                            data-cart-item="<?=$item['ID']?>"
                                        ></span>
                                    </label>
                                    */ ?>
                                </div>
                                <h3 class="preview-prod__name">
                                    <a href="<?= $item['DETAIL_PAGE_URL'] ?>"
                                       data-product-list="cart"
                                       data-product-id="<?= $item['PRODUCT_ID'] ?>"
                                    >
                                        <?= $item['NAME'] ?> (<?= $item['QUANTITY'] ?>шт.)
                                    </a>
                                </h3>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php if ($count > 5): ?>
                    <p style="text-align: left;padding: 5px 10px;">Товаров: <?=$count?></p>
                <?php endif; ?>
                <div class="preview-bottom">
                    <?php /*
                    <div class="preview-bottom__price">
                        <?=str_replace('руб.', '', $arResult['TOTAL_PRICE'])?>
                        <span> ₽</span>
                    </div>
                    */ ?>
                    <span style="display:none" data-gtm-products='<?= \Manom\GTM::getProductsOnPageJS() ?>'></span>
                    <a href="/cart/" class="preview-bottom__button">В корзину</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if ($arParams['AJAX_MIN_CART']) {
    die();
} ?>