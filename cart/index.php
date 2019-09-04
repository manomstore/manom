<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
CModule::IncludeModule('statistic');
CModule::IncludeModule('sale');
global $USER;
?>

<div class="content">
<? if (!$_REQUEST['ORDER_ID']): ?>
<main class="shopcart shopcart--step-1" id="so_main_block">
  <div class="shopcart-nav1">
    <div class="container">
      <div class="shopcart-nav1__wrapper">
        <div class="layout_cart_menu"></div>
        <input class="shopcart-nav1__radio" data-num="1" id="shopcart-tab1" type="radio" name="shopcart-tabs" value="#shopcart-item1" checked>
        <label class="shopcart-tab" for="shopcart-tab1">Корзина</label>
        <input class="slide-disable shopcart-nav1__radio" data-num="2" id="shopcart-tab2" type="radio" name="shopcart-tabs" value="#shopcart-item2">
        <label class="shopcart-tab" for="shopcart-tab2">Контактные данные</label>
        <input class="slide-disable shopcart-nav1__radio" data-num="3" id="shopcart-tab3" type="radio" name="shopcart-tabs" value="#shopcart-item3">
        <label class="shopcart-tab" for="shopcart-tab3">Выбор доставки</label>
        <input class="slide-disable shopcart-nav1__radio" data-num="4" id="shopcart-tab4" type="radio" name="shopcart-tabs" value="#shopcart-item4">
        <label class="shopcart-tab" for="shopcart-tab4">Способ оплаты</label>
      </div>
    </div>
  </div>
