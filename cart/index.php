<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
CModule::IncludeModule('statistic');
global $USER;
?>

<main class="shopcart" id="so_main_block" style="position:relative;">
  <div class="shopcart-nav1">
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

    <div class="shopcart__wrapper">
      <h1 class="shopcart__title">Корзина</h1>
      <button class="button-del button-del--top" type="button">Очистить</button>
    </div>
		<? if (!$_REQUEST['ORDER_ID']): ?>

      <div class="shopcart-main">
        <div class="shopcart-items">
          <section class="shopcart-item" id="shopcart-item1">
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
            <button class="button-del button-del--bottom" type="button">Очистить корзину</button>
          </section>
          <!-- Корзина - Контактные данные -->
          <section class="shopcart-item" id="shopcart-item2">
            <div class="sci-contact">
              <!-- Табы -->
              <div class="sci-contact-tabs">
                <input id="sci-contact-tab1" type="radio" name="tabs" checked>
                <label data-prop="PERSON_TYPE_1" class="sci-contact-tab rb_so" for="sci-contact-tab1"><span>Физическое лицо</span></label>
                <input id="sci-contact-tab2" type="radio" name="tabs">
                <label data-prop="PERSON_TYPE_2" class="sci-contact-tab rb_so" for="sci-contact-tab2"><span>Юридическое лицо</span></label>
                <section class="sci-contact-content" id="sci-contact-content1">
                  <label class="sci-contact__label" for="sci-contact__fio">Имя и фамилия*</label>
                  <input type="text"
                         data-prop="ORDER_PROP_1"
                         name="sci-contact__fio"
                         id="sci-contact__fio"
                         class="sci-contact__input"
                         placeholder="Ваши имя и фамилия"
                         required>
                  <label class="sci-contact__label" for="sci-contact__tel">Телефон*</label>
                  <input type="tel"
                         data-prop="ORDER_PROP_2"
                         name="sci-contact__tel"
                         id="sci-contact__tel"
                         class="sci-contact__input"
                         placeholder="Ваш телефон"
                         required> <!-- pattern="+[0-9]{1} ([0-9]{3}) [0-9]{3}-[0-9]{4}"  -->
                  <label class="sci-contact__label" for="sci-contact__email">E-mail*</label>
                  <input type="email"
                         data-prop="ORDER_PROP_3"
                         name="sci-contact__email"
                         id="sci-contact__email"
                         class="sci-contact__input"
                         placeholder="Ваш e-mail"
                         required>
                  <p class="sci-contact__consent">Заполняя форму я даю согласие на обработку моих персональных данных. <a href="#">Ознакомиться с
                      соглашением</a>
                    об обработке персональных данных.
                  </p>
                </section>
                <section class="sci-contact-content" id="sci-contact-content2">
                  <label class="sci-contact__l-label w-100">Название компании*
                    <input type="text"
                           data-prop="ORDER_PROP_4"
                           name="sci-contact__ur-name"
                           id="sci-contact__ur-name"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-100">Телефон*
                    <input type="text"
                           data-prop="ORDER_PROP_33"
                           name="sci-contact__ur-phone"
                           id="sci-contact__ur-phone"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-100">E-mail*
                    <input type="email"
                           data-prop="ORDER_PROP_32"
                           name="sci-contact__ur-email"
                           id="sci-contact__ur-email"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-100">Юридическое название*
                    <input type="text"
                           data-prop="ORDER_PROP_5"
                           name="sci-contact__ur-legal-name"
                           id="sci-contact__ur-legal-name"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-50">ОГРН*
                    <input type="text"
                           data-prop="ORDER_PROP_6"
                           name="sci-contact__ur-ogrn"
                           id="sci-contact__ur-ogrn"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-50">Правовая форма*

                    <select data-prop="ORDER_PROP_7" name="sci-contact__ur-legal-norm" id="sci-contact__ur-legal-norm" class="sci-contact__input" required>
                      <option selected value="1">ИП</option>
                      <option value="2">ООО</option>
                      <option value="3">ОАО</option>
                      <option value="4">ПАО</option>
                    </select>
                  </label>

                  <label class="sci-contact__l-label w-50">ИНН*
                    <input type="text"
                           data-prop="ORDER_PROP_8"
                           name="sci-contact__ur-inn"
                           id="sci-contact__ur-inn"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-50">КПП*
                    <input type="text"
                           data-prop="ORDER_PROP_9"
                           name="sci-contact__ur-kpp"
                           id="sci-contact__ur-kpp"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-100">Юридический адрес*
                    <input type="text"
                           data-prop="ORDER_PROP_10"
                           name="sci-contact__ur-legal-address"
                           id="sci-contact__ur-legal-address"
                           class="sci-contact__input"
                           placeholder="Индекс, город, адрес"
                           required>
                  </label>
                  <label class="sci-contact__l-label w-100">Фактический адрес*
                    <input type="text"
                           data-prop="ORDER_PROP_11"
                           name="sci-contact__ur-fact-address"
                           id="sci-contact__ur-fact-address"
                           class="sci-contact__input"
                           placeholder="Индекс, город, адрес"
                           required>
                  </label>
                  <h3 class="sci-contact__title">Банк</h3>
                  <label class="sci-contact__l-label w-100">Наименование*
                    <input type="text"
                           data-prop="ORDER_PROP_12"
                           name="sci-contact__bank-name"
                           id="sci-contact__bank-name"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-100">Город Банка*
                    <input type="text"
                           data-prop="ORDER_PROP_13"
                           name="sci-contact__bank-sity"
                           id="sci-contact__bank-sity"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-30">БИК*
                    <input type="text"
                           data-prop="ORDER_PROP_14"
                           name="sci-contact__bank-bik"
                           id="sci-contact__bank-bik"
                           class="sci-contact__input w50"
                           placeholder=""
                           required>
                  </label>
                  <label class="sci-contact__l-label w-70">Номер счета*
                    <input type="text"
                           data-prop="ORDER_PROP_15"
                           name="sci-contact__bank-account"
                           id="sci-contact__bank-account"
                           class="sci-contact__input"
                           placeholder=""
                           required>
                  </label>
                  <label for="sci-contact__contacts" class="sci-contact__l-label w-100">Контакты
                  </label>
                  <textarea data-prop="ORDER_PROP_16"
                            name="sci-contact__contacts"
                            id="sci-contact__contacts"
                            class="sci-contact__input w-100"
                            placeholder=""
                            rows="3"></textarea>
                  <p class="sci-contact__consent">Заполняя форму я даю согласие на обработку моих персональных данных. <a href="#">Ознакомиться с
                      соглашением</a>
                    об обработке персональных данных.
                  </p>
                </section>
              </div>
            </div>
						<? if (!$USER->IsAuthorized()): ?>
              <div class="sci-login" style="margin-top:0;">
                <h3 class="sci-login__title">Войти в существующий аккаунт</h3>
                <p class="sci-login__text">Войдите в существующий аккаунт для быстрого оформления покупки.</p>
                <div style="text-align: center;">
                  <button class="sci-login__button" id="cart_btn_open_login">Войти</button>
                </div>
              </div>
						<? endif; ?>
          </section>
          <!-- Корзина - Доставка -->
          <section class="shopcart-item" id="shopcart-item3">
            <!-- <h2>Корзина - Доставка</h2> -->
            <div class="sci-delivery">
              <!-- Табы -->
              <div class="sci-delivery-tabs">
                <input id="sci-delivery-tab1" type="radio" name="delivery-tabs" checked>
                <label data-prop="ID_DELIVERY_ID_5" class="sci-delivery-tab rb_so" for="sci-delivery-tab1"><span>Доставка по адресу</span></label>
                <input id="sci-delivery-tab2" type="radio" name="delivery-tabs">
                <label data-prop="ID_DELIVERY_ID_6" class="sci-delivery-tab rb_so" for="sci-delivery-tab2"><span>Доставка до пункта выдачи</span></label>
                <input id="sci-delivery-tab3" type="radio" name="delivery-tabs">
                <label data-prop="ID_DELIVERY_ID_13" class="sci-delivery-tab rb_so" for="sci-delivery-tab3"><span>Самовывоз</span></label>
                <!-- <input id="sci-delivery-tab3" type="radio" name="delivery-tabs" class="rb_so_proxy">
									<label data-prop="ID_DELIVERY_ID_8" class="sci-delivery-tab rb_so" for="sci-delivery-tab3"><span>Доставка собственным курьером (по МКАДу)</span></label>
									<input id="sci-delivery-tab4" type="radio" name="delivery-tabs" class="rb_so_proxy">
									<label data-prop="ID_DELIVERY_ID_9" class="sci-delivery-tab rb_so" for="sci-delivery-tab4"><span>Доставка собственным курьером (за МКАДом)</span></label>
									<input id="sci-delivery-tab5" type="radio" name="delivery-tabs" class="rb_so_proxy">
									<label data-prop="ID_DELIVERY_ID_10" class="sci-delivery-tab rb_so" for="sci-delivery-tab5"><span>Доставка собственным курьером (по КАДу)</span></label>
									<input id="sci-delivery-tab6" type="radio" name="delivery-tabs" class="rb_so_proxy">
									<label data-prop="ID_DELIVERY_ID_11" class="sci-delivery-tab rb_so" for="sci-delivery-tab6"><span>Доставка собственным курьером (за КАДом)</span></label> -->

                <section class="sci-delivery-content" id="sci-delivery-content1">
                  <h3 class="sci-delivery__title w-100">Введите адрес доставки</h3>
                  <div class="sci-delivery-content__col">
                    <label class="sci-delivery__label w-100">Город
                      <input data-city-prop-alt="ORDER_PROP_25"
                             data-city-prop-val-alt="ORDER_PROP_25_val"
                             data-city-prop="ORDER_PROP_18"
                             data-city-prop-val="ORDER_PROP_18_val"
                             name="so_city_val"
                             id="so_city_val"
                             value=""
                             class="sci-delivery__input search-suggest"
                             type="text"
                             autocomplete="off"
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
                    <label class="sci-delivery__label w-100">Улица*
                      <input data-prop="ORDER_PROP_20"
                             data-prop-alt="ORDER_PROP_27"
                             type="text"
                             name="sci-delivery-street"
                             id="sci-delivery-street"
                             class="sci-delivery__input"
                             placeholder=""
                             required>
                    </label>
                    <label class="sci-delivery__label w-100">Дом*
                      <input data-prop="ORDER_PROP_22"
                             type="text"
                             data-prop-alt="ORDER_PROP_29"
                             name="sci-delivery-building"
                             id="sci-delivery-building"
                             class="sci-delivery__input w50"
                             placeholder=""
                             required>
                    </label>
                    <label class="sci-delivery__label w-100">Кв/Офис*
                      <input data-prop="ORDER_PROP_23"
                             type="text"
                             data-prop-alt="ORDER_PROP_30"
                             name="sci-delivery-apartment"
                             id="sci-delivery-apartment"
                             class="sci-delivery__input w50"
                             placeholder=""
                             required>
                    </label>
                  </div>
                  <div class="sci-delivery-content__col">
                    <input type="hidden" data-prop="egor" name="" value="">
                    <label class="sci-delivery__label w-100">Дата доставки&nbsp
                      <input data-change="Y"
                             data-prop="ORDER_PROP_19"
                             data-prop-alt="ORDER_PROP_26"
                             type="date"
                             name="sci-delivery-date"
                             id="sci-delivery-date"
                             class="sci-delivery__input"
                             placeholder="">
                    </label>
                    <label class="sci-delivery__label w-100">Время доставки
                      <!-- <input data-change="Y" data-prop="ORDER_PROP_21" data-prop-alt="ORDER_PROP_28" type="text" name="sci-delivery-time" id="sci-delivery-time" class="sci-delivery__input" placeholder=""> -->
                      <select data-prop="ORDER_PROP_21"
                              data-prop-alt="ORDER_PROP_28"
                              name="sci-delivery-time"
                              id="sci-delivery-time"
                              class="sci-contact__input"
                              required>
                        <option selected value="c 06:00 до 09:00">c 06:00 до 09:00</option>
                        <option value="c 09:00 до 012:00">c 09:00 до 012:00</option>
                        <option value="c 12:00 до 15:00">c 12:00 до 15:00</option>
                        <option value="c 15:00 до 18:00">c 15:00 до 18:00</option>
                        <option value="c 18:00 до 21:00">c 18:00 до 21:00</option>
                      </select>
                    </label>
                    <label for="sci-delivery__comment" class="sci-delivery__label w-100">Комментарий для курьера</label>
                    <textarea data-prop="ORDER_PROP_24"
                              data-prop-alt="ORDER_PROP_31"
                              name="sci-delivery__comment"
                              id="sci-delivery__comment"
                              class="sci-delivery__input w-100"
                              placeholder=""
                              rows="3"></textarea>
                    <span class="pickup_summ_alt col-12 mb-2"><strong>Стоимость:</strong> <span>не известно</span></span><br>
                  </div>
                </section>
                <section class="sci-delivery-content" id="sci-delivery-content2">
                  <h3 class="sci-delivery__title">Доставка до пункта выдачи</h3>
                  <div class="sci-delivery-content__col fix col-12">
                    <label class="sci-delivery__label w-100" style="padding-left: 15px;text-align: left;">Город
                      <input data-city-prop-alt="ORDER_PROP_25"
                             data-city-prop-val-alt="ORDER_PROP_25_val"
                             data-city-prop="ORDER_PROP_18"
                             data-city-prop-val="ORDER_PROP_18_val"
                             name="so_city_alt_val"
                             id="so_city_alt_val"
                             value=""
                             class="sci-delivery__input search-suggest"
                             type="text"
                             autocomplete="off"
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
                    <span class="pickup_address col-12 mb-2"><strong>Пункт самовывоза:</strong> <span>не выбран</span></span> <br>
                    <span class="pickup_summ col-12 mb-2"><strong>Стоимость:</strong> <span>не известно</span></span><br>
                    <span class="pickup_date col-12 mb-2">Время доставки: <span>не известно</span></span><br>
                    <span class="shopcart-sidebar__back-shopping" id="soDelivPopUp">Выбрать пункт самовывоза</span>
                  </div>
                </section>
                <section class="sci-delivery-content" id="sci-delivery-content3">
                  <h3 class="sci-delivery__title">Самовывоз</h3>
                  <div class="sci-delivery-content__col fix col-12">
                    <span class="sv_address col-12 mb-2" style="display:block;width: 100%;text-align: left;"></span><br><br><br>
                    <span class="sv_price col-12 mb-2"
                          style="display:block;width: 100%;text-align: left;"><strong>Стоимость:</strong> <span>не известно</span></span><br>
                    <span class="sv_time col-12 mb-2"
                          style="display:block;width: 100%;text-align: left;"><strong>Время доставки:</strong> <span>не известно</span></span><br>
                  </div>
                </section>
          </section>
          <!-- Корзина - Оплата -->
          <section class="shopcart-item" id="shopcart-item4">
            <div class="sci-payment">
              <div class="sci-payment-tabs">
                <label class="sci-payment__tab rb_so" data-prop="ID_PAY_SYSTEM_ID_4">
                  <input id="sci-payment-tab1" class="sci-payment__input" type="radio" name="payment-tabs" value="1" checked>
                  <span class="sci-payment__radio"></span>
                  <span class="sci-payment__name">Оплата банковской картой</span>
                </label>
                <label class="sci-payment__tab rb_so" data-prop="ID_PAY_SYSTEM_ID_6">
                  <input id="sci-payment-tab2" class="sci-payment__input" type="radio" name="payment-tabs" value="2">
                  <span class="sci-payment__radio"></span>
                  <span class="sci-payment__name">Оплата наличными курьеру/ в пункте самовывоза</span>
                </label>
                <label class="sci-payment__tab rb_so" data-prop="ID_PAY_SYSTEM_ID_2">
                  <input id="sci-payment-tab3" class="sci-payment__input" type="radio" name="payment-tabs" value="3">
                  <span class="sci-payment__radio"></span>
                  <span class="sci-payment__name">Yandex Money/ Web Money</span>
                </label>
              </div>
              <section class="sci-payment-content" id="sci-payment-content1">
                <h3>Оплата банковской картой</h3>
                <img src="/verst/img/bank-card.jpg" class="sci-payment__img-bc" alt="Банковская карта">
              </section>
              <section class="sci-payment-content" id="sci-payment-content2">
                <h3>Оплата наличными курьеру/ в пункте самовывоза</h3>
                <img src="/verst/img/bank-card.jpg" class="sci-payment__img-bc" alt="Банковская карта">
              </section>
              <section class="sci-payment-content" id="sci-payment-content3">
                <h3>Yandex Money/ Web Money</h3>
                <img src="/verst/img/bank-card.jpg" class="sci-payment__img-bc" alt="Банковская карта">
              </section>
            </div>
          </section>
        </div>
        <div class="shopcart-sidebar">
          <div class="shopcart-sidebar__wrapper">
            <div class="promo-code shopcart-sidebar__code">
              <form action="#" method="post">
                <input
                  class="promo-code__field"
                  type="text"
                  name="promo-code"
                  placeholder="Промокод на скидку"
                >
                <button class="promo-code__button" type="submit">Применить</button>
              </form>
            </div>
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
              <div class="shopcart-sidebar__total">
                <div class="shopcart-sidebar__price-wrapper">
                  <p class="shopcart-sidebar__sum-price">
                    Стоимость товаров
                    <span>512 940 ₽</span>
                  </p>
                  <p class="shopcart-sidebar__sum-price shopcart-sidebar__sum-price--sale">
                    Скидка на товары
                    <span>− 2 582 ₽</span>
                  </p>
                </div>
                <p class="shopcart-sidebar__sum-price shopcart-sidebar__sum-price--total">
                  Итого:
                  <span id="total_price_cart"><?= $cartPrice; ?> ₽</span>
                </p>
                <p></p>
              </div>
            </div>
            <a class="shopcart-sidebar__button" id="btnNextSlide" href="#">Оформить заказ</a>
            <!--        <div class="shopcart-sidebar__button hidden" id="btnSubmitOrder">Оформить заказ</div>-->
            <!--        <div class="shopcart-sidebar__back-shopping BOC_btn" data-fancybox="" data-src="#popap-buy-one-click-cart" href="javascript:;">Купить в 1 клик</div>-->
          </div>
          <div class="shopcart-sidebar__back-wrapper">
            <a class="shopcart-sidebar__back-shopping" id="backToCatalog" href="#">Вернуться к покупкам</a>
          </div>
        </div>
      </div>

		<? else: ?>
      <div class="text-align:centr;">
        Ваш заказ №<?= $_REQUEST['ORDER_ID'] ?> был успешно оформлен. В ближайшее время с вами свяжется наш менеджер для дальнейшего подверждения заказа.
      </div>
		<? endif ?>
  </div>
</main>
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
			"ALLOW_NEW_PROFILE"              => "Y",
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
			"ALLOW_APPEND_ORDER"             => "N",
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
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
