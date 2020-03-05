<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;

$request = Application::getInstance()->getContext()->getRequest();
$uriString = $request->getRequestUri();
$uri = new Uri($uriString);
$uri->addParams(['search_sort' => '1']);
$urlSort1 = $uri->getUri();
$uri->addParams(['search_sort' => '2']);
$urlSort2 = $uri->getUri();

global $USER;
?>
<div class="catalog-main catalog-main-sr">
    <?php if ($arResult['SEARCH']): ?>
        <aside class="catalog-filter">
            <ul class="catalog-filter__ul">
                <li class="catalog-filter__li">
                    <input type="checkbox" class="checkbox-1" checked="">
                    <i></i>
                    <h3>Сортировка</h3>
                    <p>
                        <a href="<?=$urlSort1?>">По дате</a>
                    </p>
                    <p>
                        <a href="<?=$urlSort2?>">По релевантности</a>
                    </p>
                </li>
            </ul>
        </aside>
    <?php endif; ?>
    <section class="catalog-block ">
        <?php if ($arResult['SEARCH']): ?>
            <div id='PROPDS_BLOCK'>
                <div class="cb-line no-gutters ffdf" style="display: flex;">
                    <?php foreach ($arResult['SEARCH'] as $item): ?>
                        <?php
                        $class1 = $item['IN_FAVORITE_AND_COMPARE'] ? '' : 'notActive';
                        $class2 = $item['IN_FAVORITE_AND_COMPARE'] ? 'alt-img' : 'notActive';

                        [$price, $oldPrice] = $item['PRICE']['PRICES'];
                        ?>
                        <div class="cb-line__item">
                            <div class="product-card cb-line-card <?=$item['CAN_BUY'] ? 'enable' : 'disable'?>">
                                <!-- disable -->
                                <div class="product-card__img cb-line-card__img">
                                    <?php foreach ($item['IMAGES'] as $image): ?>
                                        <div class="product-card__slide">
                                            <img src="<?=$image['src']?>" alt="<?=$item['TITLE']?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="cb-line-card__main">
                                    <div class="p-nav-middle">
                                        <?php if ($item['SALE']): ?>
                                            <div class="p-nav-middle__sale active">
                                                Распродажа
                                            </div>
                                        <?php endif; ?>

                                        <?php /*
                                        <div class="p-nav-middle__rating cb-line-card__rating">
                                            <?php for ($i = 0; $i < 5; $i++): ?>
                                                <?php if ($i >= $item['RATING']['rating']): ?>
                                                    <span> ★ </span>
                                                <?php else: ?>
                                                    ★
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <div class="p-nav-middle__comments">
                                            <span><?=$item['RATING']['count']?></span>
                                        </div>
                                        */ ?>
                                    </div>
                                    <div class="cb-line-card__text">
                                        <h3 class="p-name cb-line-name">
                                            <a href="<?=$item['URL']?>"><?=$item['TITLE']?></a>
                                        </h3>
                                        <div class="p-cart-properties cb-line-properties">
                                            <?php foreach ($item['DISPLAY_PROPERTIES'] as $property): ?>
                                                <p>
                                                    <span class="p-cart-properties__name"><?=$property['NAME']?></span>
                                                    <span class="p-cart-properties__value"><?=$property['VALUE']?></span>
                                                </p>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="cb-line-nav-top">
                                        <?php if (!$item['CAN_BUY']): ?>
                                            <div class="cb-line-nav-top__available">
                                                <?=$arResult['ORIGINAL_PARAMETERS']['MESS_NOT_AVAILABLE']?>
                                            </div>
                                        <?php endif; ?>
                                        <label>
                                            <input
                                                    class="cb-line-nav-top__checkbox"
                                                    type="checkbox"
                                                <?=$item['IN_FAVORITE_AND_COMPARE'] ? 'checked' : ''?>
                                            >
                                            <div
                                                    class="addToFavoriteListOnFP cb-line-nav-top__favorite addToFavoriteList <?=$class1?>"
                                                    data-id='<?=$item['ITEM_ID']?>'
                                            >
                                                В&nbsp;избранное
                                            </div>
                                        </label>
                                        <div
                                                class="cb-line-nav-top__list addToCompareList <?=$class2?>"
                                                data-id='<?=$item['ITEM_ID']?>'
                                        >
                                            Сравнить
                                        </div>

                                        <?php /*
                                        <label>
                                            <input class="cb-line-nav-top__checkbox" type="checkbox" checked>
                                            <div class="cb-line-nav-top__favorite">В&nbsp;избранное</div>
                                        </label>
                                        <div class="cb-line-nav-top__list">Сравнить</div>
                                        */ ?>
                                    </div>
                                </div>
                                <div class="p-nav-bottom cb-line-bottom">
                                    <div class="p-nav-bottom">
                                        <?php if (
                                            !empty((int)$oldPrice) &&
                                            (int)$price !== (int)$oldPrice
                                        ): ?>
                                            <div class="p-nav-bottom__price">
                                                <?=number_format($price, 0, '', ' ')?>
                                                <span> ₽</span>
                                                <div class="p-nav-bottom__oldprice">
                                                    <?=number_format($oldPrice, 0, '', ' ')?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="p-nav-bottom__price">
                                                <?=number_format($price, 0, '', ' ')?>
                                                <span> ₽</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <button
                                            class="cb-line-bottom__buy <?=$item['CAN_BUY'] ? 'addToCartBtn' : ''?>"
                                            data-id='<?=$item['PRODUCT_ID']?>'
                                        <?=$item['CAN_BUY'] ? 'enable' : 'disable'?>
                                    >
                                        Купить
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?=$arResult['NAV_STRING']?>
            </div>
        <?php else: ?>
            <div class="message-incorrect" style="text-align:center;">
                <p>Упс 🙁 По запросу «<?=$_REQUEST['q']?>» ничего не нашли</p>
                <br>
                <p>Попробуй изменить формулировку или воспользуйся нашим каталогом</p>
                Исправьте поисковую фразу и повторите поиск.
            </div>
        <?php endif; ?>
    </section>
</div>
<script>
    $(function () {
        window.gtmActions.initCommonData(<?=GTM::getDataJS("searchresults")?>);
    });
</script>