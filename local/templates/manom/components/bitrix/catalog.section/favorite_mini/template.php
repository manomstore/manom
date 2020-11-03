<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$count = 0;
?>
<?php if ($arParams['AJAX']) {
    $APPLICATION->RestartBuffer();
} ?>
<div class="top-personal__block">
    <a
            <?=count($arResult['ITEMS']) > 0 ? "href='/user/favorite/'" : ''?>
            class="top-personal__link"
            title="Добавить в избранное"
            id="mini_favorite_header_counter"
    >
        <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/heart.svg" alt="Иконка избранного" width="17" height="15">
        <?php if (count($arResult['ITEMS']) !== 0): ?>
            <span class="top-count"><?=count($arResult['ITEMS'])?></span>
        <?php endif; ?>
    </a>
    <div class="personal-preview" id="mini_favorite_header">
        <div class="personal-preview__wrapper">
            <div class="personal-preview__top">
                <h2 class="personal-preview__title">Избранное</h2>
                <button class="personal-preview__link js-clear-favorite" type="button">Очистить</button>
            </div>
            <?
            \Manom\GTM::setProductsOnPage($arResult['ITEMS'], true, "ID");
            ?>
            <?php if (!empty($arResult['ITEMS'])): ?>
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <?php
                    $count++;
                    if ($count > 5) {
                        break;
                    }
                    ?>
                    <div class="preview-prod" data-cart-item="<?=$item['ID']?>">
                        <div class="preview-prod__picture">
                            <img src="<?=$item['image']['src']?>" alt="<?=$item['NAME']?>">
                        </div>
                        <div class="preview-prod__descr">
                            <div class="preview-prod-bottom">
                                <div class="preview-prod-bottom__price">
                                    <?php if (!empty((int)$item['oldPrice']) && (int)$item['price'] !== (int)$item['oldPrice']): ?>
                                        <span class="preview-prod-bottom__value preview-prod-bottom__value--new">
                                            <?=number_format($item['price'], 0, '.', ' ')?> ₽
                                        </span>
                                        <span class="preview-prod-bottom__value preview-prod-bottom__value--sale">
                                            <?=number_format($item['oldPrice'], 0, '.', ' ')?> ₽
                                        </span>
                                    <?php else: ?>
                                        <span class="preview-prod-bottom__value">
                                            <?=number_format($item['price'], 0, '.', ' ')?> ₽
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <button
                                        class="preview-prod-bottom__del preview-prod-bottom__button-favorite"
                                        type="button"
                                        aria-label="Удалить товар"
                                        data-cart-item="<?=$item['ID']?>"
                                ></button>
                                <?php /*
                                <label>
                                    <input class="preview-prod-bottom__checkbox" type="checkbox" checked>
                                    <span
                                            class="preview-prod-bottom__button preview-prod-bottom__button-favorite"
                                            data-cart-item="<?=$value['ID']?>"
                                    ></span>
                                </label>
                                */ ?>
                            </div>
                            <h3 class="preview-prod__name">
                                <a href="<?= $item['DETAIL_PAGE_URL'] ?>"
                                   data-product-list="favorite"
                                   data-product-id="<?= $item['ID'] ?>"
                                >
                                    <?= $item['NAME'] ?>
                                </a>
                            </h3>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($arResult['ITEMS']) > 5): ?>
                    <p style="text-align: left;padding: 5px 10px;">Товаров: <?=count($arResult['ITEMS'])?></p>
                <?php endif; ?>
                <span style="display:none" data-gtm-products='<?= \Manom\GTM::getProductsOnPageJS() ?>'></span>
                <div class="preview-bottom">
                    <a href="/user/favorite/" class="preview-bottom__button preview-bottom__compare">В избранное</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if ($arParams['AJAX']) {
    ?>
    <script>
        $(function () {
            window.gtmActions.setProducts(<?=\Manom\GTM::getProductsOnPageJS()?>);
        });
    </script>
<?
    die();
} ?>