<? else: ?>
<main class="shopcart" id="so_main_block">
<? endif ?>
  <div class="container">
    <div class="preloaderCatalog preloaderCatalogActive">
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

      <? if (!$_REQUEST['ORDER_ID']):
          $quantity = 0;

          $dbBasketItems = \CSaleBasket::GetList(
              false,
              [
                  "FUSER_ID" => \CSaleBasket::GetBasketUserID(),
                  "LID" => SITE_ID,
                  "ORDER_ID" => "NULL",
                  "DELAY" => "N"
              ],
              false,
              false,
              [
                  "ID",
                  "QUANTITY",
              ]);

          while ($arItems = $dbBasketItems->Fetch()) {
              $quantity += $arItems['QUANTITY'];
          }

          ?>
      <div class="shopcart__wrapper">
        <h1 class="shopcart__title js-shopcart-title">Корзина</h1>
        <button class="button-del button-del--top" type="button">Очистить</button>

          <span class="shopcart__sum-amount js-shopcart-amount"><?= $quantity ?> товара</span>
      </div>

      <div class="shopcart-main">
        <div class="shopcart-items">
          <section class="shopcart-item js-shopcart-items" id="shopcart-item1">
						<? $APPLICATION->IncludeComponent(
							"bitrix:sale.basket.basket",
							"manom",
							[
								"ACTION_VARIABLE"                 => "action",  // Название переменной действия
								"AUTO_CALCULATION"                => "Y",  // Автопересчет корзины
								"TEMPLATE_THEME"                  => "blue",  // Цветовая тема
								"COLUMNS_LIST"                    => [
									0 => "NAME",
									1 => "DISCOUNT",
									2 => "WEIGHT",
									3 => "DELETE",
									4 => "DELAY",
									5 => "TYPE",
									6 => "PRICE",
									7 => "QUANTITY",
								],
								"COMPONENT_TEMPLATE"              => ".default",
								"COUNT_DISCOUNT_4_ALL_QUANTITY"   => "N",
								"GIFTS_BLOCK_TITLE"               => "Выберите один из подарков",  // Текст заголовка "Подарки"
								"GIFTS_CONVERT_CURRENCY"          => "Y",  // Показывать цены в одной валюте
								"GIFTS_HIDE_BLOCK_TITLE"          => "N",  // Скрыть заголовок "Подарки"
								"GIFTS_HIDE_NOT_AVAILABLE"        => "N",  // Не отображать товары, которых нет на складах
								"GIFTS_MESS_BTN_BUY"              => "Выбрать",  // Текст кнопки "Выбрать"
								"GIFTS_MESS_BTN_DETAIL"           => "Подробнее",  // Текст кнопки "Подробнее"
								"GIFTS_PAGE_ELEMENT_COUNT"        => "4",  // Количество элементов в строке
								"GIFTS_PRODUCT_PROPS_VARIABLE"    => "prop",  // Название переменной, в которой передаются характеристики товара
								"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",  // Название переменной, в которой передается количество товара
								"GIFTS_SHOW_DISCOUNT_PERCENT"     => "Y",  // Показывать процент скидки
								"GIFTS_SHOW_IMAGE"                => "Y",  // Показывать изображение
								"GIFTS_SHOW_NAME"                 => "Y",  // Показывать название
								"GIFTS_SHOW_OLD_PRICE"            => "Y",  // Показывать старую цену
								"GIFTS_TEXT_LABEL_GIFT"           => "Подарок",  // Текст метки "Подарка"
								"GIFTS_PLACE"                     => "BOTTOM",  // Вывод блока "Подарки"
								"HIDE_COUPON"                     => "N",  // Спрятать поле ввода купона
								"OFFERS_PROPS"                    => [  // Свойства, влияющие на пересчет корзины
								                                        0 => "SIZES_SHOES",
								                                        1 => "SIZES_CLOTHES",
								],
								"PATH_TO_ORDER"                   => "/personal/order.php",  // Страница оформления заказа
								"PRICE_VAT_SHOW_VALUE"            => "N",  // Отображать значение НДС
								"QUANTITY_FLOAT"                  => "N",  // Использовать дробное значение количества
								"SET_TITLE"                       => "Y",  // Устанавливать заголовок страницы
								"USE_GIFTS"                       => "Y",  // Показывать блок "Подарки"
								"USE_PREPAYMENT"                  => "N",  // Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
							],
							false
						); ?>
          </section>
          <!-- Корзина - Контактные данные -->
          <section class="shopcart-item" id="shopcart-item2">
            <div class="sci-contact">
              <!-- Табы -->
              <div class="sci-contact__tabs">
                <input class="sci-contact__tab visually-hidden" id="sci-contact-tab1" type="radio" name="tabs" checked>
                <input class="sci-contact__tab visually-hidden" id="sci-contact-tab2" type="radio" name="tabs">
                <div class="sci-contact__top sci-contact__top--contacts">
                  <h2 class="sci-contact__title">Ваши данные</h2>
                  <label data-prop="PERSON_TYPE_1" class="sci-contact__tab-label rb_so" for="sci-contact-tab1"><span>Оформить как физ. лицо</span></label>
                  <label data-prop="PERSON_TYPE_2" class="sci-contact__tab-label rb_so" for="sci-contact-tab2"><span>Оформить как юр. лицо</span></label>
                </div>
                <section class="sci-contact-content" id="sci-contact-content1">
                    <? if (!$USER->IsAuthorized()):?>
                        <div class="js-email-block sci-contact__field">
                            <label class="sci-contact__label" for="sci-contact__email">E-mail</label>
                            <input type="email"
                                   data-prop="ORDER_PROP_3"
                                   name="sci-contact__email"
                                   id="sci-contact__email"
                                   class="sci-contact__input js-email-field"
                                   placeholder="Введите e-mail"
                                   required>
                        </div>
                        <div class="js-password-block dsb-hidden sci-contact__field">
                            <label class="sci-contact__label"
                                   for="sci-contact__password">Пароль</label>
                            <input type="password"
                                   name="sci-contact__password"
                                   id="sci-contact__password"
                                   class="sci-contact__input js-password-field"
                                   placeholder="Ваш пароль"
                            >
                            <button class="shopcart-sidebar__button js-auth" type="button">
                                Войти
                            </button>
                        </div>
                    <? endif; ?>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label" for="sci-contact__fio">Фамилия и имя</label>
                    <input type="text"
                           data-prop="ORDER_PROP_1"
                           name="sci-contact__fio"
                           id="sci-contact__fio"
                           class="sci-contact__input"
                           placeholder="Ваше имя и фамилия"
                           required>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label" for="sci-contact__tel">Телефон</label>
                    <input type="tel"
                           data-prop="ORDER_PROP_2"
                           name="sci-contact__tel"
                           id="sci-contact__tel"
                           class="sci-contact__input"
                           placeholder="Введите номер телефона"
                           required> <!-- pattern="+[0-9]{1} ([0-9]{3}) [0-9]{3}-[0-9]{4}"  -->
                  </div>
                </section>
                <section class="sci-contact-content" id="sci-contact-content2">
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">Название компании
                      <input type="text"
                             data-prop="ORDER_PROP_4"
                             name="sci-contact__ur-name"
                             id="sci-contact__ur-name"
                             class="sci-contact__input"
                             placeholder="Введите название компании"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">Телефон
                      <input type="text"
                             data-prop="ORDER_PROP_33"
                             name="sci-contact__ur-phone"
                             id="sci-contact__ur-phone"
                             class="sci-contact__input"
                             placeholder="Введите номер телефона"
                             required>
                    </label>
                  </div>
                    <? if (!$USER->IsAuthorized()): ?>
                        <div class="sci-contact__field">
                            <label class="sci-contact__label">E-mail
                                <input type="email"
                                       data-prop="ORDER_PROP_32"
                                       name="sci-contact__ur-email"
                                       id="sci-contact__ur-email"
                                       class="sci-contact__input js-email-field"
                                       placeholder="Введите e-mail"
                                       required>
                            </label>
                        </div>
                        <div class="sci-contact__field js-password-block dsb-hidden">
                            <label class="sci-contact__label">Пароль
                                <input type="password"
                                       name="sci-contact__ur-password"
                                       id="sci-contact__ur-password"
                                       class="sci-contact__input js-email-field"
                                       placeholder="Введите e-mail">
                            </label>
                            <button class="shopcart-sidebar__button js-auth" type="button">
                                Войти
                            </button>
                        </div>
                    <? endif; ?>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">Юридическое название
                      <input type="text"
                             data-prop="ORDER_PROP_5"
                             name="sci-contact__ur-legal-name"
                             id="sci-contact__ur-legal-name"
                             class="sci-contact__input"
                             placeholder="Введите юридическое название"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">ОГРН
                      <input type="text"
                             data-prop="ORDER_PROP_6"
                             name="sci-contact__ur-ogrn"
                             id="sci-contact__ur-ogrn"
                             class="sci-contact__input"
                             placeholder="Введите ОГРН"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field sci-contact__field--icon sci-contact__field--select">
                    <label class="sci-contact__label">Правовая форма

                      <select data-prop="ORDER_PROP_8"
                              class="sci-contact__input sci-contact__input--appearance"
                              name="sci-contact__ur-legal-norm"
                              id="sci-contact__ur-legal-norm"
                              required
                      >
                        <option selected value="1">ИП</option>
                        <option value="2">ООО</option>
                        <option value="3">ОАО</option>
                        <option value="4">ПАО</option>
                      </select>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">ИНН
                      <input type="text"
                             data-prop="ORDER_PROP_9"
                             name="sci-contact__ur-inn"
                             id="sci-contact__ur-inn"
                             class="sci-contact__input"
                             placeholder="Введите ИНН"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">КПП
                      <input type="text"
                             data-prop="ORDER_PROP_10"
                             name="sci-contact__ur-kpp"
                             id="sci-contact__ur-kpp"
                             class="sci-contact__input"
                             placeholder="Введите КПП"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">Юридический адрес
                      <input type="text"
                             data-prop="ORDER_PROP_11"
                             name="sci-contact__ur-legal-address"
                             id="sci-contact__ur-legal-address"
                             class="sci-contact__input"
                             autocomplete="none"
                             placeholder="Индекс, город, адрес"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">Фактический адрес
                      <input type="text"
                             data-prop="ORDER_PROP_12"
                             name="sci-contact__ur-fact-address"
                             id="sci-contact__ur-fact-address"
                             class="sci-contact__input"
                             autocomplete="none"
                             placeholder="Индекс, город, адрес"
                             required>
                    </label>
                  </div>
                  <h3 class="sci-contact__title sci-contact__title--indent">Банк</h3>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">Наименование
                      <input type="text"
                             data-prop="ORDER_PROP_13"
                             name="sci-contact__bank-name"
                             id="sci-contact__bank-name"
                             class="sci-contact__input"
                             placeholder="Введите наименование"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">Город Банка
                      <input type="text"
                             data-prop="ORDER_PROP_14"
                             name="sci-contact__bank-sity"
                             id="sci-contact__bank-sity"
                             class="sci-contact__input"
                             autocomplete="none"
                             placeholder="Город Банка"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">БИК
                      <input type="text"
                             data-prop="ORDER_PROP_15"
                             name="sci-contact__bank-bik"
                             id="sci-contact__bank-bik"
                             class="sci-contact__input"
                             placeholder="Введите БИК"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label class="sci-contact__label">Номер счета
                      <input type="text"
                             data-prop="ORDER_PROP_16"
                             name="sci-contact__bank-account"
                             id="sci-contact__bank-account"
                             class="sci-contact__input"
                             placeholder="Введите номер счета"
                             required>
                    </label>
                  </div>
                  <div class="sci-contact__field">
                    <label for="sci-contact__contacts" class="sci-contact__label">Контакты
                    </label>
                    <textarea data-prop="ORDER_PROP_17"
                              name="sci-contact__contacts"
                              id="sci-contact__contacts"
                              class="sci-contact__input sci-contact__input--area"
                              placeholder="Контакты">
                  </textarea>
                  </div>
                </section>
              </div>
            </div>
            <div class="sci-contact__button-wrapper">
              <a class="sci-contact__button-back js-shopcart-back" href="#" aria-label="Вернуться назад">
                Вернуться в корзину
              </a>
              <a class="sci-contact__button-next js-shopcart-next" href="#">
                К доставке
              </a>
            </div>
          </section>
          <!-- Корзина - Доставка -->
          <section class="shopcart-item" id="shopcart-item3">
            <!-- <h2>Корзина - Доставка</h2> -->
            <div class="sci-delivery">
              <!-- Табы -->
              <ul class="sci-delivery-tabs">
                <li class="sci-delivery-tab">
                  <input class="sci-delivery__radio visually-hidden" id="sci-delivery-tab1" type="radio" name="delivery-tabs">
                  <label data-prop="ID_DELIVERY_ID_5" class="sci-delivery__tab rb_so" for="sci-delivery-tab1">
                    Курьером manom.ru
                    <span>1-2 дня, от 350 ₽</span>
                  </label>
                  <section class="sci-delivery-content" id="sci-delivery-content1">
                    <div class="sci-contact__field sci-contact__field--icon sci-contact__field--location">
                      <label class="sci-contact__label">Город
                        <input data-city-prop-alt="ORDER_PROP_25"
                               data-city-prop-val-alt="ORDER_PROP_25_val"
                               data-city-prop="ORDER_PROP_18"
                               data-city-prop-val="ORDER_PROP_18_val"
                               name="so_city_val"
                               id="so_city_val"
                               class="sci-contact__input"
                               type="text"
                               placeholder="Введите город доставки"
                               autocomplete="none"
                               onfocus="loc_sug_CheckThisAlt(this, this.id);">
                        <input type="hidden" name="so_city" id="so_city" value="">
                        <script type="text/javascript">

                          if (typeof oObject != "object") {
                            window.oObject = {};
                          }

                          document.loc_sug_CheckThisAlt = function(oObj, id) {
                            try {
                              if (SuggestLoadedSale) {
                                window.oObject[oObj.id] = new JsSuggestSale(oObj, 'siteId:s1', '', '', '');
                                return;
                              }
                              else {
                                setTimeout(loc_sug_CheckThis(oObj, id), 10);
                              }
                            }
                            catch (e) {
                              setTimeout(loc_sug_CheckThis(oObj, id), 10);
                            }
                          }

                          clearLocInput = function() {
                            var inp = BX("so_city_val");
                            if (inp) {
                              inp.value = "";
                              inp.focus();
                            }
                          }
                        </script>
                      </label>
                    </div>
                    <div class="sci-contact__field">
                      <label class="sci-contact__label">Адрес
                        <input data-prop="ORDER_PROP_36"
                               data-prop-alt="ORDER_PROP_37"
                               type="text"
                               name="sci-delivery-street"
                               id="sci-delivery-street"
                               class="sci-contact__input js-delivery-street"
                               autocomplete="none"
                               placeholder="Улица, дом, квартира"
                               required>
                      </label>
                    </div>
                    <!--                    <div class="sci-contact__field">-->
                    <!--                      <label class="sci-contact__label">Дом-->
                    <!--                        <input data-prop="ORDER_PROP_22"-->
                    <!--                               type="text"-->
                    <!--                               data-prop-alt="ORDER_PROP_29"-->
                    <!--                               name="sci-delivery-building"-->
                    <!--                               id="sci-delivery-building"-->
                    <!--                               class="sci-contact__input"-->
                    <!--                               placeholder=""-->
                    <!--                               required>-->
                    <!--                      </label>-->
                    <!--                    </div>-->
                    <!--                    <div class="sci-contact__field">-->
                    <!--                      <label class="sci-contact__label">Кв/Офис-->
                    <!--                        <input data-prop="ORDER_PROP_23"-->
                    <!--                               type="text"-->
                    <!--                               data-prop-alt="ORDER_PROP_30"-->
                    <!--                               name="sci-delivery-apartment"-->
                    <!--                               id="sci-delivery-apartment"-->
                    <!--                               class="sci-contact__input"-->
                    <!--                               placeholder=""-->
                    <!--                               required>-->
                    <!--                      </label>-->
                    <!--                    </div>-->
                    <!--                    <input type="hidden" data-prop="egor" name="" value="">-->
                    <div class="sci-contact__row">
                      <div class="sci-contact__field sci-contact__field--icon sci-contact__field--calendar">
                        <label class="sci-contact__label">Дата доставки
                          <input data-prop="ORDER_PROP_19"
                                 data-prop-alt="ORDER_PROP_26"
                                 type="text"
                                 name="sci-delivery-date"
                                 id="sci-delivery-date"
                                 class="sci-contact__input js-shopcart-datepicker"
                                 placeholder="Введите дату доставки">
                        </label>
                      </div>
                      <div class="sci-contact__field sci-contact__field--icon sci-contact__field--select">
                        <label class="sci-contact__label">Время доставки
                          <!-- <input data-change="Y" data-prop="ORDER_PROP_21" data-prop-alt="ORDER_PROP_28" type="text" name="sci-delivery-time" id="sci-delivery-time" class="sci-delivery__input" placeholder=""> -->
                          <select data-prop="ORDER_PROP_21"
                                  data-prop-alt="ORDER_PROP_28"
                                  name="sci-delivery-time"
                                  id="sci-delivery-time"
                                  class="sci-contact__input sci-contact__input--appearance"
                                  required>
                            <option selected value="1">
                              c 6:00 до 9:00
                            </option>
                            <option value="2">
                              c 9:00 до 12:00
                            </option>
                            <option value="3">
                              c 12:00 до 15:00
                            </option>
                            <option value="4">
                              c 15:00 до 18:00
                            </option>
                            <option value="5">
                              c 18:00 до 21:00
                            </option>
                          </select>
                        </label>
                      </div>
                    </div>
                    <div class="sci-contact__field">
                      <label for="sci-contact__label" class="sci-contact__label sci-contact__label--desc">Комментарий для курьера</label>
                      <textarea data-prop="ORDER_PROP_24"
                                data-prop-alt="ORDER_PROP_31"
                                name="sci-delivery__comment"
                                id="sci-delivery__comment"
                                class="sci-contact__input sci-delivery__area"
                                placeholder="Ваш комментарий"
                                rows="3"></textarea>
                    </div>
                    <!-- <span class="sci-delivery__price pickup_summ_alt">
                        Стоимость доставки: <span>не известно</span>
                    </span> -->
                  </section>
                </li>
                <li class="sci-delivery-tab">
                  <input class="sci-delivery__radio visually-hidden" id="sci-delivery-tab2" type="radio" name="delivery-tabs">
                  <label data-prop="ID_DELIVERY_ID_6" class="sci-delivery__tab rb_so" for="sci-delivery-tab2">
                    Курьером СДЭК
                    <span>1-2 дня, от 350 ₽</span>
                  </label>
                  <section class="sci-delivery-content" id="sci-delivery-content2">
                    <!-- <div class="sci-contact__field sci-contact__field--icon sci-contact__field--location">
                      <label class="sci-contact__label">Город
                        <input data-city-prop-alt="ORDER_PROP_25"
                               data-city-prop-val-alt="ORDER_PROP_25_val"
                               data-city-prop="ORDER_PROP_18"
                               data-city-prop-val="ORDER_PROP_18_val"
                               name="so_city_alt_val"
                               id="so_city_alt_val"
                               value=""
                               class="sci-contact__input"
                               type="text"
                               autocomplete="off"
                               placeholder="Введите город доставки"
                               onfocus="loc_sug_CheckThisAltAlt(this, this.id);">
                        <input type="hidden" name="so_city_alt" id="so_city_alt" value="">
                        <script type="text/javascript">

                          if (typeof oObject != "object") {
                            window.oObject = {};
                          }

                          document.loc_sug_CheckThisAltAlt = function(oObj, id) {
                            try {
                              if (SuggestLoadedSale) {
                                window.oObject[oObj.id] = new JsSuggestSale(oObj, 'siteId:s1', '', '', '');
                                return;
                              }
                              else {
                                setTimeout(loc_sug_CheckThis(oObj, id), 10);
                              }
                            }
                            catch (e) {
                              setTimeout(loc_sug_CheckThis(oObj, id), 10);
                            }
                          }

                          clearLocInput = function() {
                            var inp = BX("so_city_val_alt");
                            if (inp) {
                              inp.value = "";
                              inp.focus();
                            }
                          }
                        </script>
                      </label>
                    </div>
                    <div class="sci-contact__field">
                      <label class="sci-contact__label">Адрес
                        <input data-prop="ORDER_PROP_20"
                               data-prop-alt="ORDER_PROP_27"
                               type="text"
                               name="sci-delivery-street"
                               id="sci-delivery-street"
                               class="sci-contact__input"
                               placeholder="Улица, дом, квартира"
                               required>
                      </label>
                    </div>
                    <div class="sci-contact__row">
                      <div class="sci-contact__field sci-contact__field--icon sci-contact__field--calendar">
                        <label class="sci-contact__label">Дата доставки
                          <input data-change="Y"
                                 data-prop="ORDER_PROP_19"
                                 data-prop-alt="ORDER_PROP_26"
                                 type="text"
                                 name="sci-delivery-date"
                                 id="sci-delivery-date"
                                 class="sci-contact__input"
                                 placeholder="Введите дату доставки">
                        </label>
                      </div>
                      <div class="sci-contact__field sci-contact__field--icon sci-contact__field--select">
                        <label class="sci-contact__label">Время доставки
                          <select data-prop="ORDER_PROP_21"
                                  data-prop-alt="ORDER_PROP_28"
                                  name="sci-delivery-time"
                                  id="sci-delivery-time"
                                  class="sci-contact__input sci-contact__input--appearance"
                                  required>
                            <option selected value="c 6:00 до 9:00">
                              c 6:00 до 9:00
                            </option>
                            <option value="c 9:00 до 12:00">
                              c 9:00 до 12:00
                            </option>
                            <option value="c 12:00 до 15:00">
                              c 12:00 до 15:00
                            </option>
                            <option value="c 15:00 до 18:00">
                              c 15:00 до 18:00
                            </option>
                            <option value="c 18:00 до 21:00">
                              c 18:00 до 21:00
                            </option>
                          </select>
                        </label>
                      </div>
                    </div>
                    <div class="sci-contact__field">
                      <label for="sci-contact__label" class="sci-contact__label sci-contact__label--desc">Комментарий для курьера</label>
                      <textarea data-prop="ORDER_PROP_24"
                                data-prop-alt="ORDER_PROP_31"
                                name="sci-delivery__comment"
                                id="sci-delivery__comment"
                                class="sci-contact__input sci-delivery__area"
                                placeholder="Ваш комментарий"
                                rows="3"></textarea>
                    </div>
                    <span class="sci-delivery__price pickup_summ">
                      Стоимость доставки: <span>не известно</span>
                    </span> -->
                    <!--                    <span class="sci-delivery__time pickup_date">-->
                    <!--                      Время доставки: <span>не известно</span>-->
                    <!--                    </span>-->
                    <div class="sci-contact__field sci-contact__field--icon sci-contact__field--location">
                      <label class="sci-contact__label">Город
                        <input data-city-prop-alt="ORDER_PROP_25"
                               data-city-prop-val-alt="ORDER_PROP_25_val"
                               data-city-prop="ORDER_PROP_18"
                               data-city-prop-val="ORDER_PROP_18_val"
                               name="so_city_alt_val"
                               id="so_city_alt_val"
                               value=""
                               class="sci-contact__input"
                               type="text"
                               autocomplete="none"
                               placeholder="Введите город"
                               onfocus="loc_sug_CheckThisAltAlt(this, this.id);">
                        <input type="hidden" name="so_city_alt" id="so_city_alt" value="">
                        <script type="text/javascript">

                          if (typeof oObject != "object") {
                            window.oObject = {};
                          }

                          document.loc_sug_CheckThisAltAlt = function(oObj, id) {
                            try {
                              if (SuggestLoadedSale) {
                                window.oObject[oObj.id] = new JsSuggestSale(oObj, 'siteId:s1', '', '', '');
                                return;
                              }
                              else {
                                setTimeout(loc_sug_CheckThis(oObj, id), 10);
                              }
                            }
                            catch (e) {
                              setTimeout(loc_sug_CheckThis(oObj, id), 10);
                            }
                          }

                          clearLocInput = function() {
                            var inp = BX("so_city_val_alt");
                            if (inp) {
                              inp.value = "";
                              inp.focus();
                            }
                          }
                        </script>
                      </label>
                    </div>
                    <div class="sci-contact__field">
                      <div class="pickup_address sci-contact__label">
                        Пункт самовывоза
                        <span class="sci-delivery__text">
                          Не выбран
                        </span>
                      </div>
                    </div>

                    <button class="sci-delivery__choice shopcart-sidebar__back-shopping" id="soDelivPopUp" type="button">
                      Выбрать пункт самовывоза
                    </button>
                  </section>
                </li>
                <!-- <li class="sci-delivery-tab">
                  <input class="sci-delivery__radio visually-hidden" id="sci-delivery-tab3" type="radio" name="delivery-tabs">
                  <label class="sci-delivery__tab rb_so" for="sci-delivery-tab3">
                    Самовывоз СДЭК
                    <span>1-2 дня, от 350 ₽</span>
                  </label>
                  <section class="sci-delivery-content" id="sci-delivery-content3">
                    <div class="sci-contact__field sci-contact__field--icon sci-contact__field--location">
                      <label class="sci-contact__label">Город
                        <input data-city-prop-alt="ORDER_PROP_25"
                               data-city-prop-val-alt="ORDER_PROP_25_val"
                               data-city-prop="ORDER_PROP_18"
                               data-city-prop-val="ORDER_PROP_18_val"
                               name="so_city_alt_val"
                               id="so_city_alt_val"
                               value=""
                               class="sci-contact__input"
                               type="text"
                               autocomplete="off"
                               placeholder="Введите город"
                               onfocus="loc_sug_CheckThisAltAlt(this, this.id);">
                        <input type="hidden" name="so_city_alt" id="so_city_alt" value="">
                        <script type="text/javascript">

                          if (typeof oObject != "object") {
                            window.oObject = {};
                          }

                          document.loc_sug_CheckThisAltAlt = function(oObj, id) {
                            try {
                              if (SuggestLoadedSale) {
                                window.oObject[oObj.id] = new JsSuggestSale(oObj, 'siteId:s1', '', '', '');
                                return;
                              }
                              else {
                                setTimeout(loc_sug_CheckThis(oObj, id), 10);
                              }
                            }
                            catch (e) {
                              setTimeout(loc_sug_CheckThis(oObj, id), 10);
                            }
                          }

                          clearLocInput = function() {
                            var inp = BX("so_city_val_alt");
                            if (inp) {
                              inp.value = "";
                              inp.focus();
                            }
                          }
                        </script>
                      </label>
                    </div>
                    <div class="sci-contact__field">
                      <div class="pickup_address sci-contact__label">
                        Пункт самовывоза
                        <span class="sci-delivery__text">
                          Не выбран
                        </span>
                      </div>
                    </div>

                    <button class="sci-delivery__choice shopcart-sidebar__back-shopping" id="soDelivPopUp" type="button">
                      Выбрать пункт самовывоза
                    </button>
                  </section>
                </li> -->
                <li class="sci-delivery-tab">
                  <input class="sci-delivery__radio visually-hidden" id="sci-delivery-tab3" type="radio" name="delivery-tabs">
                  <label data-prop="ID_DELIVERY_ID_13" class="sci-delivery__tab rb_so" for="sci-delivery-tab3">
                    Самовывоз из магазина
                    <span>1-2 дня, от 350 ₽</span>
                  </label>
                </li>
              </ul>
            </div>
            <div class="sci-contact__button-wrapper">
              <a class="sci-contact__button-back js-shopcart-back" href="#" title="Контактные данные">
                Контактные данные
              </a>
              <a class="sci-contact__button-next js-shopcart-next" href="#" title="К доставке">
                К оплате
              </a>
            </div>
          </section>
          <!-- Корзина - Оплата -->
          <section class="shopcart-item" id="shopcart-item4">
            <div class="sci-payment">
              <div class="sci-contact__top">
                <h2 class="sci-contact__title">Выберите способ оплаты</h2>
              </div>
              <ul class="sci-payment-tabs">
                <!-- <li class="sci-payment-tab">
                  <input id="sci-payment-tab1"
                         class="sci-payment__radio visually-hidden"
                         type="radio"
                         name="payment-tabs"
                         value="1"
                         checked>
                  <label class="sci-payment__tab rb_so"
                         data-prop="ID_PAY_SYSTEM_ID_6"
                         for="sci-payment-tab1">
                    Наличными при получении

                    <i class="sci-payment__icon">
                      <svg viewBox="0 0 27 18" width="25" height="16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.67 1H4.33M26 17H1V3.9h25V17zm-9.17-6.74c0 1.86-1.49 3.37-3.33 3.37a3.35 3.35 0 0 1-3.33-3.37c0-1.86 1.49-3.37 3.33-3.37s3.33 1.51 3.33 3.37z"
                              stroke="currentColor"
                              stroke-width="1.5"/>
                      </svg>
                    </i>
                  </label>
                </li>
                <li class="sci-payment-tab">
                  <input id="sci-payment-tab2"
                         class="sci-payment__radio visually-hidden"
                         type="radio"
                         name="payment-tabs"
                         value="2">
                  <label class="sci-payment__tab rb_so"
                         data-prop="ID_PAY_SYSTEM_ID_4"
                         for="sci-payment-tab2">
                    Банковской картой при получении
                    <i class="sci-payment__icon">
                      <svg viewBox="0 0 30 24" width="28" height="22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.9 8.1v7.24a2 2 0 0 0 2 2H27a2
                       2 0
                      0 0
                      2-2V8
                      .1m-22.1 0H29m-22.1 0V5.55M29 8.1V5.55m-22.1 0V3.5a2 2 0 0 1 2-2H27a2 2 0 0 1 2 2v2.05m-22.1 0H29"
                              stroke="currentColor"
                              stroke-width="1.5"/>
                        <circle cx="6.89" cy="17.34" r="5.89" fill="#FEFFFD" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M4.32 17.5h.84v-3.1H7.1c.8 0 1.38.17 1.74.5.37.34.56.82.56 1.42s-.19 1.07-.57 1.42c-.37.34-.94.51-1.73.51H6v.6h2.08v.65H6v1.08h-.83V19.5h-.84v-.66h.84v-.59h-.84v-.74zm1.67-2.34v2.35H7.1c.53 0 .9-.1 1.12-.3.21-.19.32-.48.32-.88 0-.39-.11-.68-.33-.87-.22-.2-.59-.3-1.1-.3H5.98z"
                              fill="currentColor"/>
                      </svg>
                    </i>
                  </label>
                </li>
                <li class="sci-delivery-tab">
                  <input id="sci-payment-tab3"
                         class="sci-delivery__radio visually-hidden"
                         type="radio"
                         name="payment-tabs"
                         value="3">
                  <label class="sci-delivery__tab sci-payment__tab"
                         data-prop="ID_PAY_SYSTEM_ID_4"
                         for="sci-payment-tab3">
                    Оплата картой онлайн
                    <i class="sci-payment__icon">
                      <svg viewBox="0 0 26 26" width="26" height="26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.14 9.16H9.06l1.3-8.09h2.08l-1.3 8.09zm7.55-7.89a5.11 5.11 0 0 0-1.87-.35c-2.06 0-3.5 1.1-3.51 2.68-.02 1.16 1.03 1.8 1.82 2.2.8.39 1.08.65 1.08 1 0 .54-.65.8-1.25.8-.83 0-1.28-.14-1.95-.44l-.28-.13-.29 1.82c.49.22 1.39.42 2.32.43 2.19 0 3.61-1.08 3.63-2.76 0-.92-.55-1.63-1.75-2.2-.73-.37-1.17-.62-1.17-1 0-.35.37-.7 1.2-.7.67-.02 1.17.15 1.55.31l.18.09.29-1.75zm2.77 5.03l.83-2.27.27-.77.15.7.48 2.33h-1.73zm2.57-5.23h-1.61c-.5 0-.88.15-1.1.67l-3.09 7.42h2.19l.44-1.21h2.67l.25 1.2h1.93l-1.68-8.08zm-16.71 0L5.28 6.6l-.22-1.12a6.18 6.18 0 0 0-2.88-3.4l1.86 7.08h2.2l3.28-8.08h-2.2z"
                              fill="#00579F"/>
                        <path d="M3.38 1.07H.03L0 1.23c2.61.68 4.34 2.3 5.06 4.24l-.73-3.72c-.12-.52-.5-.66-.95-.68z" fill="#FAA61A"/>
                        <path d="M17.17 14.77h-5.6v9.89h5.6v-9.89z" fill="#FF5F00"/>
                        <path d="M11.92 19.71c0-1.93.9-3.75 2.44-4.94a6.49 6.49 0 0 0-8.99 1.06 6.2 6.2 0 0 0 1.08 8.83 6.5 6.5 0 0 0 7.91 0 6.23 6.23 0 0 1-2.44-4.95z"
                              fill="#EB001B"/>
                        <path d="M24.73 19.71a6.35 6.35 0 0 1-6.4 6.29 6.48 6.48 0 0 1-3.96-1.34 6.2 6.2 0 0 0 0-9.89 6.49 6.49 0 0 1 8.99 1.06 6.2 6.2 0 0 1 1.37 3.88z"
                              fill="#F79E1B"/>
                      </svg>
                    </i>
                  </label>
                </li>
                <li class="sci-delivery-tab">
                  <input id="sci-payment-tab4"
                         class="sci-delivery__radio visually-hidden"
                         type="radio"
                         name="payment-tabs"
                         value="4">
                  <label class="sci-delivery__tab sci-payment__tab"
                         data-prop="ID_PAY_SYSTEM_ID_2"
                         for="sci-payment-tab4">
                    Яндекс.Деньги
                    <i class="sci-payment__icon">
                      <svg viewBox="0 0 19 26" width="19" height="26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 12.65c0-1.38.12-2.27 3.02-4.4C5.42 6.46 13.14.4 13.14.4v10.13H19V25.1H1.83c-1 0-1.83-.82-1.83-1.81V12.65z" fill="#F5CA44"/>
                        <path d="M13.14 10.53v5.86L2.36 23.65 16 19.27v-8.74h-2.85z" fill="#BE9E34"/>
                        <path d="M8.21 10.22c.63-.74 1.55-1 2.06-.58.5.42.4 1.36-.23 2.1s-1.55 1-2.05.58c-.5-.41-.4-1.36.22-2.1z" fill="#000"/>
                      </svg>
                    </i>
                  </label>
                </li>
                <li class="sci-delivery-tab">
                  <input id="sci-payment-tab5"
                         class="sci-delivery__radio visually-hidden"
                         type="radio"
                         name="payment-tabs"
                         value="5">
                  <label class="sci-delivery__tab sci-payment__tab"
                         data-prop="ID_PAY_SYSTEM_ID_2"
                         for="sci-payment-tab5">
                    Безналичный расчет для юр. лиц
                    <i class="sci-payment__icon">
                      <svg viewBox="0 0 26 23" width="24" height="21" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 12.67V20c0 1.1.9 2 2 2h20a2
  2 0 0 0 2-2v-7.33m-24 0V7.44c0-1.1.9-2 2-2h4m-6 7.23h9.2m14.8 0V7.44a2 2 0 0 0-2-2h-4.4m6.4 7.23h-9.6M7 5.44V3c0-1.1.9-2 2-2h7.6a2 2 0 0 1 2 2v2.44M7 5.44h11.6m-3.2 8.3v-2.05a1 1 0 0 0-1-1h-3.2a1 1 0 0 0-1 1v2.04a1 1 0 0 0 1 1h3.2a1 1 0 0 0 1-1z"
                              stroke="currentColor"
                              stroke-width="1.5"/>
                      </svg>
                    </i>
                  </label>
                </li> -->
              </ul>
              <div class="sci-payment__button">
                <button id="btnSubmitOrder" class="shopcart-sidebar__button" type="button">Оформить заказ</button>
              </div>
              <div class="sci-contact__field sci-payment__consent">
                <label class="sci-contact__consent">
                  <input class="visually-hidden sci-contact__checkbox js-shopcart-agree"
                         type="checkbox"
                         name="sci-contact__consent"
                         value="sci-contact__consent"
                         required
                         checked>
                  <span class="sci-contact__check">
                    Нажимая «Оформить заказ» вы даете согласие на хранение и обработку ваших персональных данных в соответствии <a target="_blank" href="/public_offer_agreement/">с условиями</a>.
                  </span>
                </label>
              </div>
            </div>
          </section>
        </div>
        <div class="shopcart-sidebar">
          <div class="shopcart-sidebar__wrapper">

            <div class="sci-contact__top sci-contact__top--order">
              <h2 class="shopcart-sidebar__title">Состав заказа</h2>
              <a class="shopcart-sidebar__link js-shopcart-cart-link" href="/cart/">Изменить</a>
            </div>

            <!-- <div class="promo-code shopcart-sidebar__code">
              <form action="#" method="post">
                <input
                  class="promo-code__field"
                  type="text"
                  name="promo-code"
                  placeholder="Промокод на скидку"
                >
                <button class="promo-code__button" type="submit">Применить</button>
              </form>
            </div> -->
            <div class="shopcart-sidebar__check">
							<? $APPLICATION->IncludeComponent(
								"bitrix:sale.basket.basket.line",
								"cart_info",
								[
									"HIDE_ON_BASKET_PAGES" => "Y",  // Не показывать на страницах корзины и оформления заказа
									"PATH_TO_BASKET"       => SITE_DIR . "personal/cart/",  // Страница корзины
									"PATH_TO_ORDER"        => SITE_DIR . "personal/order/make/",  // Страница оформления заказа
									"PATH_TO_PERSONAL"     => SITE_DIR . "personal/",  // Страница персонального раздела
									"PATH_TO_PROFILE"      => SITE_DIR . "personal/",  // Страница профиля
									"PATH_TO_REGISTER"     => SITE_DIR . "login/",  // Страница регистрации
									"POSITION_FIXED"       => "Y",  // Отображать корзину поверх шаблона
									"POSITION_HORIZONTAL"  => "right",  // Положение по горизонтали
									"POSITION_VERTICAL"    => "top",  // Положение по вертикали
									"SHOW_AUTHOR"          => "Y",  // Добавить возможность авторизации
									"SHOW_DELAY"           => "N",  // Показывать отложенные товары
									"SHOW_EMPTY_VALUES"    => "Y",  // Выводить нулевые значения в пустой корзине
									"SHOW_IMAGE"           => "Y",  // Выводить картинку товара
									"SHOW_NOTAVAIL"        => "N",  // Показывать товары, недоступные для покупки
									"SHOW_NUM_PRODUCTS"    => "Y",  // Показывать количество товаров
									"SHOW_PERSONAL_LINK"   => "N",  // Отображать персональный раздел
									"SHOW_PRICE"           => "Y",  // Выводить цену товара
									"SHOW_PRODUCTS"        => "Y",  // Показывать список товаров
									"SHOW_SUMMARY"         => "Y",  // Выводить подытог по строке
									"SHOW_TOTAL_PRICE"     => "Y",  // Показывать общую сумму по товарам
								],
								false
							); ?>
							<? global $cartPrice; ?>
              <div class="shopcart-sidebar__info shopcart-sidebar__info--contacts">
                <h3 class="shopcart-sidebar__title">Получатель:</h3>
                <p class="shopcart-sidebar__text">
                  <span class="shopcart-sidebar__buyer-fio"></span>
                </p>
                <p class="shopcart-sidebar__text">
                  <span class="shopcart-sidebar__buyer-email"></span>
                </p>
                <p class="shopcart-sidebar__text">
                  <span class="shopcart-sidebar__buyer-tel"></span>
                </p>
              </div>
              <div class="shopcart-sidebar__info shopcart-sidebar__info--delivery sc-hidden">
                <h3 class="shopcart-sidebar__title">Доставка:</h3>
                <p class="shopcart-sidebar__text">
                  <span class="shopcart-sidebar__delivery-city"></span>, <span class="shopcart-sidebar__delivery-address"></span>
                </p>
                <p class="shopcart-sidebar__text">
                  <span class="shopcart-sidebar__delivery-date"><span></span></span><span class="shopcart-sidebar__delivery-time">, <span></span></span>
                </p>
                <? /*
                <p class="shopcart-sidebar__text shopcart-sidebar__delivery-time">Срок доставки: <span>2-3 дня</span></p>
                */ ?>
              </div>
              <div class="shopcart-sidebar__total">
                <div class="shopcart-sidebar__price-wrapper">
                  <p class="shopcart-sidebar__sum-price">
                    Стоимость
                    <span>
                      <span id="cart-price">512 940</span> ₽
                    </span>
                  </p>
                  <p class="shopcart-sidebar__sum-price shopcart-sidebar__sum-price--sale sc-hidden">
                    Скидка
                    <span>
                      <span id="cart-discount-price">0</span> ₽
                    </span>
                  </p>
                  <p class="shopcart-sidebar__sum-price shopcart-sidebar__sum-price--delivery sc-hidden">
                    Доставка
                    <span>
                      <span id="cart-delivery-price">0</span> ₽
                    </span>
                  </p>
                </div>
                <p class="shopcart-sidebar__sum-price shopcart-sidebar__sum-price--total">
                  Итого:
                  <span>
                    <span id="total_price_cart"><?= $cartPrice; ?></span> ₽
                  </span>
                </p>
              </div>
            </div>

            <div class="shopcart-sidebar__button-wrapper">
              <a class="shopcart-sidebar__button js-shopcart-next" href="#" title="Оформить заказ">Оформить заказ</a>
              <!--        <div class="shopcart-sidebar__button hidden" id="btnSubmitOrder">Оформить заказ</div>-->
              <!--        <div class="shopcart-sidebar__back-shopping BOC_btn" data-fancybox="" data-src="#popap-buy-one-click-cart" href="javascript:;">Купить в 1 клик</div>-->
            </div>
          </div>
          <div class="shopcart-sidebar__back-wrapper">
            <a class="shopcart-sidebar__back-shopping" href="/catalog/" title="Вернуться к покупкам">Вернуться к покупкам</a>
          </div>
        </div>
      </div>

		<? else: ?>
      <section class="shopcart-success">
        <h2 class="shopcart-success__title">
          Ваш заказ #<?= $_REQUEST['ORDER_ID'] ?> успешно оформлен
        </h2>
        <p class="shopcart-success__text">
          В ближайшее время с вами свяжется наш менеджер для дальнейшего подверждения заказа.
        </p>
        <a class="shopcart-success__button" href="/">Класс, спасибо!</a>
      </section>
		<? endif ?>
  </div>
