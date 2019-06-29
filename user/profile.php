<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Профиль");
if ($USER->IsAuthorized()){?>
    <section class="personal-burger" style="margin-top: 20px;">
        <div class="container">
            <input class="filter-burger__checkbox" type="checkbox" id="filter-burger">
            <label class="filter-burger" for="filter-burger" title="Фильтр"></label>
        </div>
    </section>
    <div class="personal container">
        <div class="personal-main">
            <aside class="personal__aside">
                <h1 class="personal__title">Личный кабинет</h1>
                <p id="personal-nav__item1" class="personal-nav__item personal-nav__name" data-id="pb-info">Мои настройки</p>
                <p class="personal-nav__name">Покупки:</p>
                <a href="/user/history.php" id="personal-nav__item2" class="personal-nav__item">История покупок</a>
                <a href="/user/favorite/" id="personal-nav__item4" class="personal-nav__item">Товары в избранном</a>
                <a href="/catalog/compare/" id="personal-nav__item4" class="personal-nav__item">Сравнение товаров</a>
                <p class="personal-nav__name">Моя активность:</p>
<!--                <a href="/user/review/add-list/" id="personal-nav__item4" class="personal-nav__item">Добавить отзыв товару</a>-->
<!--                <a href="/user/review/" id="personal-nav__item4" class="personal-nav__item">Мои отзывы</a>-->
            </aside>

            <!-- Личный кабинет - основной блок -->
            <main class="personal-block">
                <?if($USER->IsAuthorized()){
                    $APPLICATION->IncludeComponent(
                        "bitrix:main.profile",
                        "profile",
                        Array(
                            "CHECK_RIGHTS" => "N",
                            "SEND_INFO" => "N",
                            "SET_TITLE" => "Y",
                            "USER_PROPERTY" => array(),
                            "USER_PROPERTY_NAME" => ""
                        )
                    );
                }?>
                <!-- Избранные товары  -->
                <section id="pb-favour" class="personal-block__section">
                    <h2 class="pb-info__title">Избранные товары:</h2>
                    <div class="cb-nav cb-nav-personal">
                        <div class="cb-nav-sort">
                            Сортировать по
                            <select required>
                                <option selected>популярности</option>
                                <option>цене</option>
                            </select>
                        </div>
                        <div class="cb-nav-count">
                            Товары <span class="cb-nav-count__current">1—12</span> из <span class="cb-nav-count__total">125</span>
                            Товаров на странице
                            <select required>
                                <option value="6">6</option>
                                <option value="12" selected>12</option>
                                <option value="24">24</option>
                                <option value="all">все</option>
                            </select>
                        </div>
                        <div class="cb-nav-style">
                            Вид
                            <div class="cb-nav-style__block">
                                <label>
                                    <input class="radio" type="radio" name="style-favour" id="v-single-favour">
                                    <img src="/assets/img/v-single.svg" alt="" class="v-single">
                                </label>
                                <label>
                                    <input class="radio" type="radio" name="style-favour" id="v-block-favour" checked>
                                    <img src="/assets/img/v-block.svg" alt="" class="v-block">
                                </label>
                                <label>
                                    <input class="radio" type="radio" name="style-favour" id="v-line-favour">
                                    <img src="/assets/img/v-line.svg" alt="" class="v-line">
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- 	single -->
                    <div id="cb-single-favour" class="cb-single no-gutters">
                        <div class="cb-single__item col-6">
                            <div class="product-card cb-single-card disable">
                                <div class="product-card__img cb-single-card__img">
                                    <div class="product-card__slide cb-single-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide cb-single-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <p class="p-label-top active">Товар дня</p>
                                <div class="cb-single-nav-top">
                                    <label>
                                        <input class="p-nav-top__checkbox" type="checkbox" checked>
                                        <div class="p-nav-top__favorite" title="в избранное"></div>
                                    </label>
                                    <div class="p-nav-top__list"></div>
                                </div>
                                <div class="p-nav-middle">
                                    <div class="p-nav-middle__sale active">Распродажа</div>
                                    <div class="p-nav-middle__rating">★ ★ ★ <span>★ ★</span></div>
                                    <div class="p-nav-middle__comments"><span>12</span></div>
                                </div>
                                <h3 class="p-name cb-single-name">
                                    Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                </h3>
                                <div class="p-cart-properties">
                                    <p>
                                        <span class="p-cart-properties__name">Производитель процессора</span>
                                        <span class="p-cart-properties__value">Intel</span>
                                    </p>
                                    <p>
                                        <span class="p-cart-properties__name">Производитель чипсета видео</span>
                                        <span class="p-cart-properties__value">Intel</span>
                                    </p>
                                    <p>
                                        <span class="p-cart-properties__name">Тип процессора</span>
                                        <span class="p-cart-properties__value">Intel Core i3</span>
                                    </p>
                                    <p>
                                        <span class="p-cart-properties__name">Предустановленная ОС</span>
                                        <span class="p-cart-properties__value">Без системы</span>
                                    </p>
                                </div>
                                <div class="p-nav-bottom">
                                    <div class="p-nav-bottom__price">
                                        5 000<span> ₽</span>
                                        <div class="p-nav-bottom__oldprice">40 000</div>
                                    </div>
                                    <button class="p-nav-bottom__shopcart" disabled></button>
                                </div>
                            </div>
                        </div>
                        <div class="cb-single__item col-6">
                            <div class="product-card cb-single-card">
                                <div class="product-card__img cb-single-card__img">
                                    <div class="product-card__slide cb-single-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide cb-single-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <p class="p-label-top active">Товар дня</p>
                                <div class="cb-single-nav-top">
                                    <label>
                                        <input class="p-nav-top__checkbox" type="checkbox" checked>
                                        <div class="p-nav-top__favorite" title="в избранное"></div>
                                    </label>
                                    <div class="p-nav-top__list"></div>
                                </div>
                                <div class="p-nav-middle">
                                    <div class="p-nav-middle__sale active">Распродажа</div>
                                    <div class="p-nav-middle__rating">★ ★ ★ <span>★ ★</span></div>
                                    <div class="p-nav-middle__comments"><span>12</span></div>
                                </div>
                                <h3 class="p-name cb-single-name">
                                    Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                </h3>
                                <div class="p-cart-properties">
                                    <p>
                                        <span class="p-cart-properties__name">Производитель процессора</span>
                                        <span class="p-cart-properties__value">Intel</span>
                                    </p>
                                    <p>
                                        <span class="p-cart-properties__name">Производитель чипсета видео</span>
                                        <span class="p-cart-properties__value">Intel</span>
                                    </p>
                                    <p>
                                        <span class="p-cart-properties__name">Тип процессора</span>
                                        <span class="p-cart-properties__value">Intel Core i3</span>
                                    </p>
                                    <p>
                                        <span class="p-cart-properties__name">Предустановленная ОС</span>
                                        <span class="p-cart-properties__value">Без системы</span>
                                    </p>
                                </div>
                                <div class="p-nav-bottom">
                                    <div class="p-nav-bottom__price">
                                        5 000<span> ₽</span>
                                        <div class="p-nav-bottom__oldprice">40 000</div>
                                    </div>
                                    <button class="p-nav-bottom__shopcart" disabled></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 	block -->
                    <div id="cb-block-favour" class="cb-block no-gutters">
                        <div class="cb-block__item col-4">
                            <div class="product-card disable">
                                <div class="product-card__img">
                                    <div class="product-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <p class="p-label-top active">Товар дня</p>
                                <div class="p-nav-top">
                                    <label>
                                        <input class="p-nav-top__checkbox" type="checkbox" checked>
                                        <div class="p-nav-top__favorite" title="в избранное"></div>
                                    </label>
                                    <div class="p-nav-top__list"></div>
                                </div>
                                <div class="p-nav-middle">
                                    <div class="p-nav-middle__sale active">Распродажа</div>
                                    <div class="p-nav-middle__rating">★ ★ ★ <span>★ ★</span></div>
                                    <div class="p-nav-middle__comments"><span>12</span></div>
                                </div>
                                <h3 class="p-name">
                                    Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                </h3>
                                <div class="p-nav-bottom">
                                    <div class="p-nav-bottom__price">
                                        5 000<span> ₽</span>
                                        <div class="p-nav-bottom__oldprice">40 000</div>
                                    </div>
                                    <button class="p-nav-bottom__shopcart" disabled></button>
                                </div>
                            </div>
                        </div>
                        <div class="cb-block__item col-4">
                            <div class="product-card">
                                <div class="product-card__img">
                                    <div class="product-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <p class="p-label-top active">Товар дня</p>
                                <div class="p-nav-top">
                                    <label>
                                        <input class="p-nav-top__checkbox" type="checkbox" checked>
                                        <div class="p-nav-top__favorite" title="в избранное"></div>
                                    </label>
                                    <div class="p-nav-top__list"></div>
                                </div>
                                <div class="p-nav-middle">
                                    <div class="p-nav-middle__sale active">Распродажа</div>
                                    <div class="p-nav-middle__rating">★ ★ ★ <span>★ ★</span></div>
                                    <div class="p-nav-middle__comments"><span>12</span></div>
                                </div>
                                <h3 class="p-name">
                                    Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                </h3>
                                <div class="p-nav-bottom">
                                    <div class="p-nav-bottom__price">
                                        5 000<span> ₽</span>
                                        <div class="p-nav-bottom__oldprice">40 000</div>
                                    </div>
                                    <button class="p-nav-bottom__shopcart" disabled></button>
                                </div>
                            </div>
                        </div>
                        <div class="cb-block__item col-4">
                            <div class="product-card">
                                <div class="product-card__img">
                                    <div class="product-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <p class="p-label-top active">Товар дня</p>
                                <div class="p-nav-top">
                                    <label>
                                        <input class="p-nav-top__checkbox" type="checkbox" checked>
                                        <div class="p-nav-top__favorite" title="в избранное"></div>
                                    </label>
                                    <div class="p-nav-top__list"></div>
                                </div>
                                <div class="p-nav-middle">
                                    <div class="p-nav-middle__sale active">Распродажа</div>
                                    <div class="p-nav-middle__rating">★ ★ ★ <span>★ ★</span></div>
                                    <div class="p-nav-middle__comments"><span>12</span></div>
                                </div>
                                <h3 class="p-name">
                                    Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                </h3>
                                <div class="p-nav-bottom">
                                    <div class="p-nav-bottom__price">
                                        5 000<span> ₽</span>
                                        <div class="p-nav-bottom__oldprice">40 000</div>
                                    </div>
                                    <div class="p-nav-bottom__shopcart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- line -->
                    <div id="cb-line-favour" class="cb-line no-gutters">
                        <div class="cb-line__item col-12">
                            <div class="product-card cb-line-card disable">  <!-- disable -->
                                <div class="product-card__img cb-line-card__img">
                                    <div class="product-card__slide cb-line-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide cb-line-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <div class="cb-line-card__main">
                                    <div class="p-nav-middle">
                                        <div class="cb-line-card__sale active">Распродажа</div>
                                        <div class="p-nav-middle__rating cb-line-card__rating">★ ★ ★ <span>★ ★</span></div>
                                        <div class="p-nav-middle__comments"><span>12</span></div>
                                    </div>
                                    <div class="cb-line-card__text">
                                        <h3 class="p-name cb-line-name">
                                            Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                        </h3>
                                        <div class="p-cart-properties cb-line-properties">
                                            <p>
                                                <span class="p-cart-properties__name">Производитель процессора</span>
                                                <span class="p-cart-properties__value">Intel</span>
                                            </p>
                                            <p>
                                                <span class="p-cart-properties__name">Производитель чипсета видео</span>
                                                <span class="p-cart-properties__value">Intel</span>
                                            </p>
                                            <p>
                                                <span class="p-cart-properties__name">Тип процессора</span>
                                                <span class="p-cart-properties__value">Intel Core i3</span>
                                            </p>
                                            <p>
                                                <span class="p-cart-properties__name">Предустановленная ОС</span>
                                                <span class="p-cart-properties__value">Без системы</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="cb-line-nav-top">
                                        <div class="cb-line-nav-top__available">В&nbsp;наличии</div>
                                        <label>
                                            <input class="cb-line-nav-top__checkbox" type="checkbox" checked>
                                            <div class="cb-line-nav-top__favorite">В&nbsp;избранное</div>
                                        </label>
                                        <div class="cb-line-nav-top__list">Сравнить</div>
                                    </div>
                                </div>
                                <div class="p-nav-bottom cb-line-bottom">
                                    <div class="p-nav-bottom__price">
                                        5 000<span> ₽</span>
                                        <div class="p-nav-bottom__oldprice">40 000</div>
                                    </div>
                                    <button class="cb-line-bottom__buy" disabled>Купить</button>
                                </div>
                            </div>
                        </div>
                        <div class="cb-line__item col-12">
                            <div class="product-card cb-line-card">  <!-- disable -->
                                <div class="product-card__img cb-line-card__img">
                                    <div class="product-card__slide cb-line-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide cb-line-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <div class="cb-line-card__main">
                                    <div class="p-nav-middle">
                                        <div class="cb-line-card__sale active">Распродажа</div>
                                        <div class="p-nav-middle__rating cb-line-card__rating">★ ★ ★ <span>★ ★</span></div>
                                        <div class="p-nav-middle__comments"><span>12</span></div>
                                    </div>
                                    <div class="cb-line-card__text">
                                        <h3 class="p-name cb-line-name">
                                            Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                        </h3>
                                        <div class="p-cart-properties cb-line-properties">
                                            <p>
                                                <span class="p-cart-properties__name">Производитель процессора</span>
                                                <span class="p-cart-properties__value">Intel</span>
                                            </p>
                                            <p>
                                                <span class="p-cart-properties__name">Производитель чипсета видео</span>
                                                <span class="p-cart-properties__value">Intel</span>
                                            </p>
                                            <p>
                                                <span class="p-cart-properties__name">Тип процессора</span>
                                                <span class="p-cart-properties__value">Intel Core i3</span>
                                            </p>
                                            <p>
                                                <span class="p-cart-properties__name">Предустановленная ОС</span>
                                                <span class="p-cart-properties__value">Без системы</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="cb-line-nav-top">
                                        <div class="cb-line-nav-top__available">В наличии</div>
                                        <label>
                                            <input class="cb-line-nav-top__checkbox" type="checkbox" checked>
                                            <div class="cb-line-nav-top__favorite">В избранное</div>
                                        </label>
                                        <div class="cb-line-nav-top__list">Сравнить</div>
                                    </div>
                                </div>
                                <div class="p-nav-bottom cb-line-bottom">
                                    <div class="p-nav-bottom__price">
                                        5 000<span> ₽</span>
                                        <div class="p-nav-bottom__oldprice">40 000</div>
                                    </div>
                                    <button class="cb-line-bottom__buy">Купить</button>
                                </div>
                            </div>
                        </div>
                        <div class="cb-line__item col-12">
                            <div class="product-card cb-line-card">  <!-- disable -->
                                <div class="product-card__img cb-line-card__img">
                                    <div class="product-card__slide cb-line-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide cb-line-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <div class="cb-line-card__main">
                                    <div class="p-nav-middle">
                                        <div class="cb-line-card__sale">Распродажа</div>
                                        <div class="p-nav-middle__rating cb-line-card__rating">★ ★ ★ <span>★ ★</span></div>
                                        <div class="p-nav-middle__comments"><span>12</span></div>
                                    </div>
                                    <div class="cb-line-card__text">
                                        <h3 class="p-name cb-line-name">
                                            Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                        </h3>
                                        <div class="p-cart-properties cb-line-properties">
                                            <p>
                                                <span class="p-cart-properties__name">Производитель процессора</span>
                                                <span class="p-cart-properties__value">Intel</span>
                                            </p>
                                            <p>
                                                <span class="p-cart-properties__name">Производитель чипсета видео</span>
                                                <span class="p-cart-properties__value">Intel</span>
                                            </p>
                                            <p>
                                                <span class="p-cart-properties__name">Тип процессора</span>
                                                <span class="p-cart-properties__value">Intel Core i3</span>
                                            </p>
                                            <p>
                                                <span class="p-cart-properties__name">Предустановленная ОС</span>
                                                <span class="p-cart-properties__value">Без системы</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="cb-line-nav-top">
                                        <div class="cb-line-nav-top__available">В наличии</div>
                                        <label>
                                            <input class="cb-line-nav-top__checkbox" type="checkbox" checked>
                                            <div class="cb-line-nav-top__favorite">В избранное</div>
                                        </label>
                                        <div class="cb-line-nav-top__list">Сравнить</div>
                                    </div>
                                </div>
                                <div class="p-nav-bottom cb-line-bottom">
                                    <div class="p-nav-bottom__price">
                                        5 000<span> ₽</span>
                                        <div class="p-nav-bottom__oldprice">40 000</div>
                                    </div>
                                    <button class="cb-line-bottom__buy">Купить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Мои отзывы  -->
                <section id="pb-comments" class="personal-block__section">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "review",
                        array(
                            "DISPLAY_DATE" => "Y",
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => "Y",
                            "DISPLAY_PREVIEW_TEXT" => "Y",
                            "AJAX_MODE" => "Y",
                            "IBLOCK_TYPE" => "catalog",
                            "IBLOCK_ID" => "7",
                            "NEWS_COUNT" => "20",
                            "SORT_BY1" => "ACTIVE_FROM",
                            "SORT_ORDER1" => "DESC",
                            "SORT_BY2" => "SORT",
                            "SORT_ORDER2" => "ASC",
                            "FILTER_NAME" => "",
                            "FIELD_CODE" => array(
                                0 => "ID",
                                1 => "",
                            ),
                            "PROPERTY_CODE" => array(
                                0 => "",
                                1 => "DESCRIPTION",
                                2 => "",
                            ),
                            "CHECK_DATES" => "Y",
                            "DETAIL_URL" => "",
                            "PREVIEW_TRUNCATE_LEN" => "",
                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                            "SET_TITLE" => "Y",
                            "SET_BROWSER_TITLE" => "Y",
                            "SET_META_KEYWORDS" => "Y",
                            "SET_META_DESCRIPTION" => "Y",
                            "SET_LAST_MODIFIED" => "Y",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                            "ADD_SECTIONS_CHAIN" => "Y",
                            "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
                            "PARENT_SECTION" => "",
                            "PARENT_SECTION_CODE" => "",
                            "INCLUDE_SUBSECTIONS" => "Y",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600",
                            "CACHE_FILTER" => "Y",
                            "CACHE_GROUPS" => "Y",
                            "DISPLAY_TOP_PAGER" => "Y",
                            "DISPLAY_BOTTOM_PAGER" => "Y",
                            "PAGER_TITLE" => "Новости",
                            "PAGER_SHOW_ALWAYS" => "Y",
                            "PAGER_TEMPLATE" => "",
                            "PAGER_DESC_NUMBERING" => "Y",
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                            "PAGER_SHOW_ALL" => "Y",
                            "PAGER_BASE_LINK_ENABLE" => "Y",
                            "SET_STATUS_404" => "Y",
                            "SHOW_404" => "Y",
                            "MESSAGE_404" => "",
                            "PAGER_BASE_LINK" => "",
                            "PAGER_PARAMS_NAME" => "arrPager",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "COMPONENT_TEMPLATE" => "review",
                            "STRICT_SECTION_CHECK" => "N",
                            "FILE_404" => ""
                        ),
                        false
                    );?>
                    <h2 class="pb-comments__title">Мои отзывы:</h2>
                    <!-- 	block -->
                    <div class="pb-comments__block">
                        <div class="pb-comments__product">
                            <div class="product-card disable">
                                <div class="product-card__img">
                                    <div class="product-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <p class="p-label-top active">Товар дня</p>
                                <div class="p-nav-top">
                                    <label>
                                        <input class="p-nav-top__checkbox" type="checkbox" checked>
                                        <div class="p-nav-top__favorite" title="в избранное"></div>
                                    </label>
                                    <div class="p-nav-top__list"></div>
                                </div>
                                <h3 class="p-name">
                                    Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                </h3>
                            </div>
                        </div>
                        <div class="pb-comments__content">
                            <div class="pb-comments__header">
                                <div class="pb-comments__foto">
                                    <img src="img/personal-foto.jpg" alt="">
                                </div>
                                <p class="pb-comments__text">Автор: <span>Константин Константинопольский</span></p>
                                <p class="pb-comments__text">Дата: <span>14-08-2018</span></p>
                                <div class="pb-comments__rating">Оценка:
                                    <div class="p-nav-middle__rating">★ ★ ★ <span>★ ★</span></div>
                                    <span>удовлетворительная</span></div>
                            </div>
                            <p class="pb-comments__chapter">Достоинства:</p>
                            <p class="pb-comments__text">Хорошая звукоизоляция: чтобы слушать музыку, прогуливаясь по шумной улице, не нужно задирать громкость на полную.</p>
                            <p class="pb-comments__text">Годно держатся в ушах.</p>
                            <p class="pb-comments__text">Зарядка micro-USB.</p>
                            <p class="pb-comments__chapter">Недостатки:</p>
                            <p class="pb-comments__text">Ядовитые верха и некоторая требовательность к источнику. В телефонном приложении Я.Музыки пришлось выставить HQ и покрутить эквалайзер — со всеми другими наушниками, что пробовал, хватало стандартного качества и плоской эквализации. В подкастах сибилянты обращают на себя внимание. Но с компа всё звучит получше. </p>
                            <p class="pb-comments__text">Домашний ноут — макбук про 2013, макось 10.12 — их не увидел за пару попыток (но мне особо и не надо было, так, ради интереса пробовал). Со служебным более новым тачбарным прошкой спарились без проблем.</p>
                            <p class="pb-comments__chapter">Комментарий:</p>
                            <p class="pb-comments__text">Разговаривают со мной по-китайски</p>
                        </div>
                    </div>
                    <!-- 	block -->
                    <div class="pb-comments__block">
                        <div class="pb-comments__product">
                            <div class="product-card">
                                <div class="product-card__img">
                                    <div class="product-card__slide">
                                        <img src="img/4396800.jpg" alt="">
                                    </div>
                                    <div class="product-card__slide">
                                        <img src="img/4396801.jpg" alt="">
                                    </div>
                                </div>
                                <p class="p-label-top active">Товар дня</p>
                                <div class="p-nav-top">
                                    <label>
                                        <input class="p-nav-top__checkbox" type="checkbox" checked>
                                        <div class="p-nav-top__favorite" title="в избранное"></div>
                                    </label>
                                    <div class="p-nav-top__list"></div>
                                </div>
                                <h3 class="p-name">
                                    Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple
                                </h3>
                            </div>
                        </div>
                        <div class="pb-comments__content">
                            <div class="pb-comments__header">
                                <div class="pb-comments__foto">
                                    <img src="img/personal-foto.jpg" alt="">
                                </div>
                                <p class="pb-comments__text">Автор: <span>Константин Константинопольский</span></p>
                                <p class="pb-comments__text">Дата: <span>14-08-2018</span></p>
                                <div class="pb-comments__rating">Оценка:
                                    <div class="p-nav-middle__rating">★ ★ ★ <span>★ ★</span></div>
                                    <span>удовлетворительная</span></div>
                            </div>
                            <p class="pb-comments__chapter">Достоинства:</p>
                            <p class="pb-comments__text">Хорошая звукоизоляция: чтобы слушать музыку, прогуливаясь по шумной улице, не нужно задирать громкость на полную.</p>
                            <p class="pb-comments__text">Годно держатся в ушах.</p>
                            <p class="pb-comments__text">Зарядка micro-USB.</p>
                            <p class="pb-comments__chapter">Недостатки:</p>
                            <p class="pb-comments__text">Ядовитые верха и некоторая требовательность к источнику. В телефонном приложении Я.Музыки пришлось выставить HQ и покрутить эквалайзер — со всеми другими наушниками, что пробовал, хватало стандартного качества и плоской эквализации. В подкастах сибилянты обращают на себя внимание. Но с компа всё звучит получше. </p>
                            <p class="pb-comments__text">Домашний ноут — макбук про 2013, макось 10.12 — их не увидел за пару попыток (но мне особо и не надо было, так, ради интереса пробовал). Со служебным более новым тачбарным прошкой спарились без проблем.</p>
                            <p class="pb-comments__chapter">Комментарий:</p>
                            <p class="pb-comments__text">Разговаривают со мной по-китайски</p>
                        </div>
                    </div>
                </section>
                <!-- Последние просмотренные товары -->
            </main>
            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.products.viewed",
                "cart-product",
                array(
                    "ACTION_VARIABLE" => "action_cpv",
                    "ADDITIONAL_PICT_PROP_2" => "MORE_PHOTO",
                    "ADDITIONAL_PICT_PROP_3" => "-",
                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                    "ADD_TO_BASKET_ACTION" => "BUY",
                    "BASKET_URL" => "/personal/basket.php",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "3600",
                    "CACHE_TYPE" => "A",
                    "CART_PROPERTIES_2" => array(
                        0 => "NEWPRODUCT",
                        1 => "NEWPRODUCT,SALELEADER",
                        2 => "",
                    ),
                    "CART_PROPERTIES_3" => array(
                        0 => "COLOR_REF",
                        1 => "SIZES_SHOES",
                        2 => "",
                    ),
                    "CONVERT_CURRENCY" => "Y",
                    "CURRENCY_ID" => "RUB",
                    "DATA_LAYER_NAME" => "dataLayer",
                    "DEPTH" => "",
                    "DISCOUNT_PERCENT_POSITION" => "top-right",
                    "ENLARGE_PRODUCT" => "STRICT",
                    "ENLARGE_PROP_2" => "NEWPRODUCT",
                    "HIDE_NOT_AVAILABLE" => "N",
                    "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                    "IBLOCK_ID" => "6",
                    "IBLOCK_MODE" => "single",
                    "IBLOCK_TYPE" => "catalog",
                    "LABEL_PROP_2" => array(
                        0 => "NEWPRODUCT",
                    ),
                    "LABEL_PROP_MOBILE_2" => "",
                    "LABEL_PROP_POSITION" => "top-left",
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                    "MESS_BTN_BUY" => "Купить",
                    "MESS_BTN_DETAIL" => "Подробнее",
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",
                    "MESS_RELATIVE_QUANTITY_FEW" => "мало",
                    "MESS_RELATIVE_QUANTITY_MANY" => "много",
                    "MESS_SHOW_MAX_QUANTITY" => "Наличие",
                    "OFFER_TREE_PROPS_3" => array(
                        0 => "COLOR_REF",
                        1 => "SIZES_SHOES",
                        2 => "SIZES_CLOTHES",
                    ),
                    "PAGE_ELEMENT_COUNT" => "8",
                    "PARTIAL_PRODUCT_PROPERTIES" => "N",
                    "PRICE_CODE" => array(
                        0 => "Цена продажи",
                    ),
                    "PRICE_VAT_INCLUDE" => "Y",
                    "PRODUCT_BLOCKS_ORDER" => "price,props,quantityLimit,sku,quantity,buttons,compare",
                    "PRODUCT_ID_VARIABLE" => "id",
                    "PRODUCT_PROPS_VARIABLE" => "prop",
                    "PRODUCT_QUANTITY_VARIABLE" => "",
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
                    "PRODUCT_SUBSCRIPTION" => "N",
                    "PROPERTY_CODE_2" => array(
                        0 => "NEWPRODUCT",
                        1 => "SALELEADER",
                        2 => "SPECIALOFFER",
                        3 => "MANUFACTURER",
                        4 => "MATERIAL",
                        5 => "COLOR",
                        6 => "SALELEADER,SPECIALOFFER,MATERIAL,COLOR,KEYWORDS,BRAND_REF",
                        7 => "",
                    ),
                    "PROPERTY_CODE_3" => array(
                        0 => "ARTNUMBER",
                        1 => "COLOR_REF",
                        2 => "SIZES_SHOES",
                        3 => "SIZES_CLOTHES",
                        4 => "",
                    ),
                    "PROPERTY_CODE_MOBILE_2" => "",
                    "RELATIVE_QUANTITY_FACTOR" => "5",
                    "SECTION_CODE" => "",
                    "SECTION_ELEMENT_CODE" => "",
                    "SECTION_ELEMENT_ID" => "",
                    "SECTION_ID" => "",
                    "SHOW_CLOSE_POPUP" => "N",
                    "SHOW_DISCOUNT_PERCENT" => "Y",
                    "SHOW_FROM_SECTION" => "N",
                    "SHOW_MAX_QUANTITY" => "N",
                    "SHOW_OLD_PRICE" => "Y",
                    "SHOW_PRICE_COUNT" => "1",
                    "SHOW_PRODUCTS_2" => "N",
                    "SHOW_SLIDER" => "Y",
                    "SLIDER_INTERVAL" => "3000",
                    "SLIDER_PROGRESS" => "Y",
                    "TEMPLATE_THEME" => "",
                    "USE_ENHANCED_ECOMMERCE" => "N",
                    "USE_PRICE_COUNT" => "N",
                    "USE_PRODUCT_QUANTITY" => "Y",
                    "COMPONENT_TEMPLATE" => "cart-product",
                    "DISPLAY_COMPARE" => "Y",
                    "PROPERTY_CODE_6" => array(
                        0 => "",
                        1 => "",
                    ),
                    "PROPERTY_CODE_MOBILE_6" => array(
                    ),
                    "CART_PROPERTIES_6" => array(
                        0 => "",
                        1 => "",
                    ),
                    "ADDITIONAL_PICT_PROP_6" => "-",
                    "LABEL_PROP_6" => array(
                    ),
                    "PROPERTY_CODE_7" => array(
                        0 => "MORE_PHOTO",
                        1 => "",
                    ),
                    "CART_PROPERTIES_7" => array(
                        0 => "",
                        1 => "",
                    ),
                    "ADDITIONAL_PICT_PROP_7" => "-",
                    "OFFER_TREE_PROPS_7" => array(
                    ),
                    "SHOW_PRODUCTS_6" => "N",
                    "LABEL_PROP_MOBILE_6" => "",
                    "COMPARE_PATH" => "",
                    "MESS_BTN_COMPARE" => "Сравнить",
                    "COMPARE_NAME" => "CATALOG_COMPARE_LIST"
                ),
                false
            );?>
        </div>

    </div>
<?}else{
    LocalRedirect("/");
}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
