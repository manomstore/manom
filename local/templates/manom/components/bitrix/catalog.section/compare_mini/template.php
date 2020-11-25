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
<div class="top-personal__block top-personal__block--compare">
    <a
            <?=count($arResult['ITEMS']) > 0 ? "href='/catalog/compare/'" : ''?>
            class="top-personal__link"
            title="Избранное"
            id="mini_compare_header_counter"
    >
        <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/compare.svg" alt="Иконка сравнения" width="16" height="15">
        <?php if (count($arResult['ITEMS']) !== 0): ?>
            <span class="top-count"><?=count($arResult['ITEMS'])?></span>
        <?php endif; ?>
    </a>
    <div class="personal-preview" id="mini_compare_header">
        <div class="personal-preview__wrapper">
            <div class="personal-preview__top">
                <h2 class="personal-preview__title">Сравнение</h2>
                <button class="personal-preview__link js-clear-compare" type="button">Очистить</button>
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
                                    <?php if ($item['showOldPrice']): ?>
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
                                        class="preview-prod-bottom__del preview-prod-bottom__button-compare"
                                        type="button"
                                        aria-label="Удалить товар"
                                        data-cart-item="<?=$item['ID']?>"
                                ></button>
                                <?php /*
                                <label>
                                    <input class="preview-prod-bottom__checkbox" type="checkbox" checked>
                                    <span
                                        class="preview-prod-bottom__button preview-prod-bottom__button-compare"
                                        data-cart-item="<?=$item['ID']?>"
                                    ></span>
                                </label>
                                */ ?>
                            </div>
                            <h3 class="preview-prod__name">
                                <a href="<?= $item['DETAIL_PAGE_URL'] ?>"
                                   data-product-list="compare"
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
                    <a href="/catalog/compare/" class="preview-bottom__button preview-bottom__compare">В сравнение</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if ($arParams['AJAX']) {
    die();
} ?>