</main>
</div>
<?
$template = 'old_version';
if ($_REQUEST['ORDER_ID']) {
	$template = 'manom1';
}
?>

<div id="module_so" style="display:none;">
  <!-- <div id="module_so" style=""> -->
	<? $APPLICATION->IncludeComponent(
		"bitrix:sale.order.ajax",
		$template,
		[
			"ADDITIONAL_PICT_PROP_8"         => "-",
			"ALLOW_AUTO_REGISTER"            => "Y",
			"ALLOW_NEW_PROFILE"              => "N",
			"ALLOW_USER_PROFILES"            => "Y",
			"BASKET_IMAGES_SCALING"          => "standard",
			"BASKET_POSITION"                => "before",
			"COMPATIBLE_MODE"                => "Y",
			"DELIVERIES_PER_PAGE"            => "8",
			"DELIVERY_FADE_EXTRA_SERVICES"   => "Y",
			"DELIVERY_NO_AJAX"               => "Y",
			"DELIVERY_NO_SESSION"            => "Y",
			"DELIVERY_TO_PAYSYSTEM"          => "d2p",
			"DISABLE_BASKET_REDIRECT"        => "N",
			"MESS_DELIVERY_CALC_ERROR_TEXT"  => "Вы можете продолжить оформление заказа, а чуть позже менеджер магазина свяжется с вами и уточнит информацию по доставке.",
			"MESS_DELIVERY_CALC_ERROR_TITLE" => "Не удалось рассчитать стоимость доставки.",
			"MESS_FAIL_PRELOAD_TEXT"         => "Вы заказывали в нашем интернет-магазине, поэтому мы заполнили все данные автоматически.Обратите внимание на развернутый блок с информацией о заказе. Здесь вы можете внести необходимые изменения или оставитькак есть и нажать кнопку \"#ORDER_BUTTON#\".",
			"MESS_SUCCESS_PRELOAD_TEXT"      => "Вы заказывали в нашем интернет-магазине, поэтому мы заполнили все данныеавтоматически. Если все заполнено верно, нажмите кнопку \"#ORDER_BUTTON#\".",
			"ONLY_FULL_PAY_FROM_ACCOUNT"     => "N",
			"PATH_TO_AUTH"                   => "/auth/",
			"PATH_TO_BASKET"                 => "basket.php",
			"PATH_TO_PAYMENT"                => "payment.php",
			"PATH_TO_PERSONAL"               => "index.php",
			"PAY_FROM_ACCOUNT"               => "Y",
			"PAY_SYSTEMS_PER_PAGE"           => "8",
			"PICKUPS_PER_PAGE"               => "5",
			"PRODUCT_COLUMNS_HIDDEN"         => "",
			"PRODUCT_COLUMNS_VISIBLE"        => [
				0 => "PREVIEW_PICTURE",
				1 => "PROPS",
                2 => "PROPERTY_this_prod_model",
			],
			"PROPS_FADE_LIST_1"              => [
				0 => "19",
			],
			"SEND_NEW_USER_NOTIFY"           => "Y",
			"SERVICES_IMAGES_SCALING"        => "standard",
			"SET_TITLE"                      => "Y",
			"SHOW_BASKET_HEADERS"            => "N",
			"SHOW_COUPONS_BASKET"            => "Y",
			"SHOW_COUPONS_DELIVERY"          => "Y",
			"SHOW_COUPONS_PAY_SYSTEM"        => "Y",
			"SHOW_DELIVERY_INFO_NAME"        => "Y",
			"SHOW_DELIVERY_LIST_NAMES"       => "Y",
			"SHOW_DELIVERY_PARENT_NAMES"     => "Y",
			"SHOW_MAP_IN_PROPS"              => "N",
			"SHOW_NEAREST_PICKUP"            => "N",
			"SHOW_ORDER_BUTTON"              => "final_step",
			"SHOW_PAY_SYSTEM_INFO_NAME"      => "Y",
			"SHOW_PAY_SYSTEM_LIST_NAMES"     => "Y",
			"SHOW_STORES_IMAGES"             => "Y",
			"SHOW_TOTAL_ORDER_BUTTON"        => "Y",
			"SHOW_VAT_PRICE"                 => "Y",
			"SKIP_USELESS_BLOCK"             => "Y",
			"TEMPLATE_LOCATION"              => "popup",
			"TEMPLATE_THEME"                 => "site",
			"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
			"USE_CUSTOM_ERROR_MESSAGES"      => "Y",
			"USE_CUSTOM_MAIN_MESSAGES"       => "N",
			"USE_PREPAYMENT"                 => "N",
			"USE_YM_GOALS"                   => "N",
			"USER_CONSENT"                   => "Y",
			"USER_CONSENT_ID"                => "0",
			"USER_CONSENT_IS_CHECKED"        => "Y",
			"USER_CONSENT_IS_LOADED"         => "N",
			"COMPONENT_TEMPLATE"             => "old_version",
			"ALLOW_APPEND_ORDER"             => "Y",
			"SHOW_NOT_CALCULATED_DELIVERIES" => "L",
			"SPOT_LOCATION_BY_GEOIP"         => "Y",
			"USE_PRELOAD"                    => "Y",
			"SHOW_PICKUP_MAP"                => "Y",
			"PICKUP_MAP_TYPE"                => "yandex",
			"PROPS_FADE_LIST_2"              => "",
			"ACTION_VARIABLE"                => "soa-action",
			"USE_PHONE_NORMALIZATION"        => "Y",
			"ADDITIONAL_PICT_PROP_6"         => "MORE_PHOTO",
			"ADDITIONAL_PICT_PROP_7"         => "MORE_PHOTO",
			"USE_ENHANCED_ECOMMERCE"         => "N",
			"MESS_PAY_SYSTEM_PAYABLE_ERROR"  => "Вы сможете оплатить заказ после того, как менеджер проверит наличие полного комплекта товаров на складе. Сразу после проверки вы получите письмо с инструкциями по оплате. Оплатить заказ можно будет в персональном разделе сайта.",
		],
		false
	); ?>
  <div id="popap-buy-one-click-cart" class="popap-login">
    <h3 class="sci-login__title">Купить в один клик</h3>
    <form class="sci-login__form">
      <div class="form_msg">tesr</div>
      <input type="hidden" name="form_id" value="3">
      <label class="sci-login__label" for="sci-login__name_alt">Имя</label>
      <input type="text" name="name" id="sci-login__name_alt" class="sci-login__input" placeholder="Ваше имя" required>
      <label class="sci-login__label" for="sci-login__tel_alt">Телефон</label>
      <input type="tel" name="phone" id="sci-login__tel_alt" class="sci-login__input" placeholder="+7 (___) ___-__-__" required>
      <label class="sci-login__label" for="sci-login__tel">E-mail</label>
      <input type="email" name="email" id="sci-login__tel" class="sci-login__input" placeholder="E-mail" required>
      <button class="sci-login__button">Отправить</button>
    </form>
  </div>

	</div>
