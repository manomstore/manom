<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Manom\GTM;

$class1 = $arResult['PRODUCTS_COUNT'] !== 0 ? 'top-personal__cart--full' : '';
$class2 = $arResult['PRODUCTS_COUNT'] === 0 ? 'preview-shopcart--empty' : '';

$count = 0;
?>
<?php if ($arParams['AJAX_MIN_CART']) {
    $APPLICATION->RestartBuffer();
} ?>
<div class="top-personal__block">
    <a
        <?=$arResult['PRODUCTS_COUNT'] > 0 ? "href='/cart/'" : ''?>
            class="top-personal__link top-personal__cart <?=$class?>"
            id="mini_cart_header_counter"
    >
        <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/cart.svg" alt="Иконка корзины" width="20" height="16">
        <?php if ($arResult['PRODUCTS_COUNT'] !== 0): ?>
            <?=number_format($arResult['TOTAL_PRICE'], 0, '', ' ')?>
            &#8381;
            (
            <span class="top-count top-personal__count"><?=$arResult['PRODUCTS_COUNT']?></span>
            )
        <?php endif; ?>
    </a>
    <?php
    GTM::setProductsOnPage($arResult['PRODUCTS_ID']);
    ?>
    <div class="personal-preview preview-shopcart <?=$class2?>" id="mini_cart_header">
        <div class="personal-preview__wrapper">
            <div class="personal-preview__top">
                <h2 class="personal-preview__title">Корзина</h2>
                <button class="personal-preview__link js-clear-cart" type="button">Очистить</button>
            </div>
            <?php foreach ($arResult['ITEMS']['AnDelCanBuy'] as $item): ?>
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
                                <?php if (
                                    !empty((int)$item['oldSum']) &&
                                    (int)$item['sum'] !== (int)$item['oldSum']
                                ): ?>
                                    <span class="preview-prod-bottom__value preview-prod-bottom__value--new">
                                        <?=number_format($item['sum'], 0, '', ' ')?>
                                        &#8381;
                                    </span>
                                    <span class="preview-prod-bottom__value preview-prod-bottom__value--sale">
                                        <?=number_format($item['oldSum'], 0, '', ' ')?>
                                        &#8381;
                                    </span>
                                <?php else: ?>
                                    <span class="preview-prod-bottom__value">
                                        <?=number_format($item['sum'], 0, '', ' ')?>
                                        &#8381;
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button
                                class="preview-prod-bottom__del preview-prod-bottom__button-cart"
                                type="button"
                                aria-label="Удалить товар"
                                data-cart-item="<?=$item['ID']?>"
                                data-product-id="<?=$item['PRODUCT_ID']?>"
                            ></button>
                        </div>
                        <h3 class="preview-prod__name">
                            <a href="<?=$item['DETAIL_PAGE_URL']?>"
                               data-product-list="cart"
                               data-product-id="<?=$item['PRODUCT_ID']?>"
                            >
                                <?=$item['NAME']?> (<?=$item['QUANTITY']?>шт.)
                            </a>
                        </h3>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($count > 5): ?>
                <p style="text-align: left;padding: 5px 10px;">Товаров: <?=$count?></p>
            <?php endif; ?>
            <div class="preview-bottom">
                <span style="display:none" data-gtm-products='<?=GTM::getProductsOnPageJS()?>'></span>
                <a href="/cart/" class="preview-bottom__button">В корзину</a>
            </div>
        </div>
    </div>
</div>
<?php if ($arParams['AJAX_MIN_CART']) {
    die();
} ?>