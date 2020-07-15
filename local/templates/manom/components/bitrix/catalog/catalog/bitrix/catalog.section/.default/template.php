<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<section class="catalog-block" <?=$arParams['IS_BRAND'] ? 'style="width:100%;"' : ''?>>
    <h2 class="cb-title"><?=$arResult['NAME']?></h2>
    <input class="filter-burger__checkbox" type="checkbox" id="filter-burger">
    <label class="filter-burger" for="filter-burger" title="Фильтр"></label>
    <div class="cb-filter">
        <?php /*
        <div class="cb-filter__param">Процессор: Intel Core i5<span>×</span></div>
        <div class="cb-filter__param">Цвет: Белый<span>×</span></div>
        <div class="cb-filter__param">Экран: 1920х1080<span>×</span></div>
        */ ?>
        <?php if (!$arParams['IS_BRAND']): ?>
            <div class="cb-filter__clear dnd-hide">Очистить фильтры</div>
        <?php endif; ?>
    </div>
    <div class="cb-nav">
        <div class="cb-nav-sort">
            <span class="cb-nav__text">Сортировать</span>
            <select required name="sort_by">
                <? if ($arParams["IS_SEARCH"]): ?>
                    <option selected value="relevance">по релевантности</option>
                    <option value="pop">по популярности</option>
                <? else: ?>
                    <option selected value="pop">по популярности</option>
                <? endif; ?>
                <option value="price">по цене</option>
                <option value="name">по названию</option>
            </select>
        </div>
        <div class="cb-nav-count catTopCount">
            <?=$arResult['NAV_STRING']?>
            <span class="cb-nav__text">Товаров на странице</span>
            <select name="countOnPage" required>
                <option value="3">3</option>
                <option value="6">6</option>
                <option value="12" selected>12</option>
                <option value="24">24</option>
                <option value="9999">все</option>
            </select>
        </div>
        <div class="cb-nav-style">
            Вид
            <div class="cb-nav-style__block">
                <label>
                    <input class="radio" type="radio" name="style" id="v-single">
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/v-single.svg" alt="" class="v-single">
                </label>
                <label>
                    <input class="radio" type="radio" name="style" id="v-block" checked>
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/v-block.svg" alt="" class="v-block">
                </label>
                <label>
                    <input class="radio" type="radio" name="style" id="v-line">
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/v-line.svg" alt="" class="v-line">
                </label>
            </div>
        </div>
    </div>
    <?
    \Manom\GTM::setProductsOnPage($arResult['ITEMS'], true);
    ?>
    <div id='PROPDS_BLOCK'>
        <?php if ($arParams['AJAX']) {
            $APPLICATION->RestartBuffer();
        } ?>

        <div class="cb-single no-gutters" <?=$arParams['BLOCK_STYLE'] === 'v-single' ? 'style="display: flex;"' : ''?>>
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php
                $class1 = $item['inFavoriteAndCompare'] ? '' : 'notActive';
                $class2 = $item['inFavoriteAndCompare'] ? 'alt-img' : 'notActive';
                ?>
                <div class="cb-single__item col-6">
                    <div class="product-card cb-single-card <?=$item['canBuy'] ? 'enable' : 'disable'?>">
                        <div class="product-card__img cb-single-card__img">
                            <?php foreach ($item['images'] as $image): ?>
                                <div class="product-card__slide cb-single-card__slide">
                                    <img
                                            src="<?=$image['src']?>"
                                            alt="<?=$item['name']?>"
                                            data-product-list="section"
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
                        <div class="cb-single-nav-top">
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
                        <h3 class="p-name cb-single-name">
                            <a href="<?=$item['url']?>"
                               data-product-list="section"
                               data-product-id="<?= $item['id'] ?>"
                            ><?=$item['name']?></a>
                        </h3>
                        <div class="p-cart-properties">
                            <?php foreach ($item['properties'] as $property): ?>
                                <p>
                                    <span class="p-cart-properties__name"><?=$property['NAME']?></span>
                                    <span class="p-cart-properties__value"><?=$property['VALUE']?></span>
                                </p>
                            <?php endforeach; ?>
                        </div>
                        <div class="p-nav-bottom">
                            <?php if (
                                !empty((int)$item['oldPrice']) &&
                                (int)$item['price'] !== (int)$item['oldPrice']
                            ): ?>
                                <div class="p-nav-bottom__price">
                                    <?=number_format($item['price'], 0, '', ' ')?>
                                    <span> ₽</span>
                                    <div class="p-nav-bottom__oldprice">
                                        <?=number_format($item['oldPrice'], 0, '', ' ')?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="p-nav-bottom__price">
                                    <?=number_format($item['price'], 0, '', ' ')?>
                                    <span> ₽</span>
                                </div>
                            <?php endif; ?>
                            <button
                                    class="p-nav-bottom__shopcart <?=$item['canBuy'] ? 'addToCartBtn' : ''?>"
                                    data-id='<?=$item['productId']?>'
                                <?=$item['canBuy'] ? 'enable' : 'disable'?>
                            ></button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cb-block no-gutters" <?=$arParams['BLOCK_STYLE'] === 'v-block' ? 'style="display: flex;"' : ''?>>
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php
                $class1 = $item['inFavoriteAndCompare'] ? '' : 'notActive';
                $class2 = $item['inFavoriteAndCompare'] ? 'alt-img' : 'notActive';
                ?>
                <div class="cb-block__item col-3<?=$arParams['IS_BRAND'] ? ' block__item__brand' : ''?>">

                    <div class="product-card <?=$item['canBuy'] ? 'enable' : 'disable'?>">
                        <div class="product-card__img">
                            <?php foreach ($item['images'] as $image): ?>
                                <div class="product-card__slide">
                                    <img
                                            src="<?=$image['src']?>"
                                            alt="<?=$item['name']?>"
                                            data-product-list="section"
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
                            <?php if (!$item['canBuy']): ?>
                                <div class="p-nav-middle__sale active">Нет в наличии</div>
                            <?php endif; ?>
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
                            <a href="<?=$item['url']?>"
                               data-product-list="section"
                               data-product-id="<?= $item['id'] ?>"
                            ><?=$item['name']?></a>
                        </h3>
                        <div class="p-nav-bottom">
                            <?php if (
                                !empty((int)$item['oldPrice']) &&
                                (int)$item['price'] !== (int)$item['oldPrice']
                            ): ?>
                                <div class="p-nav-bottom__price">
                                    <?=number_format($item['price'], 0, '', ' ')?>
                                    <span> ₽</span>
                                    <div class="p-nav-bottom__oldprice">
                                        <?=number_format($item['oldPrice'], 0, '', ' ')?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="p-nav-bottom__price">
                                    <?=number_format($item['price'], 0, '', ' ')?>
                                    <span> ₽</span>
                                </div>
                            <?php endif; ?>
                            <button
                                    class="p-nav-bottom__shopcart <?=$item['canBuy'] ? 'addToCartBtn' : ''?>"
                                    data-id='<?=$item['productId']?>'
                                <?=$item['canBuy'] ? 'enable' : 'disable'?>
                            ></button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cb-line no-gutters" <?=$arParams['BLOCK_STYLE'] === 'v-line' ? 'style="display: flex;"' : ''?>>
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php
                $class1 = $item['inFavoriteAndCompare'] ? '' : 'notActive';
                $class2 = $item['inFavoriteAndCompare'] ? 'alt-img' : 'notActive';
                ?>
                <div class="cb-line__item col-12">
                    <div class="product-card cb-line-card <?=$item['canBuy'] ? 'enable' : 'disable'?>">
                        <div class="product-card__img cb-line-card__img">
                            <?php foreach ($item['images'] as $image): ?>
                                <div class="product-card__slide">
                                    <img
                                            src="<?=$image['src']?>"
                                            alt="<?=$item['name']?>"
                                            data-product-list="section"
                                            data-product-id="<?= $item['id'] ?>"
                                            data-href="<?=$item['url']?>"
                                    >
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="cb-line-card__main">
                            <div class="p-nav-middle">
                                <?php if ($item['sale']): ?>
                                    <div class="p-nav-middle__sale active">Распродажа</div>
                                <?php endif; ?>

                                <div class="p-nav-middle__rating cb-line-card__rating">
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
                            </div>
                            <div class="cb-line-card__text">
                                <h3 class="p-name cb-line-name">
                                    <a href="<?=$item['url']?>"
                                       data-product-list="section"
                                       data-product-id="<?= $item['id'] ?>"
                                    ><?=$item['name']?></a>
                                </h3>
                                <div class="p-cart-properties cb-line-properties">
                                    <?php foreach ($item['properties'] as $property): ?>
                                        <p>
                                            <span class="p-cart-properties__name"><?=$property['NAME']?></span>
                                            <span class="p-cart-properties__value"><?=$property['VALUE']?></span>
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="cb-line-nav-top">
                                <?php if (!$item['canBuy']): ?>
                                    <div class="cb-line-nav-top__available">Нет в наличии</div>
                                <?php endif; ?>
                                <label>
                                    <input
                                            class="cb-line-nav-top__checkbox"
                                            type="checkbox"
                                        <?=$item['inFavoriteAndCompare'] ? 'checked' : ''?>
                                    >
                                    <div
                                            class="cb-line-nav-top__favorite addToFavoriteList <?=$class1?>"
                                            data-id='<?=$item['id']?>'
                                    >
                                        В&nbsp;избранное
                                    </div>
                                </label>
                                <div
                                        class="cb-line-nav-top__list addToCompareList <?=$class2?>"
                                        data-id='<?=$item['id']?>'
                                >
                                    Сравнить
                                </div>
                            </div>
                        </div>
                        <div class="p-nav-bottom cb-line-bottom">
                            <div class="p-nav-bottom">
                                <?php if (
                                    !empty((int)$item['oldPrice']) &&
                                    (int)$item['price'] !== (int)$item['oldPrice']
                                ): ?>
                                    <div class="p-nav-bottom__price">
                                        <?=number_format($item['price'], 0, '', ' ')?>
                                        <span> ₽</span>
                                        <div class="p-nav-bottom__oldprice">
                                            <?=number_format($item['oldPrice'], 0, '', ' ')?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="p-nav-bottom__price">
                                        <?=number_format($item['price'], 0, '', ' ')?>
                                        <span> ₽</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button
                                    class="cb-line-bottom__buy <?=$item['canBuy'] ? 'addToCartBtn' : ''?>"
                                    data-id='<?=$item['productId']?>'
                                <?=$item['canBuy'] ? 'enable' : 'disable'?>
                            >
                                Купить
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <span style="display:none" data-gtm-data='<?= \Manom\GTM::getDataJS($arResult['GTM_PAGE_TYPE'], $arResult['GTM_DATA']) ?>'></span>
            <span style="display:none" data-gtm-products='<?= \Manom\GTM::getProductsOnPageJS() ?>'></span>
        </div>
        <?=$arResult['NAV_STRING']?>
        <?php if ($arParams['AJAX']) {
            die();
        } ?>
    </div>
    <?php /*
    <div class="cb-nav-bottom">
        <div class="cb-nav-pagination">
            <div class="cb-nav-pagination__item active">1</div>
            <div class="cb-nav-pagination__item">2</div>
            <div class="cb-nav-pagination__item">3</div>
        </div>
        <div class="cb-nav-count">
            Товары
            <span class="cb-nav-count__current">1—12</span>
            из
            <span class="cb-nav-count__total">125</span>
        </div>
    </div>
    */ ?>
</section>
<script>
    $(function () {
        window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS($arResult['GTM_PAGE_TYPE'], $arResult['GTM_DATA'])?>);
    });
</script>