</div>

<script id="tmpl-payment-icon-cash" type="x-tmpl-mustache">
  <svg class="sci-payment__svg sci-payment__svg--cash" viewBox="0 0 27 18" width="25" height="16" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M22.67 1H4.33M26 17H1V3.9h25V17zm-9.17-6.74c0 1.86-1.49 3.37-3.33 3.37a3.35 3.35 0 0 1-3.33-3.37c0-1.86 1.49-3.37 3.33-3.37s3.33 1.51 3.33 3.37z"
          stroke="currentColor"
          stroke-width="1.5"/>
  </svg>
</script>

<script id="tmpl-payment-icon-card" type="x-tmpl-mustache">
  <svg class="sci-payment__svg sci-payment__svg--card" width="30" height="24" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M6.89478 8.09064L6.89478 15.3421C6.89478 16.4467 7.7902 17.3421 8.89477 17.3421L27 17.3421C28.1046 17.3421 29 16.4467 29 15.3421L29 8.09064M6.89478 8.09064L29 8.09064M6.89478 8.09064V5.55263M29 8.09064V5.55263M6.89478 5.55263V3.5C6.89478 2.39543 7.79021 1.5 8.89478 1.5H27C28.1046 1.5 29 2.39543 29 3.5V5.55263M6.89478 5.55263H29" stroke="currentColor" stroke-width="1.5"/>
    <circle cx="6.89474" cy="17.3421" r="5.89474" fill="#FEFFFD" stroke="currentColor" stroke-width="1.5"/>
    <path d="M4.31592 17.5071H5.15592V14.3947H7.10118C7.89697 14.3947 8.4776 14.5657 8.84308 14.9076C9.21444 15.2436 9.40013 15.7151 9.40013 16.3223C9.40013 16.9177 9.2115 17.3892 8.83423 17.737C8.46287 18.0789 7.88518 18.2499 7.10118 18.2499H5.98708V18.8423H8.07381V19.4966H5.98708V20.5842H5.15592V19.4966H4.31592V18.8423H5.15592V18.2499H4.31592V17.5071ZM5.98708 15.1551V17.5071H7.10118C7.62581 17.5071 7.99718 17.4099 8.21529 17.2153C8.43339 17.0208 8.54244 16.7261 8.54244 16.3311C8.54244 15.9421 8.43044 15.6503 8.20644 15.4558C7.98834 15.2553 7.61992 15.1551 7.10118 15.1551H5.98708Z" fill="currentColor"/>
  </svg>
