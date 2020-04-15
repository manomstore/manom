<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

use Manom\Content;

$class1 = $arResult['inFavoriteAndCompare'] ? '' : 'notActive';
$class2 = $arResult['inFavoriteAndCompare'] ? 'alt-img' : 'notActive';

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'кб.', 'мб.', 'гб.', 'тб.');

    return round(1024 ** ($base - floor($base)), $precision).' '.$suffixes[floor($base)];
}

\Manom\GTM::setProductsOnPage([$arResult['PRODUCT_ID']]);
?>
<main class="product container">
    <div class="preloaderCatalog">
        <div class="windows8">
            <div class="wBall" id="wBall_1">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_2">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_3">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_4">
                <div class="wInnerBall"></div>
            </div>
            <div class="wBall" id="wBall_5">
                <div class="wInnerBall"></div>
            </div>
        </div>
    </div>

    <div class="product-nav1">
        <h2 class="product-nav1__title isElementName"><?=$arResult['NAME']?></h2>
        <?php if ($arResult['PROPERTIES']['SELL_PROD']['VALUE'] === 'Да'): ?>
            <div class="p-nav-middle__sale active">Распродажа</div>
        <?php endif; ?>
        <div class="p-nav-top active">
            <label>
                <input
                        class="p-nav-top__checkbox"
                        type="checkbox"
                    <?=$arResult['inFavoriteAndCompare'] ? 'checked' : ''?>
                >
                <div
                        class="p-nav-top__favorite addToFavoriteList <?=$class1?>"
                        data-id='<?=$arResult['ID']?>'
                        title="в избранное"
                ></div>
            </label>
            <div
                    class="p-nav-top__list addToCompareList <?=$class2?>"
                    data-id='<?=$arResult['ID']?>'
            ></div>
        </div>
    </div>

    <div class="product-nav2">
        <div class="product-credential">
            <?php if ($arResult['PROPERTIES']['TOP_FIELD_2']['VALUE']): ?>
                <span
                        id="top_prop_code_prod"
                        class="<?=$arResult['PROPERTIES']['TOP_FIELD_2']['VALUE'] ? '' : 'hidden_top_prop'?>"
                >
                    Код товара:
                    <span class="credential-code">
                        <?=$arResult['PROPERTIES']['TOP_FIELD_2']['VALUE']?>
                    </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
            <?php endif; ?>
            <?php if ($arResult['PROPERTIES']['model']['VALUE']): ?>
                <span
                        id="top_prop_model_prod"
                        class="<?=$arResult['PROPERTIES']['model']['VALUE'] ? '' : 'hidden_top_prop'?>"
                >
                    Модель:
                    <span class="credential-code article_code_field">
                        <?=$arResult['PROPERTIES']['model']['VALUE']?>
                    </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
            <?php endif; ?>
            <?php if ($arResult['PROPERTIES']['TOP_FIELD_3']['VALUE']): ?>
                <span class="credential-rostest">
                    <?=$arResult['PROPERTIES']['TOP_FIELD_3']['VALUE']?>
                </span>
                &nbsp;
            <?php endif; ?>
        </div>

        <?php if (!empty($arResult['rating']['count'])) : ?>
            <div class="product-rating">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <?php if ($i >= $arResult['rating']['rating']): ?>
                        <span> ★</span>
                    <?php else: ?>
                        ★
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
            <div class="product-comments">
                <a href="#product-tabs" data-scroll-to-product-tab="reviews">
                    <span><?=$arResult['rating']['count']?></span>
                    отзыв<?=Content::getNumEnding($arResult['rating']['count'], array('', 'а', 'ов'))?>
                </a>
            </div>
            <div class="product-article">
				<span>Артикул:MWLT2RU/A 2222</span>
			</div>
			<div class="product-code">
				<span>Код: 0000102323</span>
            </div>
            <div class="product-code">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M9.34048 0H12.0002V1.33798H10.6599V10.662H12.0002V12H9.34048V0ZM0 0H2.65973V1.33798H1.34034V5.33101H2.68068V6.66899H1.34034V10.662H2.68068V12H0V0ZM4.00007 0H8.00014V11.9791H6.65981V6.64808H5.31947V11.9791H3.97913V0H4.00007ZM5.34041 5.33101H6.68075V1.33798H5.34041V5.33101Z" fill="#ABABB2"/>
            </svg>
			</div>
        <?php endif; ?>

    </div>

    <div class="product-main row">
        <div class="product-photo">
            <div class="product-photo__left col-1">
                <?php $first = true;?>
                <?php foreach ($arResult['smallImages'] as $i => $image): ?>
                    <img
                        src="<?=$image['src']?>"
                        data-color=""
                        data-photo-id="<?=$i?>"
                        class="<?=$first ? 'active' : ''?>"
                        alt=""
                    >
                    <?php $first = false;?>
                <?php endforeach; ?>
            </div>
            <div class="product-photo__right col-5">
                <?php $first = true;?>
                <?php foreach ($arResult['images'] as $i => $image): ?>
                    <a
                        data-fancybox="gallery-prod"
                        href="<?=$image['src']?>"
                        data-color=""
                        data-photo-id="<?=$i?>"
                        class="pp__big_photo <?=$first ? 'active' : ''?>"
                    >
                        <img src="<?=$image['src']?>" alt="">
                    </a>
                    <?php $first = false;?>
                <?php endforeach; ?>
            </div>
            <div class="product-photo__info">
              <p>Теперь можно делать фото со сверхшироким углом обзора, камера позволяет увидеть и снять то, что происходит за пределами кадра.</p>
            </div>
            <?php if ($arResult['PROPERTIES']['TEXT_UNDER_PHOTO']['VALUE']): ?>
                <div class="interesting-fact">
                    <?=$arResult['PROPERTIES']['TEXT_UNDER_PHOTO']['~VALUE']['TEXT']?>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-content col-3">
            <?php if (strpos($arParams['CURRENT_DIR'], '/catalog/utsenka/') !== false): ?>
                <?php if ($arResult['PROPERTIES']['BS_STR']['~VALUE']): ?>
                    <div class="product-content__discount">
                        <p class="product-content__discount_text">
                            <?=$arResult['PROPERTIES']['BS_STR']['~VALUE']['TEXT']?>
                        </p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($arResult['CATALOG_AVAILABLE'] === 'Y'): ?>
                <div class="product-content__available">В наличии</div>
            <?php else: ?>
                <div class="product-content__available">Нет в наличии</div>
            <?php endif; ?>
            <ul class="product-content__info">
                <li>Первая в мире система трёх камер</li>
                <li>Самое прочное стекло и защита от воды</li>
                <li>Рекордный ресурс аккумулятора</li>
            </ul>
            <div class="product-content__color">
                <h3>Цвет</h3>
                <ul>
                    <li class="product-content__color-black">
                        <button></button>
                    </li>
                    <li class="product-content__color-green">
                        <button class="active"></button>
                    </li>
                    <li class="product-content__color-white">
                        <button></button>
                    </li>
                    <li class="product-content__color-red">
                        <button></button>
                    </li>
                    <li class="product-content__color-yellow">
                        <button></button>
                    </li>
                </ul>
            </div>
            <div class="product-content__size">
                <h3>Объем памяти</h3>
                <ul>
                    <li>
                        <button  class="active">64 ГБ</button>
                    </li>
                    <li>
                        <button>128 ГБ</button>
                    </li>
                    <li>
                        <button>256 ГБ</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="product-sidebar col-3">
            <div class="product-sidebar-cover">
                <?php if ($arResult['CATALOG_AVAILABLE'] === 'Y'): ?>
                    <div class="product-sidebar__total mainBlockPrice">
                        <div class="product-sidebar__total_price">
                            <span
                                class="product-sidebar__total-price-price addToCartBtn"
                                data-id='<?=$arResult['PRODUCT_ID']?>'
                            >
                                <?=number_format($arResult['price'], 0, '', ' ')?>
                            </span>
                            <span id="ruble">₽</span>
                        </div>
                        <div
                            class="product-sidebar__right-price"
                            style="display:<?=empty($arResult['oldPrice']) ? 'none' : 'block'?>"
                        >
                            <p class="product-sidebar__profit">
                                Выгода
                                <span>
                                    <?=number_format($arResult['oldPrice'] - $arResult['price'], 0, '', ' ')?>
                                </span>
                                ₽
                            </p>
                            <p class="product-sidebar__old-price">
                                Было
                                <span>
                                    <?=number_format($arResult['oldPrice'], 0, '', ' ')?>
                                </span>
                                ₽
                            </p>
                        </div>
                    </div>

                    <?php if ($arResult['onlyCash']): ?>
                        <?php
                        $class = 'product-sidebar__note product-sidebar__note--positive js-disallow_loc_buy';
                        if($arResult['locationDisallowBuy']) {
                            $class .= ' dnd-hide';
                        }
                        ?>
                        <div class="<?=$class?>">Доступен для заказа в Москве</div>
                    <?php endif; ?>

                    <div class="js-allow_loc_buy <?=$arResult['locationDisallowBuy'] ? 'dnd-hide' : ''?>">
                        <?php
                        $class = 'product-sidebar__button addToCartBtn addToCartBtn_mainPage';
                        if (!empty($arParams['BASKET'][$arResult['PRODUCT_ID']])) {
                            $class .= ' dsb-hidden';
                        }
                        ?>
                        <a class="<?=$class?>" data-id="<?=$arResult['PRODUCT_ID']?>">
                            Купить
                        </a>

                        <?php if (!empty($arParams['BASKET'][$arResult['PRODUCT_ID']])): ?>
                            <a
                                class="product-sidebar__button goToFcnCart"
                                href="/cart/"
                                data-id="<?=$arResult['PRODUCT_ID']?>"
                            >
                            Купить
                            </a>
                        <?php endif; ?>

                        <div class="product-sidebar__buttons">
                            <?php
                            $class = 'product-sidebar__cheaper';
                            if (empty($arResult['CHEAPER'])) {
                                $class .= ' product-sidebar__cheaper__disbled';
                            }
                            ?>
                            <div class="<?=$class?>">
                                Купить дешевле
                            </div>
                            <div
                                class="product-sidebar__one-click BOC_btn"
                                data-id="<?=$arResult['PRODUCT_ID']?>"
                                data-fancybox
                                data-src="#popap-buy-one-click"
                                href="javascript:;"
                            >
                                Купить в один клик
                            </div>
                        </div>
                    </div>

                    <div id="popap-buy-one-click" class="popap-login">
                        <h3 class="sci-login__title">Купить в один клик</h3>
                        <form class="sci-login__form js-one-click-order">
                            <div class="form_msg js-message-field"></div>
                            <input
                                type="hidden"
                                name="productId"
                                class="js-product-id"
                                value="<?=$arResult['PRODUCT_ID']?>"
                            >
                            <label class="sci-login__label" for="sci-login__name_alt">Имя</label>
                            <input
                                    type="text"
                                    name="name"
                                    value="<?=$arResult['CURRENT_USER']['NAME']?>"
                                    id="sci-login__name_alt"
                                    class="sci-login__input"
                                    placeholder="Ваше имя"
                                    required
                            >
                            <label class="sci-login__label" for="sci-login__tel_alt">Телефон</label>
                            <input
                                    type="tel"
                                    name="phone"
                                    value="<?=$arResult['CURRENT_USER']['PHONE']?>"
                                    id="sci-login__tel_alt"
                                    class="sci-login__input"
                                    placeholder="+7 (___) ___-__-__"
                                    required
                            >
                            <?php if (empty($arResult['CURRENT_USER'])): ?>
                                <label class="sci-login__label" for="sci-login__tel">E-mail</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="sci-login__tel"
                                    class="sci-login__input"
                                    placeholder="E-mail"
                                    required
                                >
                            <?php endif; ?>
                            <button class="sci-login__button">Купить</button>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="product-sidebar__warrenty">
                    <div class="product-warrenty">
                        <div class="product-warrenty__cell">
                            <h5 class="product-warrenty__title">Гарантия</h5>
                        </div>
                        <div class="product-warrenty__cell">
                            <div class="product-warrenty__duration">
                                <span>1 год</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-sidebar__delivery">
                    <div class="product-delivery">
                        <div class="product-delivery__header">
                            <div class="product-delivery__header-cell">
                                <h5 class="product-delivery__title">Доставка</h5>
                            </div>
                            <div class="product-delivery__header-cell">
                                <div class="product-delivery__city" data-city-trigger="">
                                    <svg class="svg-icon svg-icon--location">
                                        <use xlink:href="#location"/>
                                    </svg>
                                    <span data-current-city=""><?=$arParams['LOCATION']['CITY_NAME']?></span>
                                </div>
                            </div>
                        </div>
                        <div class="js-delivery_block">
                            <?php foreach ($arResult['DELIVERIES'] as $delivery): ?>
                                <div class="product-delivery__item">
                                    <div class="product-delivery__item-cell">
                                        <?=$delivery['NAME']?>
                                    </div>
                                    <div class="product-delivery__item-cell">
                                        <?=$delivery['DESCRIPTION']?>
                                    </div>
                                </div>
                                <div class="product-delivery__item">
                                    <div class="product-delivery__item-cell">
                                      Самовывоз
                                    </div>
                                    <div class="product-delivery__item-cell">
                                      Ежедневно 11:00—19:00
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($arResult['PAY_SYSTEMS'])): ?>
                    <div class="product-sidebar__payment-methods">
                        <div class="product-payment-methods">
                            <h5 class="product-payment-methods__title">
                                Мы принимаем
                            </h5>
                        </div>
                        <ul class="product-payment-methods__list">
                            <?php if (in_array('CARD', $arResult['PAY_SYSTEMS'], true)): ?>
                                <li class="product-payment-methods__list-item">
                                    <img
                                            src="<?=SITE_TEMPLATE_PATH?>/assets/img/payment-methods/visa.svg"
                                            alt="Visa"
                                    />
                                </li>
                                <li class="product-payment-methods__list-item">
                                    <img
                                            src="<?=SITE_TEMPLATE_PATH?>/assets/img/payment-methods/mastercard.svg"
                                            alt="MasterCard"
                                    />
                                </li>
                                <li class="product-payment-methods__list-item">
                                    <img
                                            src="<?=SITE_TEMPLATE_PATH?>/assets/img/payment-methods/mir.svg"
                                            alt="Мир"
                                    />
                                </li>
                                <li class="product-payment-methods__list-item">
                                    <img
                                            src="<?=SITE_TEMPLATE_PATH?>/assets/img/payment-methods/wallet.svg"
                                            alt="wallet"
                                    />
                                </li>
                            <?php endif; ?>

                            <?php /*
                            <li class="product-payment-methods__list-item">
                                <img
                                        src="<?= SITE_TEMPLATE_PATH ?>/assets/img/payment-methods/yamoney.svg"
                                        alt="Яндекс.Деньги"
                                />
                            </li>
                            */ ?>

                            <?php if (in_array('CASH', $arResult['PAY_SYSTEMS'], true)): ?>
                                <li class="product-payment-methods__list-item" data-title="Наличные">
                                    <img
                                            src="<?=SITE_TEMPLATE_PATH?>/assets/img/payment-methods/cash.svg"
                                            alt="Наличные"
                                    />
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="product-tabs" class="product-tabs">
        <input id="tab2" type="radio" name="tabs" checked>
        <input id="tab1" type="radio" name="tabs">
        <?php if ($arResult['REVIEWS']): ?>
            <input id="tab3" type="radio" name="tabs" data-product-tab="reviews">
        <?php endif; ?>
        <?php if (!empty($arResult['QNA_VALUES'])): ?>
            <input id="tab4" type="radio" name="tabs">
        <?php endif; ?>
        <?php if ($arResult['DELIV']): ?>
            <input id="tab5" type="radio" name="tabs">
        <?php endif; ?>
        <?php if ($arResult['PROPERTIES']['YOUTUBE']['VALUE']): ?>
            <input id="tab6" type="radio" name="tabs">
        <?php endif; ?>
        <?php if ($arResult['PROPERTIES']['ACESS']['VALUE']): ?>
            <input id="tab7" type="radio" name="tabs">
        <?php endif; ?>
        <?php if ($arResult['CHEAPER']): ?>
            <input id="tab8" type="radio" name="tabs">
        <?php endif; ?>

        <div class="product-tabs__nav">
            <label for="tab2">
                <span>Описание</span>
            </label>
            <label for="tab1">
                <span>Характеристики</span>
            </label>
            <?php if ($arResult['REVIEWS']): ?>
                <label for="tab3">
                    <span>
                        Отзывы
                    </span>
                </label>
            <?php endif; ?>
            <?php if (!empty($arResult['QNA_VALUES'])): ?>
                <label for="tab4">
                    <span>
                        Вопрос-
                        <br>
                        ответ
                    </span>
                </label>
            <?php endif; ?>
            <?php if ($arResult['DELIV']): ?>
                <label for="tab5">
                    <span>
                        Оплата
                        <br>
                        и доставка
                    </span>
                </label>
            <?php endif; ?>
            <?php if ($arResult['PROPERTIES']['YOUTUBE']['VALUE']): ?>
                <label for="tab6">
                    <span>
                        Обзоры
                        <br>
                        на товар
                    </span>
                </label>
            <?php endif; ?>
            <?php if ($arResult['PROPERTIES']['ACESS']['VALUE']): ?>
                <label for="tab7">
                    <span>Аксессуары</span>
                </label>
            <?php endif; ?>
            <?php if ($arResult['CHEAPER']): ?>
                <label for="tab8">
                    <span>
                        Купить
                        <br>
                        дешевле
                    </span>
                </label>
            <?php endif; ?>
            <?php if ($arResult['PROPERTIES']['ACESS']['VALUE']): ?>
                <label for="tab7">
                    <span>Аксессуары</span>
                </label>
            <?php endif; ?>
        </div>

        <section id="content2">
            <div class="tab-content">
                <div class="tab-content__row">
                    <?php if ($arResult['PROPERTIES']['BUNDLE_BOX']['~VALUE']['TEXT']): ?>
                        <div class="tab-content__column right-block">
                            <div class="tab-content__title">Комплект поставки</div>
                            <p class="tab-content__text1 bundle-border">
                                <?=$arResult['PROPERTIES']['BUNDLE_BOX']['~VALUE']['TEXT']?>
                            </p>
                        </div>
                    <?php endif; ?>
                    <?php
                    $class = 'tab-content__column';
                    if ($arResult['PROPERTIES']['BUNDLE_BOX']['~VALUE']['TEXT']) {
                        $class .= ' left-block';
                    }
                    ?>
                    <div class=" <?=$class?>">
                        <p class="tab-content__text1">
                            <?=$arResult['DETAIL_TEXT']?>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section id="content1">
            <div class="tab-content">
                <div class="tab-content__row tab-content__row--reverse">
                    <div class="tab-content__column col-6">
                        <div class="instruction-wrapper tab-content__column col-4">
                            <?php if ($arResult['PROPERTIES']['FILES']['VALUE']): ?>
                                <strong class="name-properties col-12">Инструкции и сертификаты:</strong>
                                <?php foreach ($arResult['PROPERTIES']['FILES']['VALUE'] as $value): ?>
                                    <?php
                                    $file = CFile::GetByID($value)->Fetch();
                                    $splitFileName = explode('.', $file['ORIGINAL_NAME']);
                                    $fileType = array_pop($splitFileName);
                                    $fileName = implode(' ', $splitFileName);
                                    ?>
                                    <a class="files-item" href="<?=CFile::GetPath($value)?>" target="_blank">
                                        <div class="files-item__icon">
                                            <img src="/upload/pdfs-512.png" alt=""/>
                                        </div>
                                        <div class="files-item__content">
                                            <?=$fileName?>
                                            <div class="files-item__size">
                                                <?=formatBytes($file['FILE_SIZE'])?>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="characteristics-wrapper tab-content__column col-8">
                            <?php $APPLICATION->IncludeComponent(
                                'redsign:grupper.list',
                                'catalog_element',
                                Array(
                                    'DISPLAY_PROPERTIES' => $arResult['CHARACTERISTICS'],
                                    'CACHE_TIME' => 36000,
                                ),
                                false
                            ); ?>
                        </div>
                        <div class="tab-content__column col"></div>
                    </div>
                </div>
        </section>

        <section id="content3">
            <div class="tab-content">
                <div class="reviews">
                    <?php foreach ($arResult['REVIEWS'] as $value): ?>
                        <div class="reviews-item">
                            <div class="reviews-item__header">
                                <div class="reviews-item__header-line">
                                    <span class="reviews-item__username"><?=$value['author']?></span>
                                    <span class="reviews-item__date"><?=$value['date']?></span>
                                    <span class="reviews-item__source"><?=$value['source']?></span>
                                </div>
                                <div class="reviews-item__header-line">
                                    <span class="reviews-item__rating">
                                        <span
                                                class="reviews-item__rating-fill"
                                                style="width: <?=$value['rating'] * 20?>%;"
                                        ></span>
                                    </span>
                                    <?php if ($value['recommend']): ?>
                                        <span class="reviews-item__recommend reviews-item__recommend--yes">
                                            Рекомендую
                                        </span>
                                    <?php else: ?>
                                        <span class="reviews-item__recommend reviews-item__recommend--no">
                                            Не рекомендую
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="reviews-item__content">
                                <div class="reviews-item__desc">
                                    <p class="reviews-item__text"><?=$value['review_text']?></p>
                                </div>
                                <?php if (!empty($value['merits'])): ?>
                                    <div class="reviews-item__summary reviews-item__summary--plus">
                                        <p class="reviews-item__text"><?=$value['merits']?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($value['disadvantages'])): ?>
                                    <div class="reviews-item__summary reviews-item__summary--minus">
                                        <p class="reviews-item__text"><?=$value['disadvantages']?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php if (!empty($arResult['QNA_VALUES'])): ?>
            <section id="content4">
                <div class="tab-content">
                    <?php foreach ($arResult['QNA_VALUES'] as $value): ?>
                        <p class="tab-content__item">
                            <span class="tab-content__title"><?=$value['title']?></span>
                            <span class="tab-content__text1"><?=$value['answer']?></span>
                        </p>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <section id="content5">
            <div class="tab-content">
                <?php foreach ($arResult['DELIV'] as $value): ?>
                    <p class="tab-content__item">
                        <span class="tab-content__title"><?=$value['title']?></span>
                        <span class="tab-content__text1"><?=$value['text']?></span>
                    </p>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="content6">
            <div class="tab-content">
                <div class="tab-content__row">
                    <?php foreach ($arResult['PROPERTIES']['YOUTUBE']['VALUE'] as $value): ?>
                        <div class="tab-content__column col-6">
                            <div class="video-container">
                                <iframe
                                    width="100%"
                                    height="100%"
                                    src="<?=$value?>"
                                    frameborder="0"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="content7">
            <?php if (!empty($arResult['PROPERTIES']['ACESS']['VALUE'])): ?>
                <?php
                global $accessoryFilter;
                $accessoryFilter = array(
                    'ID' => $arResult['PROPERTIES']['ACESS']['VALUE'],
                    '>CATALOG_PRICE_1' => 0,
                );
                ?>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:catalog.section',
                    'access',
                    Array(
                        'ACTION_VARIABLE' => '',
                        'ADD_PICT_PROP' => '',
                        'ADD_PROPERTIES_TO_BASKET' => 'N',
                        'ADD_SECTIONS_CHAIN' => 'N',
                        'ADD_TO_BASKET_ACTION' => '',
                        'AJAX_MODE' => 'N',
                        'AJAX_OPTION_ADDITIONAL' => '',
                        'AJAX_OPTION_HISTORY' => 'N',
                        'AJAX_OPTION_JUMP' => 'N',
                        'AJAX_OPTION_STYLE' => 'N',
                        'BACKGROUND_IMAGE' => '',
                        'BASKET_URL' => '',
                        'BRAND_PROPERTY' => '',
                        'BROWSER_TITLE' => '',
                        'CACHE_FILTER' => 'N',
                        'CACHE_GROUPS' => 'N',
                        'CACHE_TIME' => 36000000,
                        'CACHE_TYPE' => 'A',
                        'COMPATIBLE_MODE' => 'Y',
                        'CONVERT_CURRENCY' => 'Y',
                        'CURRENCY_ID' => 'RUB',
                        'CUSTOM_FILTER' => '',
                        'DATA_LAYER_NAME' => '',
                        'DETAIL_URL' => '',
                        'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
                        'DISCOUNT_PERCENT_POSITION' => '',
                        'DISPLAY_BOTTOM_PAGER' => 'N',
                        'DISPLAY_TOP_PAGER' => 'N',
                        'ELEMENT_SORT_FIELD' => 'sort',
                        'ELEMENT_SORT_FIELD2' => 'id',
                        'ELEMENT_SORT_ORDER' => 'asc',
                        'ELEMENT_SORT_ORDER2' => 'desc',
                        'ENLARGE_PRODUCT' => '',
                        'ENLARGE_PROP' => '',
                        'FILTER_NAME' => 'accessoryFilter',
                        'HIDE_NOT_AVAILABLE' => 'Y',
                        'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
                        'IBLOCK_ID' => 6,
                        'IBLOCK_TYPE' => 'catalog',
                        'INCLUDE_SUBSECTIONS' => 'Y',
                        'LABEL_PROP' => '',
                        'LABEL_PROP_MOBILE' => '',
                        'LABEL_PROP_POSITION' => '',
                        'LAZY_LOAD' => 'N',
                        'LINE_ELEMENT_COUNT' => 0,
                        'LOAD_ON_SCROLL' => 'N',
                        'MESSAGE_404' => '',
                        'MESS_BTN_ADD_TO_BASKET' => '',
                        'MESS_BTN_BUY' => '',
                        'MESS_BTN_DETAIL' => '',
                        'MESS_BTN_LAZY_LOAD' => '',
                        'MESS_BTN_SUBSCRIBE' => '',
                        'MESS_NOT_AVAILABLE' => '',
                        'META_DESCRIPTION' => '',
                        'META_KEYWORDS' => '',
                        'OFFERS_CART_PROPERTIES' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'OFFERS_FIELD_CODE' => array(),
                        'OFFERS_LIMIT' => 0,
                        'OFFERS_PROPERTY_CODE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'OFFERS_SORT_FIELD' => 'sort',
                        'OFFERS_SORT_FIELD2' => 'id',
                        'OFFERS_SORT_ORDER' => 'asc',
                        'OFFERS_SORT_ORDER2' => 'desc',
                        'OFFER_ADD_PICT_PROP' => '',
                        'OFFER_TREE_PROPS' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'PAGER_BASE_LINK_ENABLE' => 'N',
                        'PAGER_DESC_NUMBERING' => 'N',
                        'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
                        'PAGER_SHOW_ALL' => 'N',
                        'PAGER_SHOW_ALWAYS' => 'N',
                        'PAGER_TEMPLATE' => '',
                        'PAGER_TITLE' => '',
                        'PAGE_ELEMENT_COUNT' => 6,
                        'PARTIAL_PRODUCT_PROPERTIES' => 'N',
                        'PRICE_CODE' => array('Цена продажи', 'РРЦ'),
                        'PRICE_VAT_INCLUDE' => 'Y',
                        'PRODUCT_BLOCKS_ORDER' => '',
                        'PRODUCT_DISPLAY_MODE' => '',
                        'PRODUCT_ID_VARIABLE' => '',
                        'PRODUCT_PROPERTIES' => '',
                        'PRODUCT_PROPS_VARIABLE' => '',
                        'PRODUCT_QUANTITY_VARIABLE' => '',
                        'PRODUCT_ROW_VARIANTS' => '',
                        'PRODUCT_SUBSCRIPTION' => 'N',
                        'PROPERTY_CODE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'PROPERTY_CODE_MOBILE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'RCM_PROD_ID' => '',
                        'RCM_TYPE' => '',
                        'SECTION_CODE' => '',
                        'SECTION_ID' => '',
                        'SECTION_ID_VARIABLE' => '',
                        'SECTION_URL' => '',
                        'SECTION_USER_FIELDS' => array(),
                        'SEF_MODE' => 'N',
                        'SET_BROWSER_TITLE' => 'N',
                        'SET_LAST_MODIFIED' => 'N',
                        'SET_META_DESCRIPTION' => 'N',
                        'SET_META_KEYWORDS' => 'N',
                        'SET_STATUS_404' => 'N',
                        'SET_TITLE' => 'N',
                        'SHOW_404' => 'N',
                        'SHOW_ALL_WO_SECTION' => 'Y',
                        'SHOW_CLOSE_POPUP' => 'N',
                        'SHOW_DISCOUNT_PERCENT' => 'N',
                        'SHOW_FROM_SECTION' => 'N',
                        'SHOW_MAX_QUANTITY' => 'N',
                        'SHOW_OLD_PRICE' => 'N',
                        'SHOW_PRICE_COUNT' => 1,
                        'SHOW_SLIDER' => 'N',
                        'SLIDER_INTERVAL' => 3000,
                        'SLIDER_PROGRESS' => 'N',
                        'TEMPLATE_THEME' => '',
                        'USE_ENHANCED_ECOMMERCE' => 'N',
                        'USE_MAIN_ELEMENT_SECTION' => 'N',
                        'USE_PRICE_COUNT' => 'N',
                        'USE_PRODUCT_QUANTITY' => 'N',
                        'DISPLAY_COMPARE' => 'N',
                    ),
                    false
                ); ?>
            <?php endif; ?>
        </section>

        <section id="content8">
            <?php if (!empty($arResult['CHEAPER'])): ?>
                <?php
                global $cheaperFilter;
                $cheaperFilter = array(
                    'ID' => $arResult['CHEAPER'],
                    '>CATALOG_PRICE_1' => 0,
                );
                ?>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:catalog.section',
                    'access',
                    Array(
                        'ACTION_VARIABLE' => '',
                        'ADD_PICT_PROP' => '',
                        'ADD_PROPERTIES_TO_BASKET' => 'N',
                        'ADD_SECTIONS_CHAIN' => 'N',
                        'ADD_TO_BASKET_ACTION' => '',
                        'AJAX_MODE' => 'N',
                        'AJAX_OPTION_ADDITIONAL' => '',
                        'AJAX_OPTION_HISTORY' => 'N',
                        'AJAX_OPTION_JUMP' => 'N',
                        'AJAX_OPTION_STYLE' => 'N',
                        'BACKGROUND_IMAGE' => '',
                        'BASKET_URL' => '',
                        'BRAND_PROPERTY' => '',
                        'BROWSER_TITLE' => '',
                        'CACHE_FILTER' => 'N',
                        'CACHE_GROUPS' => 'N',
                        'CACHE_TIME' => 36000000,
                        'CACHE_TYPE' => 'A',
                        'COMPATIBLE_MODE' => 'Y',
                        'CONVERT_CURRENCY' => 'Y',
                        'CURRENCY_ID' => 'RUB',
                        'CUSTOM_FILTER' => '',
                        'DATA_LAYER_NAME' => '',
                        'DETAIL_URL' => '',
                        'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
                        'DISCOUNT_PERCENT_POSITION' => '',
                        'DISPLAY_BOTTOM_PAGER' => 'N',
                        'DISPLAY_TOP_PAGER' => 'N',
                        'ELEMENT_SORT_FIELD' => 'sort',
                        'ELEMENT_SORT_FIELD2' => 'id',
                        'ELEMENT_SORT_ORDER' => 'asc',
                        'ELEMENT_SORT_ORDER2' => 'desc',
                        'ENLARGE_PRODUCT' => '',
                        'ENLARGE_PROP' => '',
                        'FILTER_NAME' => 'cheaperFilter',
                        'HIDE_NOT_AVAILABLE' => 'Y',
                        'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
                        'IBLOCK_ID' => 6,
                        'IBLOCK_TYPE' => 'catalog',
                        'INCLUDE_SUBSECTIONS' => 'Y',
                        'LABEL_PROP' => '',
                        'LABEL_PROP_MOBILE' => '',
                        'LABEL_PROP_POSITION' => '',
                        'LAZY_LOAD' => 'N',
                        'LINE_ELEMENT_COUNT' => 0,
                        'LOAD_ON_SCROLL' => 'N',
                        'MESSAGE_404' => '',
                        'MESS_BTN_ADD_TO_BASKET' => '',
                        'MESS_BTN_BUY' => '',
                        'MESS_BTN_DETAIL' => '',
                        'MESS_BTN_LAZY_LOAD' => '',
                        'MESS_BTN_SUBSCRIBE' => '',
                        'MESS_NOT_AVAILABLE' => '',
                        'META_DESCRIPTION' => '',
                        'META_KEYWORDS' => '',
                        'OFFERS_CART_PROPERTIES' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'OFFERS_FIELD_CODE' => array(),
                        'OFFERS_LIMIT' => 0,
                        'OFFERS_PROPERTY_CODE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'OFFERS_SORT_FIELD' => 'sort',
                        'OFFERS_SORT_FIELD2' => 'id',
                        'OFFERS_SORT_ORDER' => 'asc',
                        'OFFERS_SORT_ORDER2' => 'desc',
                        'OFFER_ADD_PICT_PROP' => '',
                        'OFFER_TREE_PROPS' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'PAGER_BASE_LINK_ENABLE' => 'N',
                        'PAGER_DESC_NUMBERING' => 'N',
                        'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
                        'PAGER_SHOW_ALL' => 'N',
                        'PAGER_SHOW_ALWAYS' => 'N',
                        'PAGER_TEMPLATE' => '',
                        'PAGER_TITLE' => '',
                        'PAGE_ELEMENT_COUNT' => 6,
                        'PARTIAL_PRODUCT_PROPERTIES' => 'N',
                        'PRICE_CODE' => array('Цена продажи', 'РРЦ'),
                        'PRICE_VAT_INCLUDE' => 'Y',
                        'PRODUCT_BLOCKS_ORDER' => '',
                        'PRODUCT_DISPLAY_MODE' => '',
                        'PRODUCT_ID_VARIABLE' => '',
                        'PRODUCT_PROPERTIES' => '',
                        'PRODUCT_PROPS_VARIABLE' => '',
                        'PRODUCT_QUANTITY_VARIABLE' => '',
                        'PRODUCT_ROW_VARIANTS' => '',
                        'PRODUCT_SUBSCRIPTION' => 'N',
                        'PROPERTY_CODE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'PROPERTY_CODE_MOBILE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
                        'RCM_PROD_ID' => '',
                        'RCM_TYPE' => '',
                        'SECTION_CODE' => '',
                        'SECTION_ID' => '',
                        'SECTION_ID_VARIABLE' => '',
                        'SECTION_URL' => '',
                        'SECTION_USER_FIELDS' => array(),
                        'SEF_MODE' => 'N',
                        'SET_BROWSER_TITLE' => 'N',
                        'SET_LAST_MODIFIED' => 'N',
                        'SET_META_DESCRIPTION' => 'N',
                        'SET_META_KEYWORDS' => 'N',
                        'SET_STATUS_404' => 'N',
                        'SET_TITLE' => 'N',
                        'SHOW_404' => 'N',
                        'SHOW_ALL_WO_SECTION' => 'Y',
                        'SHOW_CLOSE_POPUP' => 'N',
                        'SHOW_DISCOUNT_PERCENT' => 'N',
                        'SHOW_FROM_SECTION' => 'N',
                        'SHOW_MAX_QUANTITY' => 'N',
                        'SHOW_OLD_PRICE' => 'N',
                        'SHOW_PRICE_COUNT' => 1,
                        'SHOW_SLIDER' => 'N',
                        'SLIDER_INTERVAL' => 3000,
                        'SLIDER_PROGRESS' => 'N',
                        'TEMPLATE_THEME' => '',
                        'USE_ENHANCED_ECOMMERCE' => 'N',
                        'USE_MAIN_ELEMENT_SECTION' => 'N',
                        'USE_PRICE_COUNT' => 'N',
                        'USE_PRODUCT_QUANTITY' => 'N',
                        'DISPLAY_COMPARE' => 'N',
                    ),
                    false
                ); ?>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php if ($arResult['PROPERTIES']['RECOM']['VALUE']): ?>
    <?php
    global $recommendedFilter;
    $recommendedFilter = array(
        'ID' => $arResult['PROPERTIES']['RECOM']['VALUE'],
        '>CATALOG_PRICE_1' => 0,
    );
    ?>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:catalog.section',
        'recom',
        Array(
            'ACTION_VARIABLE' => '',
            'ADD_PICT_PROP' => '',
            'ADD_PROPERTIES_TO_BASKET' => 'N',
            'ADD_SECTIONS_CHAIN' => 'N',
            'ADD_TO_BASKET_ACTION' => '',
            'AJAX_MODE' => 'N',
            'AJAX_OPTION_ADDITIONAL' => '',
            'AJAX_OPTION_HISTORY' => 'N',
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_STYLE' => 'N',
            'BACKGROUND_IMAGE' => '',
            'BASKET_URL' => '',
            'BRAND_PROPERTY' => '',
            'BROWSER_TITLE' => '',
            'CACHE_FILTER' => 'N',
            'CACHE_GROUPS' => 'N',
            'CACHE_TIME' => 36000000,
            'CACHE_TYPE' => 'A',
            'COMPATIBLE_MODE' => 'Y',
            'CONVERT_CURRENCY' => 'Y',
            'CURRENCY_ID' => 'RUB',
            'CUSTOM_FILTER' => '',
            'DATA_LAYER_NAME' => '',
            'DETAIL_URL' => '',
            'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
            'DISCOUNT_PERCENT_POSITION' => '',
            'DISPLAY_BOTTOM_PAGER' => 'N',
            'DISPLAY_TOP_PAGER' => 'N',
            'ELEMENT_SORT_FIELD' => 'sort',
            'ELEMENT_SORT_FIELD2' => 'id',
            'ELEMENT_SORT_ORDER' => 'asc',
            'ELEMENT_SORT_ORDER2' => 'desc',
            'ENLARGE_PRODUCT' => '',
            'ENLARGE_PROP' => '',
            'FILTER_NAME' => 'recommendedFilter',
            'HIDE_NOT_AVAILABLE' => 'Y',
            'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
            'IBLOCK_ID' => 6,
            'IBLOCK_TYPE' => 'catalog',
            'INCLUDE_SUBSECTIONS' => 'Y',
            'LABEL_PROP' => '',
            'LABEL_PROP_MOBILE' => '',
            'LABEL_PROP_POSITION' => '',
            'LAZY_LOAD' => 'N',
            'LINE_ELEMENT_COUNT' => 0,
            'LOAD_ON_SCROLL' => 'N',
            'MESSAGE_404' => '',
            'MESS_BTN_ADD_TO_BASKET' => '',
            'MESS_BTN_BUY' => '',
            'MESS_BTN_DETAIL' => '',
            'MESS_BTN_LAZY_LOAD' => '',
            'MESS_BTN_SUBSCRIBE' => '',
            'MESS_NOT_AVAILABLE' => '',
            'META_DESCRIPTION' => '',
            'META_KEYWORDS' => '',
            'OFFERS_CART_PROPERTIES' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'OFFERS_FIELD_CODE' => array(),
            'OFFERS_LIMIT' => 0,
            'OFFERS_PROPERTY_CODE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'OFFERS_SORT_FIELD' => 'sort',
            'OFFERS_SORT_FIELD2' => 'id',
            'OFFERS_SORT_ORDER' => 'asc',
            'OFFERS_SORT_ORDER2' => 'desc',
            'OFFER_ADD_PICT_PROP' => '',
            'OFFER_TREE_PROPS' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'PAGER_BASE_LINK_ENABLE' => 'N',
            'PAGER_DESC_NUMBERING' => 'N',
            'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
            'PAGER_SHOW_ALL' => 'N',
            'PAGER_SHOW_ALWAYS' => 'N',
            'PAGER_TEMPLATE' => '',
            'PAGER_TITLE' => '',
            'PAGE_ELEMENT_COUNT' => 6,
            'PARTIAL_PRODUCT_PROPERTIES' => 'N',
            'PRICE_CODE' => array('Цена продажи', 'РРЦ'),
            'PRICE_VAT_INCLUDE' => 'Y',
            'PRODUCT_BLOCKS_ORDER' => '',
            'PRODUCT_DISPLAY_MODE' => '',
            'PRODUCT_ID_VARIABLE' => '',
            'PRODUCT_PROPERTIES' => '',
            'PRODUCT_PROPS_VARIABLE' => '',
            'PRODUCT_QUANTITY_VARIABLE' => '',
            'PRODUCT_ROW_VARIANTS' => '',
            'PRODUCT_SUBSCRIPTION' => 'N',
            'PROPERTY_CODE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'PROPERTY_CODE_MOBILE' => array('MORE_PHOTO', 'ACESS_STR', 'BS_STR'),
            'RCM_PROD_ID' => '',
            'RCM_TYPE' => '',
            'SECTION_CODE' => '',
            'SECTION_ID' => '',
            'SECTION_ID_VARIABLE' => '',
            'SECTION_URL' => '',
            'SECTION_USER_FIELDS' => array(),
            'SEF_MODE' => 'N',
            'SET_BROWSER_TITLE' => 'N',
            'SET_LAST_MODIFIED' => 'N',
            'SET_META_DESCRIPTION' => 'N',
            'SET_META_KEYWORDS' => 'N',
            'SET_STATUS_404' => 'N',
            'SET_TITLE' => 'N',
            'SHOW_404' => 'N',
            'SHOW_ALL_WO_SECTION' => 'Y',
            'SHOW_CLOSE_POPUP' => 'N',
            'SHOW_DISCOUNT_PERCENT' => 'N',
            'SHOW_FROM_SECTION' => 'N',
            'SHOW_MAX_QUANTITY' => 'N',
            'SHOW_OLD_PRICE' => 'N',
            'SHOW_PRICE_COUNT' => 1,
            'SHOW_SLIDER' => 'N',
            'SLIDER_INTERVAL' => 3000,
            'SLIDER_PROGRESS' => 'N',
            'TEMPLATE_THEME' => '',
            'USE_ENHANCED_ECOMMERCE' => 'N',
            'USE_MAIN_ELEMENT_SECTION' => 'N',
            'USE_PRICE_COUNT' => 'N',
            'USE_PRODUCT_QUANTITY' => 'N',
            'DISPLAY_COMPARE' => 'N',
        ),
        false
    ); ?>
<?php endif; ?>
<script>
    $(function () {
        window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("product", [
            "items" => [
                $arResult["ID"]
            ],
            "categoryId" => $arResult["SECTION"]["ID"],
        ])?>);
    });
</script>
