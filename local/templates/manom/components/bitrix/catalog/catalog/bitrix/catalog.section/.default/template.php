<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

?>

<? if (!empty($arResult["ITEMS"])): ?>
<section class="catalog-block">
    <? if (!empty($arParams["BRAND_DATA"])): ?>
        <h2 class="cb-title"><?= $arParams["BRAND_DATA"]["name"] ?></h2>
    <? elseif ($arParams["IS_SALE"]): ?>
        <h2 class="cb-title">Распродажа</h2>
    <? else: ?>
        <h2 class="cb-title"><?= $arResult['NAME'] ?></h2>
    <? endif; ?>
    <input class="filter-burger__checkbox" type="checkbox" id="filter-burger">
    <label class="filter-burger" for="filter-burger" title=""></label>
    <div class="cb-filter">
        <?php /*
        <div class="cb-filter__param">Процессор: Intel Core i5<span>×</span></div>
        <div class="cb-filter__param">Цвет: Белый<span>×</span></div>
        <div class="cb-filter__param">Экран: 1920х1080<span>×</span></div>
        */ ?>
        <div class="cb-filter__clear dnd-hide">Очистить фильтры</div>
    </div>
    <div class="cb-nav">
        <div class="cb-nav-sort">
            <span class="cb-nav__text">Сортировать</span>
            <select required name="sort_by">
                <? foreach ($arParams["SORT_LIST"] as $sortItem): ?>
                    <option <?= $sortItem["selected"] ? "selected" : "" ?> value="<?= $sortItem["code"] ?>">
                        <?= $sortItem["name"] ?>
                    </option>
                <? endforeach; ?>
            </select>
        </div>
        <div class="cb-nav-count catTopCount visually-hidden">
            <?=$arResult['NAV_STRING']?>
            <span class="cb-nav__text">Товаров на странице</span>
            <select name="countOnPage" required>
                <? foreach ($arParams["PAGE_COUNT_LIST"] as $pageCount => $data): ?>
                    <option value="<?= $pageCount ?>" <?= $data["SELECTED"] ? "selected" : "" ?>>
                        <?= $data["NAME"] ?>
                    </option>
                <? endforeach; ?>
            </select>
        </div>
        <div class="cb-nav-style" style="display: none">
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
                                        title="Добавить в избранное"
                                ></div>
                            </label>
                            <div  title="Добавить в сравнение"
                                    class="p-nav-top__list addToCompareList <?=$class2?>"
                                    data-id='<?=$item['id']?>'
                            ></div>
                        </div>
                        <div class="p-nav-middle">
                            <?php if ($item['productPreorder']): ?>
                                <div class="product-label product-label--preorder active">Предзаказ</div>
                            <?php endif; ?>
                            <?php if ($item['sale']): ?>
                                <div class="p-nav-middle__sale active">Распродажа</div>
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
                            <?php if ($item['showOldPrice']): ?>
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
                            <? if (!$item["productPreorder"]): ?>
                                <button
                                        class="p-nav-bottom__shopcart <?= $item['canBuy'] ? 'addToCartBtn' : '' ?>"
                                        data-id='<?= $item['productId'] ?>'
                                    <?= $item['canBuy'] ? 'enable' : 'disable' ?>
                                ></button>
                            <? endif; ?>
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
                <div class="cb-block__item col-3">

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
                            <a href="#" title="Добавить в сравнение">
                                <div
                                    class="p-nav-top__list addToCompareList <?=$class2?>"
                                    data-id='<?=$item['id']?>'
                                    title="Добавить в сравнение"
                                ></div>
                            </a>
                        </div>
                        <div class="p-nav-middle">
                            <?php if (!$item['canBuy']): ?>
                                <div class="p-nav-middle__sale active">Нет в наличии</div>
                            <?php endif; ?>
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
                            <a href="<?=$item['url']?>"
                               data-product-list="section"
                               data-product-id="<?= $item['id'] ?>"
                            ><?=$item['name']?></a>
                        </h3>
                        <div class="p-nav-bottom">
                            <?php if ($item['showOldPrice']): ?>
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
                            <? if (!$item["productPreorder"]): ?>
                                <button
                                        class="p-nav-bottom__shopcart <?= $item['canBuy'] ? 'addToCartBtn' : '' ?>"
                                        data-id='<?= $item['productId'] ?>'
                                    <?= $item['canBuy'] ? 'enable' : 'disable' ?>
                                ></button>
                            <? endif; ?>
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
                                <div    title="Добавить в сравнение"
                                        class="cb-line-nav-top__list addToCompareList <?=$class2?>"
                                        data-id='<?=$item['id']?>'
                                >
                                    Сравнить
                                </div>
                            </div>
                        </div>
                        <div class="p-nav-bottom cb-line-bottom">
                            <div class="p-nav-bottom">
                                <?php if ($item['showOldPrice']): ?>
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
                            <? if (!$item["productPreorder"]): ?>
                                <button
                                        class="cb-line-bottom__buy <?= $item['canBuy'] ? 'addToCartBtn' : '' ?>"
                                        data-id='<?= $item['productId'] ?>'
                                    <?= $item['canBuy'] ? 'enable' : 'disable' ?>
                                >
                                    Купить
                                </button>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <span style="display:none" data-gtm-data='<?= \Manom\GTM::getDataJS($arResult['GTM_PAGE_TYPE'], $arResult['GTM_DATA']) ?>'></span>
            <span style="display:none" data-gtm-products='<?= \Manom\GTM::getProductsOnPageJS() ?>'></span>
        </div>
        <?=$arResult['NAV_STRING']?>
    </div>
    <?php if ($arParams['AJAX']) {
        die();
    } ?>
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
<? else: ?>
    <div class="content">
        <div class="container  empty-container">
            <div class="empty-page">
                <? if (!empty($arParams["BRAND_DATA"])): ?>
                    <div class="empty__block empty__block--brand">
                        <img class="empty__block-image" src="<?= $arParams["BRAND_DATA"]["logo"] ?>">
                        <p class="empty__text">
                            Здесь пока пусто. Посмотрите другие
                            <a href="<?= $arResult["SECTION_PAGE_URL"] ?>">
                                <?= $arResult['NAME'] ?>
                            </a>
                        </p>
                    </div>
                <? else: ?>
                    <div class="empty__block empty__block--goods">
                        <h2 class="empty__block-header">
                            <?= $arResult["NAME"] ?>
                        </h2>
                        <p class="empty__text">
                            Здесь пока пусто. Посмотрите другие
                            <? if ($arResult["PARENT_SECTION"]): ?>
                                товары в разделе <a href="<?= $arResult["PARENT_SECTION"]["SECTION_PAGE_URL"] ?>">
                                    <?= $arResult["PARENT_SECTION"]["NAME"] ?>
                                </a>
                            <? else: ?>
                                <a href="<?= SITE_DIR ?>catalog/">товары</a>
                            <? endif; ?>
                        </p>
                    </div>
                <? endif; ?>
            </div>
        </div>
    </div>
<? endif; ?>
<script>
    $(function () {
        window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS($arResult['GTM_PAGE_TYPE'], $arResult['GTM_DATA'])?>);
    });
</script>