</script>

<script id="tmpl-payment-icon-visa-master" type="x-tmpl-mustache">
  <svg class="sci-payment__svg sci-payment__svg--visa-master" viewBox="0 0 26 26" width="26" height="26" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M11.14 9.16H9.06l1.3-8.09h2.08l-1.3 8.09zm7.55-7.89a5.11 5.11 0 0 0-1.87-.35c-2.06 0-3.5 1.1-3.51 2.68-.02 1.16 1.03 1.8 1.82 2.2.8.39 1.08.65 1.08 1 0 .54-.65.8-1.25.8-.83 0-1.28-.14-1.95-.44l-.28-.13-.29 1.82c.49.22 1.39.42 2.32.43 2.19 0 3.61-1.08 3.63-2.76 0-.92-.55-1.63-1.75-2.2-.73-.37-1.17-.62-1.17-1 0-.35.37-.7 1.2-.7.67-.02 1.17.15 1.55.31l.18.09.29-1.75zm2.77 5.03l.83-2.27.27-.77.15.7.48 2.33h-1.73zm2.57-5.23h-1.61c-.5 0-.88.15-1.1.67l-3.09 7.42h2.19l.44-1.21h2.67l.25 1.2h1.93l-1.68-8.08zm-16.71 0L5.28 6.6l-.22-1.12a6.18 6.18 0 0 0-2.88-3.4l1.86 7.08h2.2l3.28-8.08h-2.2z"
          fill="#00579F"/>
    <path d="M3.38 1.07H.03L0 1.23c2.61.68 4.34 2.3 5.06 4.24l-.73-3.72c-.12-.52-.5-.66-.95-.68z" fill="#FAA61A"/>
    <path d="M17.17 14.77h-5.6v9.89h5.6v-9.89z" fill="#FF5F00"/>
    <path d="M11.92 19.71c0-1.93.9-3.75 2.44-4.94a6.49 6.49 0 0 0-8.99 1.06 6.2 6.2 0 0 0 1.08 8.83 6.5 6.5 0 0 0 7.91 0 6.23 6.23 0 0 1-2.44-4.95z"
          fill="#EB001B"/>
    <path d="M24.73 19.71a6.35 6.35 0 0 1-6.4 6.29 6.48 6.48 0 0 1-3.96-1.34 6.2 6.2 0 0 0 0-9.89 6.49 6.49 0 0 1 8.99 1.06 6.2 6.2 0 0 1 1.37 3.88z"
          fill="#F79E1B"/>
  </svg>
