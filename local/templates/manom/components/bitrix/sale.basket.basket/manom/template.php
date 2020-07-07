<?php

use Manom\GTM;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?php if ($arParams['AJAX_CART']) {
    $APPLICATION->RestartBuffer();
} ?>
<?php if (count($arResult['GRID']['ROWS']) <= 0): ?>
    <script type="text/javascript">
      location.href = '/';
    </script>
<?php endif; ?>
<?php
GTM::setProductsOnPage($arResult['GRID']['ROWS'], true, 'PRODUCT_ID');
?>
<?php foreach ($arResult['GRID']['ROWS'] as $key => $row): ?>
    <article class="sci-product <?=$row['CAN_BUY'] === 'Y' ? '' : 'sci-product--off'?>" data-id="<?=$row['ID']?>">
        <div class="sci-product__wrapper">
            <div class="sci-product__picture">
                <img src="<?=$row['PIC']['src']?>" alt="<?=$row['NAME']?>">
            </div>
            <div class="sci-product__info">
                <div class="sci-product__sum-price">
                    <div class="product-price">
                        <?php if (
                            !empty((int)$row['oldSum']) &&
                            (int)$row['sum'] !== (int)$row['oldSum']
                        ): ?>
                            <span class="product-price__value product-price__value--new">
                                <?=number_format($row['sum'], 0, '', ' ')?> ₽
                            </span>
                            <span class="product-price__value product-price__value--sale">
                                <?=number_format($row['oldSum'], 0, '', ' ')?> ₽
                            </span>
                        <?php else: ?>
                            <span class="product-price__value">
                                <?=number_format($row['sum'], 0, '', ' ')?> ₽
                            </span>
                        <?php endif; ?>
                    </div>
                    <button
                            class="sci-product__delete sci-top__remove"
                            type="button" aria-label="Удалить товар"
                            data-id="<?=$row['ID']?>"
                            data-product-id="<?=$row['PRODUCT_ID']?>"
                            data-out-of-stock="<?=(int)in_array($row['PRODUCT_ID'], $arParams['productsOutOfStock'])?>"
                    >
                    </button>
                </div>
                <a class="sci-product__name-link"
                   data-product-list="cart"
                   data-product-id="<?= $row['PRODUCT_ID'] ?>"
                   href="<?=$row['DETAIL_PAGE_URL']?>"
                >
                    <h3 class="sci-product__name">
                        <?=$row['NAME']?>
                    </h3>
                </a>
                <?php if (!empty($row['MODEL'])): ?>
                    <p class="sci-product__model"><?=$row['MODEL']?></p>
                <?php endif; ?>
                <p class="sci-product__status">
                    <?php if(in_array($row['PRODUCT_ID'], $arParams['productsOutOfStock'])): ?>
                        <span style="color: red;">Товар закончился, удалите его, чтобы продолжить</span>
                    <?php elseif ($row['CAN_BUY'] === 'Y'): ?>
                        Есть в наличии
                    <?php else: ?>
                        Товар закончился
                    <?php endif; ?>
                </p>
                <div class="sci-product__counter-wrapper">
                    <div class="sci-product__counter">
                        <button
                                class="sci-top__count-down"
                                type="button"
                                aria-label="Уменьшить количество"
                                data-id="<?=$row['ID']?>" data-q="<?=$row['QUANTITY']?>"
                        >
                            <svg width="8" height="8">
                                <line x1="0" y1="4" x2="8" y2="4" stroke="#343434" stroke-width="1"/>
                            </svg>
                        </button>
                        <input
                                type="text"
                                readonly
                                value="<?=$row['QUANTITY']?>"
                                name="QUANTITY"
                        >
                        <button
                                class="sci-top__count-up"

                                type="button"
                                aria-label="Увеличить количество"
                                data-id="<?=$row['ID']?>" data-q="<?=$row['QUANTITY']?>"
                                <?=in_array($row['PRODUCT_ID'], $arParams['productsOutOfStock'])?'disabled':''?>
                        >
                            <svg width="8" height="8">
                                <line x1="0" y1="4" x2="8" y2="4" stroke="#343434" stroke-width="1"/>
                                <line x1="4" y1="0" x2="4" y2="8" stroke="#343434" stroke-width="1"/>
                            </svg>
                        </button>
                    </div>
                    <span class="sci-product__price">
                        <?=number_format($row['price'], 0, '', ' ')?> ₽
                    </span>
                </div>
            </div>
        </div>
        <?php if (!empty($row['ACCESSORIES']) || !empty($row['ADDITIONAL_SERVICES'])): ?>
            <div class="sci-add">
                <?php if (!empty($row['ACCESSORIES'])): ?>
                    <div class="sci-add__block">
                        <h2 class="sci-add__title">Рекомендуем добавить в заказ</h2>
                        <!-- При нажатии добавлять/удалять класс sci-add__button-hide--on, чтобы перевернуть стрелку -->
                        <button class="sci-add__button-hide" type="button" aria-label="Скрыть данные"></button>
                        <div class="sci-add__products">
                            <?php
                            GTM::setProductsOnPage($row['ACCESSORIES'], true);
                            ?>
                            <?php foreach ($row['ACCESSORIES'] as $item): ?>
                                <article class="sci-add__prod" data-id="<?=$item['id']?>">
                                    <div class="sci-add__picture">
                                        <img src="<?=$item['img']?>" alt="<?=$item['name']?>">
                                    </div>
                                    <div class="sci-add__prices">
                                        <div class="sci-add__price">
                                            <span>
                                                <?=number_format(
                                                    $item['prices']['price'],
                                                    0,
                                                    '',
                                                    ' '
                                                )?>
                                            </span>
                                            ₽
                                        </div>
                                    </div>
                                    <a class="sci-add__name-link"
                                       data-product-list="recommend"
                                       data-product-id="<?= $item['id'] ?>"
                                       href="<?= $item['url'] ?>"
                                    >
                                        <h3 class="sci-add__name">
                                            <?= $item['name'] ?>
                                        </h3>
                                    </a>
                                    <button
                                            class="sci-add__button addToCartBtn addToCartBtn_inCart"
                                            data-id="<?=$item['id']?>"
                                            type="button"
                                    >
                                        Добавить
                                    </button>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($row['ADDITIONAL_SERVICES'])): ?>
                    <div class="sci-add__services sci-add__block">
                        <h2 class="sci-add__title">Не забудьте еще</h2>
                        <button class="sci-add__button-hide" type="button" aria-label="Скрыть данные"></button>
                        <div class="sci-add__products">
                            <?php
                            GTM::setProductsOnPage($row['ADDITIONAL_SERVICES'], true);
                            ?>
                            <?php foreach ($row['ADDITIONAL_SERVICES'] as $item): ?>
                                <div class="sci-add__prod">
                                    <div class="sci-add__picture">
                                        <img src="<?=$item['img']?>" alt="<?=$item['name']?>">
                                    </div>
                                    <div class="sci-add__prices">
                                        <div class="sci-add__price">
                                            <span>
                                                <?=number_format(
                                                    $item['prices']['price'],
                                                    0,
                                                    '',
                                                    ' '
                                                )?>
                                            </span>
                                            ₽
                                        </div>
                                    </div>
                                    <a href="<?=$item['url']?>"
                                       data-product-list="recommend"
                                       data-product-id="<?= $item['id'] ?>"
                                    >
                                        <h3 class="sci-add__name">
                                            <?=$item['name']?>
                                        </h3>
                                    </a>
                                    <button
                                            class="sci-add__button addToCartBtn addToCartBtn_inCart"
                                            data-id="<?=$item['id']?>"
                                            type="button"
                                    >
                                        Добавить
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </article>
<?php endforeach; ?>
<?php if (!empty($arResult['GRID']['ROWS'])): ?>
    <button class="button-del button-del--bottom js-basket-clear" type="button">Очистить корзину</button>
<?php endif; ?>
<?php if ($arParams['AJAX_CART']) {
    die();
} ?>
<?php if ($arResult['MAIN_CART']) : ?>
    <script>
        $(function () {
            window.gtmActions.initCommonData(<?=GTM::getDataJS('cart')?>);
        });
    </script>
<?php endif; ?>