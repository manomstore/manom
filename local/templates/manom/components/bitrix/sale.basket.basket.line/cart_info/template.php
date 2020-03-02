<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
global $cartPrice;
$cartPrice = str_replace('руб.', '', $arResult['TOTAL_PRICE']);
?>
<div id="cart_info_block" class="shopcart-sidebar__prods">
    <?php if ($arParams['AJAX_CART_INFO']) {
        $APPLICATION->RestartBuffer();
    } ?>
    <?php if ($arResult['NUM_PRODUCTS'] > 0): ?>
        <div class="shopcart-sidebar__prod-list js-shopcart-sidebar-product-list">
            <?php foreach ($arResult['CATEGORIES'] as $category): ?>
                <?php foreach ($category as $item): ?>
                    <div class="shopcart-sidebar__prod <?=$item['CAN_BUY'] === 'Y' ? '' : 'shopcart-sidebar__prod--stop'?>">
                        <div class="sci-product__wrapper">
                            <div class="sci-product__picture">
                                <img src="<?=$item['PIC']['src']?>" alt="<?=$item['NAME']?>">
                            </div>
                            <div class="sci-product__info">
                                <div class="sci-product__info-wrapper">
                                    <div class="sci-product__sum-price">
                                        <div class="product-price">
                                            <?php if ($item['DISCOUNT_PRICE_PERCENT'] > 0): ?>
                                                <span class="product-price__value product-price__value--new">
                                                    <?=$item['SUM']?> ₽
                                                </span>
                                                <span class="product-price__value product-price__value--sale">
                                                    <?=$item['SUM_FULL_PRICE_FORMATTED']?> ₽
                                                </span>
                                            <?php else: ?>
                                                <span class="product-price__value">
                                                    <?=$item['SUM']?> ₽
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <h3 class="sci-product__name">
                                        <?=$item['NAME']?>
                                    </h3>
                                </div>
                                <div class="sci-product__count-block">
                                    <span class="sci-product__name">
                                        <?=$item['QUANTITY']?> шт.
                                    </span>
                                    <?php if ($item['CAN_BUY'] !== 'Y'): ?>
                                        <button
                                                class="sci-product__delete sci-top__remove"
                                                type="button"
                                                aria-label="Удалить товар"
                                                data-id="<?=$item['ID']?>"
                                        >
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($item['CAN_BUY'] !== 'Y'): ?>
                            <p class="shopcart-sidebar__error">Товар закончился, удалите его и оформите заказ</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="hidden" style="display:none;">
        <span id="cart_count_prod"><?=$arResult['NUM_PRODUCTS']?></span>
        <span id="cart_sum_prod"><?=str_replace('руб.', '', $arResult['TOTAL_PRICE'])?></span>
    </div>
    <?php if ($arParams['AJAX_CART_INFO']) {
        die();
    } ?>
</div>