</script>

<script id="tmpl-payment-icon-ya-money" type="x-tmpl-mustache">
  <svg class="sci-payment__svg sci-payment__svg--ya-money" viewBox="0 0 19 26" width="19" height="26" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0 12.65c0-1.38.12-2.27 3.02-4.4C5.42 6.46 13.14.4 13.14.4v10.13H19V25.1H1.83c-1 0-1.83-.82-1.83-1.81V12.65z" fill="#F5CA44"/>
    <path d="M13.14 10.53v5.86L2.36 23.65 16 19.27v-8.74h-2.85z" fill="#BE9E34"/>
    <path d="M8.21 10.22c.63-.74 1.55-1 2.06-.58.5.42.4 1.36-.23 2.1s-1.55 1-2.05.58c-.5-.41-.4-1.36.22-2.1z" fill="#000"/>
  </svg>
</script>

<script id="tmpl-payment-icon-cashless" type="x-tmpl-mustache">
  <svg class="sci-payment__svg sci-payment__svg--cashless" viewBox="0 0 26 23" width="24" height="21" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M1 12.67V20c0 1.1.9 2 2 2h20a2
  2 0 0 0 2-2v-7.33m-24 0V7.44c0-1.1.9-2 2-2h4m-6 7.23h9.2m14.8 0V7.44a2 2 0 0 0-2-2h-4.4m6.4 7.23h-9.6M7 5.44V3c0-1.1.9-2 2-2h7.6a2 2 0 0 1 2 2v2.44M7 5.44h11.6m-3.2 8.3v-2.05a1 1 0 0 0-1-1h-3.2a1 1 0 0 0-1 1v2.04a1 1 0 0 0 1 1h3.2a1 1 0 0 0 1-1z"
          stroke="currentColor"
          stroke-width="1.5"/>
  </svg>
