<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

global $cartPrice;
$cartPrice = $arResult['TOTAL_PRICE'];
?>
<div id="cart_info_block" class="shopcart-sidebar__prods">
    <?php if ($arParams['AJAX_CART_INFO']) {
        $APPLICATION->RestartBuffer();
    } ?>
    <div class="shopcart-sidebar__prod-list js-shopcart-sidebar-product-list">
        <?php foreach ($arResult['ITEMS']['AnDelCanBuy'] as $item): ?>
            <div class="shopcart-sidebar__prod <?=$item['CAN_BUY'] === 'Y' ? '' : 'shopcart-sidebar__prod--stop'?>">
                <div class="sci-product__wrapper">
                    <div class="sci-product__picture">
                        <img src="<?=$item['PIC']['src']?>" alt="<?=$item['NAME']?>">
                    </div>
                    <div class="sci-product__info">
                        <div class="sci-product__info-wrapper">
                            <div class="sci-product__sum-price">
                                <div class="product-price">
                                    <?php if (
                                        !empty((int)$item['oldSum']) &&
                                        (int)$item['sum'] !== (int)$item['oldSum']
                                    ): ?>
                                        <span class="product-price__value product-price__value--new">
                                            <?=number_format($item['sum'], 0, '', ' ')?>
                                            &#8381;
                                        </span>
                                        <span class="product-price__value product-price__value--sale">
                                            <?=number_format($item['oldSum'], 0, '', ' ')?>
                                            &#8381;
                                        </span>
                                    <?php else: ?>
                                        <span class="product-price__value">
                                            <?=number_format($item['sum'], 0, '', ' ')?>
                                            &#8381;
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
    </div>
    <div class="hidden" style="display:none;">
        <span id="cart_count_prod"><?=$arResult['PRODUCTS_COUNT']?></span>
        <span id="cart_sum_prod">
            <?=number_format($arResult['TOTAL_PRICE'], 0, '', ' ')?>
            &#8381;
        </span>
    </div>
    <?php if ($arParams['AJAX_CART_INFO']) {
        die();
    } ?>
</div>