</script>

<script id="tmpl-shopcart-sidebar-product" type="x-tmpl-mustache">
  <div class="shopcart-sidebar__prod">
    <div class="sci-product__wrapper">
      <div class="sci-product__picture">
        <img src="{{image}}" alt="">
      </div>
      <div class="sci-product__info">
        <div class="sci-product__info-wrapper">
          <div class="sci-product__sum-price">
            <div class="product-price">
              {{#existDiscount}}
                <span class="product-price__value product-price__value--new">{{sum}}</span>
                <span class="product-price__value product-price__value--sale">{{oldSum}}</span>
              {{/existDiscount}}
              {{^existDiscount}}
                <span class="product-price__value">{{sum}}</span>
              {{/existDiscount}}
            </div>
          </div>
          <div class="sci-product__name">{{name}}</div>
        </div>
        {{#model}}
        <div class="sci-product__count-block">
          <span class="sci-product__name">Модель: {{model}}</span>
        </div>
        {{/model}}
        <div class="sci-product__count-block">
          <span class="sci-product__name">{{quantity}} шт.</span>
        </div>
      </div>
    </div>
  </div>
</script>

<script>
  var SHOPCART_STEP_TITLES = ['Корзина', 'Контактные данные', 'Выбор доставки', 'Способ оплаты'];
</script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
