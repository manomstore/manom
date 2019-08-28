<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("main");
CModule::IncludeModule("iblock");
CModule::IncludeModule("form");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

if ($_REQUEST['METHOD_CART'] and (int)$_REQUEST['PRODUCT_ID'] > 0) {
  if ($_REQUEST['METHOD_CART'] == 'CHANGE_COUNT' and (int)$_REQUEST['COUNT'] >= 0) {
    $res = CSaleBasket::Update($_REQUEST['PRODUCT_ID'], array('QUANTITY' => $_REQUEST['COUNT']));
    echo "res: ".$res;
    if ($_REQUEST['AJAX_CART'] == 'Y') {
      $APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "manom", Array(
        "ACTION_VARIABLE" => "action",	// Название переменной действия
          "AUTO_CALCULATION" => "Y",	// Автопересчет корзины
          "TEMPLATE_THEME" => "blue",	// Цветовая тема
          "COLUMNS_LIST" => array(
            0 => "NAME",
            1 => "DISCOUNT",
            2 => "WEIGHT",
            3 => "DELETE",
            4 => "DELAY",
            5 => "TYPE",
            6 => "PRICE",
            7 => "QUANTITY",
          ),
          "COMPONENT_TEMPLATE" => "header-search",
          "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
          "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",	// Текст заголовка "Подарки"
          "GIFTS_CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
          "GIFTS_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки"
          "GIFTS_HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
          "GIFTS_MESS_BTN_BUY" => "Выбрать",	// Текст кнопки "Выбрать"
          "GIFTS_MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
          "GIFTS_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в строке
          "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
          "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
          "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
          "GIFTS_SHOW_IMAGE" => "Y",	// Показывать изображение
          "GIFTS_SHOW_NAME" => "Y",	// Показывать название
          "GIFTS_SHOW_OLD_PRICE" => "Y",	// Показывать старую цену
          "GIFTS_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка"
          "GIFTS_PLACE" => "BOTTOM",	// Вывод блока "Подарки"
          "HIDE_COUPON" => "N",	// Спрятать поле ввода купона
          "OFFERS_PROPS" => array(	// Свойства, влияющие на пересчет корзины
            0 => "SIZES_SHOES",
            1 => "SIZES_CLOTHES",
          ),
          "PATH_TO_ORDER" => "/personal/order.php",	// Страница оформления заказа
          "PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
          "QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
          "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
          "USE_GIFTS" => "Y",	// Показывать блок "Подарки"
          "USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
        ),
        false
      );
    }
  } elseif ($_REQUEST['METHOD_CART'] == 'add') {
    $res = Add2BasketByProductID((int)$_REQUEST['PRODUCT_ID']);
    if ($_REQUEST['AJAX_CART'] == 'Y') {
      $APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "manom", Array(
        "ACTION_VARIABLE" => "action",	// Название переменной действия
          "AUTO_CALCULATION" => "Y",	// Автопересчет корзины
          "TEMPLATE_THEME" => "blue",	// Цветовая тема
          "COLUMNS_LIST" => array(
            0 => "NAME",
            1 => "DISCOUNT",
            2 => "WEIGHT",
            3 => "DELETE",
            4 => "DELAY",
            5 => "TYPE",
            6 => "PRICE",
            7 => "QUANTITY",
          ),
          "COMPONENT_TEMPLATE" => "header-search",
          "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
          "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",	// Текст заголовка "Подарки"
          "GIFTS_CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
          "GIFTS_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки"
          "GIFTS_HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
          "GIFTS_MESS_BTN_BUY" => "Выбрать",	// Текст кнопки "Выбрать"
          "GIFTS_MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
          "GIFTS_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в строке
          "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
          "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
          "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
          "GIFTS_SHOW_IMAGE" => "Y",	// Показывать изображение
          "GIFTS_SHOW_NAME" => "Y",	// Показывать название
          "GIFTS_SHOW_OLD_PRICE" => "Y",	// Показывать старую цену
          "GIFTS_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка"
          "GIFTS_PLACE" => "BOTTOM",	// Вывод блока "Подарки"
          "HIDE_COUPON" => "N",	// Спрятать поле ввода купона
          "OFFERS_PROPS" => array(	// Свойства, влияющие на пересчет корзины
            0 => "SIZES_SHOES",
            1 => "SIZES_CLOTHES",
          ),
          "PATH_TO_ORDER" => "/personal/order.php",	// Страница оформления заказа
          "PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
          "QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
          "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
          "USE_GIFTS" => "Y",	// Показывать блок "Подарки"
          "USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
        ),
        false
      );
    }
    if ($_REQUEST['AJAX_MIN_CART'] == 'Y') {
      $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "monom", Array(
          "HIDE_ON_BASKET_PAGES" => "Y",	// Не показывать на страницах корзины и оформления заказа
          "PATH_TO_BASKET" => SITE_DIR."personal/cart/",	// Страница корзины
          "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",	// Страница оформления заказа
          "PATH_TO_PERSONAL" => SITE_DIR."personal/",	// Страница персонального раздела
          "PATH_TO_PROFILE" => SITE_DIR."personal/",	// Страница профиля
          "PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
          "POSITION_FIXED" => "Y",	// Отображать корзину поверх шаблона
          "POSITION_HORIZONTAL" => "right",	// Положение по горизонтали
          "POSITION_VERTICAL" => "top",	// Положение по вертикали
          "SHOW_AUTHOR" => "Y",	// Добавить возможность авторизации
          "SHOW_DELAY" => "N",	// Показывать отложенные товары
          "SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
          "SHOW_IMAGE" => "Y",	// Выводить картинку товара
          "SHOW_NOTAVAIL" => "N",	// Показывать товары, недоступные для покупки
          "SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
          "SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
          "SHOW_PRICE" => "Y",	// Выводить цену товара
          "SHOW_PRODUCTS" => "Y",	// Показывать список товаров
          "SHOW_SUMMARY" => "Y",	// Выводить подытог по строке
          "SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
        ),
        false
      );
    }
  }elseif ($_REQUEST['METHOD_CART'] == 'delete') {
      if ($_REQUEST['clear_all'] === "Y") {
          \CSaleBasket::DeleteAll(\CSaleBasket::GetBasketUserID());
      } else {
          CSaleBasket::Delete((int)$_REQUEST['PRODUCT_ID']);
      }
    if ($_REQUEST['AJAX_MIN_CART'] == 'Y') {
      $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "monom", Array(
          "HIDE_ON_BASKET_PAGES" => "Y",	// Не показывать на страницах корзины и оформления заказа
          "PATH_TO_BASKET" => SITE_DIR."personal/cart/",	// Страница корзины
          "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",	// Страница оформления заказа
          "PATH_TO_PERSONAL" => SITE_DIR."personal/",	// Страница персонального раздела
          "PATH_TO_PROFILE" => SITE_DIR."personal/",	// Страница профиля
          "PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
          "POSITION_FIXED" => "Y",	// Отображать корзину поверх шаблона
          "POSITION_HORIZONTAL" => "right",	// Положение по горизонтали
          "POSITION_VERTICAL" => "top",	// Положение по вертикали
          "SHOW_AUTHOR" => "Y",	// Добавить возможность авторизации
          "SHOW_DELAY" => "N",	// Показывать отложенные товары
          "SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
          "SHOW_IMAGE" => "Y",	// Выводить картинку товара
          "SHOW_NOTAVAIL" => "N",	// Показывать товары, недоступные для покупки
          "SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
          "SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
          "SHOW_PRICE" => "Y",	// Выводить цену товара
          "SHOW_PRODUCTS" => "Y",	// Показывать список товаров
          "SHOW_SUMMARY" => "Y",	// Выводить подытог по строке
          "SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
        ),
        false
      );
    }
    if ($_REQUEST['AJAX_CART'] == 'Y') {
      $APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "manom", Array(
        "ACTION_VARIABLE" => "action",	// Название переменной действия
          "AUTO_CALCULATION" => "Y",	// Автопересчет корзины
          "TEMPLATE_THEME" => "blue",	// Цветовая тема
          "COLUMNS_LIST" => array(
            0 => "NAME",
            1 => "DISCOUNT",
            2 => "WEIGHT",
            3 => "DELETE",
            4 => "DELAY",
            5 => "TYPE",
            6 => "PRICE",
            7 => "QUANTITY",
          ),
          "COMPONENT_TEMPLATE" => "header-search",
          "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
          "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",	// Текст заголовка "Подарки"
          "GIFTS_CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
          "GIFTS_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки"
          "GIFTS_HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
          "GIFTS_MESS_BTN_BUY" => "Выбрать",	// Текст кнопки "Выбрать"
          "GIFTS_MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
          "GIFTS_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в строке
          "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
          "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
          "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
          "GIFTS_SHOW_IMAGE" => "Y",	// Показывать изображение
          "GIFTS_SHOW_NAME" => "Y",	// Показывать название
          "GIFTS_SHOW_OLD_PRICE" => "Y",	// Показывать старую цену
          "GIFTS_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка"
          "GIFTS_PLACE" => "BOTTOM",	// Вывод блока "Подарки"
          "HIDE_COUPON" => "N",	// Спрятать поле ввода купона
          "OFFERS_PROPS" => array(	// Свойства, влияющие на пересчет корзины
            0 => "SIZES_SHOES",
            1 => "SIZES_CLOTHES",
          ),
          "PATH_TO_ORDER" => "/personal/order.php",	// Страница оформления заказа
          "PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
          "QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
          "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
          "USE_GIFTS" => "Y",	// Показывать блок "Подарки"
          "USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
        ),
        false
      );
    }
  }
} elseif ($_REQUEST['METHOD_CART']) {
  if ($_REQUEST['METHOD_CART'] == 'refredh_mini_cart' and $_REQUEST['AJAX_MIN_CART'] == 'Y') {
    $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "monom", Array(
        "HIDE_ON_BASKET_PAGES" => "Y",	// Не показывать на страницах корзины и оформления заказа
        "PATH_TO_BASKET" => SITE_DIR."personal/cart/",	// Страница корзины
        "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",	// Страница оформления заказа
        "PATH_TO_PERSONAL" => SITE_DIR."personal/",	// Страница персонального раздела
        "PATH_TO_PROFILE" => SITE_DIR."personal/",	// Страница профиля
        "PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
        "POSITION_FIXED" => "Y",	// Отображать корзину поверх шаблона
        "POSITION_HORIZONTAL" => "right",	// Положение по горизонтали
        "POSITION_VERTICAL" => "top",	// Положение по вертикали
        "SHOW_AUTHOR" => "Y",	// Добавить возможность авторизации
        "SHOW_DELAY" => "N",	// Показывать отложенные товары
        "SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
        "SHOW_IMAGE" => "Y",	// Выводить картинку товара
        "SHOW_NOTAVAIL" => "N",	// Показывать товары, недоступные для покупки
        "SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
        "SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
        "SHOW_PRICE" => "Y",	// Выводить цену товара
        "SHOW_PRODUCTS" => "Y",	// Показывать список товаров
        "SHOW_SUMMARY" => "Y",	// Выводить подытог по строке
        "SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
      ),
      false
    );
  }
  if ($_REQUEST['METHOD_CART'] == 'refredh_cart_info' and $_REQUEST['AJAX_CART_INFO'] == 'Y') {
    $APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "cart_info", Array(
        "HIDE_ON_BASKET_PAGES" => "Y",	// Не показывать на страницах корзины и оформления заказа
        "PATH_TO_BASKET" => SITE_DIR."personal/cart/",	// Страница корзины
        "PATH_TO_ORDER" => SITE_DIR."personal/order/make/",	// Страница оформления заказа
        "PATH_TO_PERSONAL" => SITE_DIR."personal/",	// Страница персонального раздела
        "PATH_TO_PROFILE" => SITE_DIR."personal/",	// Страница профиля
        "PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
        "POSITION_FIXED" => "Y",	// Отображать корзину поверх шаблона
        "POSITION_HORIZONTAL" => "right",	// Положение по горизонтали
        "POSITION_VERTICAL" => "top",	// Положение по вертикали
        "SHOW_AUTHOR" => "Y",	// Добавить возможность авторизации
        "SHOW_DELAY" => "N",	// Показывать отложенные товары
        "SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
        "SHOW_IMAGE" => "Y",	// Выводить картинку товара
        "SHOW_NOTAVAIL" => "N",	// Показывать товары, недоступные для покупки
        "SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
        "SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
        "SHOW_PRICE" => "Y",	// Выводить цену товара
        "SHOW_PRODUCTS" => "Y",	// Показывать список товаров
        "SHOW_SUMMARY" => "Y",	// Выводить подытог по строке
        "SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
      ),
      false
    );
  }
  if ($_REQUEST['METHOD_CART'] == 'refredh_cart' and $_REQUEST['AJAX_CART'] == 'Y') {
    $APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "manom", Array(
      "ACTION_VARIABLE" => "action",	// Название переменной действия
        "AUTO_CALCULATION" => "Y",	// Автопересчет корзины
        "TEMPLATE_THEME" => "blue",	// Цветовая тема
        "COLUMNS_LIST" => array(
          0 => "NAME",
          1 => "DISCOUNT",
          2 => "WEIGHT",
          3 => "DELETE",
          4 => "DELAY",
          5 => "TYPE",
          6 => "PRICE",
          7 => "QUANTITY",
        ),
        "COMPONENT_TEMPLATE" => "header-search",
        "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
        "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",	// Текст заголовка "Подарки"
        "GIFTS_CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
        "GIFTS_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки"
        "GIFTS_HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
        "GIFTS_MESS_BTN_BUY" => "Выбрать",	// Текст кнопки "Выбрать"
        "GIFTS_MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
        "GIFTS_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в строке
        "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
        "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
        "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
        "GIFTS_SHOW_IMAGE" => "Y",	// Показывать изображение
        "GIFTS_SHOW_NAME" => "Y",	// Показывать название
        "GIFTS_SHOW_OLD_PRICE" => "Y",	// Показывать старую цену
        "GIFTS_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка"
        "GIFTS_PLACE" => "BOTTOM",	// Вывод блока "Подарки"
        "HIDE_COUPON" => "N",	// Спрятать поле ввода купона
        "OFFERS_PROPS" => array(	// Свойства, влияющие на пересчет корзины
          0 => "SIZES_SHOES",
          1 => "SIZES_CLOTHES",
        ),
        "PATH_TO_ORDER" => "/personal/order.php",	// Страница оформления заказа
        "PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
        "QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
        "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
        "USE_GIFTS" => "Y",	// Показывать блок "Подарки"
        "USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
      ),
      false
    );
  }
  if ($_REQUEST['METHOD_CART'] == 'clear' && $_REQUEST['AJAX_CART'] == 'Y') {
      \CSaleBasket::DeleteAll(\CSaleBasket::GetBasketUserID());
          $APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "manom", Array(
              "ACTION_VARIABLE" => "action",	// Название переменной действия
              "AUTO_CALCULATION" => "Y",	// Автопересчет корзины
              "TEMPLATE_THEME" => "blue",	// Цветовая тема
              "COLUMNS_LIST" => array(
                  0 => "NAME",
                  1 => "DISCOUNT",
                  2 => "WEIGHT",
                  3 => "DELETE",
                  4 => "DELAY",
                  5 => "TYPE",
                  6 => "PRICE",
                  7 => "QUANTITY",
              ),
              "COMPONENT_TEMPLATE" => "header-search",
              "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
              "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",	// Текст заголовка "Подарки"
              "GIFTS_CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
              "GIFTS_HIDE_BLOCK_TITLE" => "N",	// Скрыть заголовок "Подарки"
              "GIFTS_HIDE_NOT_AVAILABLE" => "N",	// Не отображать товары, которых нет на складах
              "GIFTS_MESS_BTN_BUY" => "Выбрать",	// Текст кнопки "Выбрать"
              "GIFTS_MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
              "GIFTS_PAGE_ELEMENT_COUNT" => "4",	// Количество элементов в строке
              "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
              "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
              "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
              "GIFTS_SHOW_IMAGE" => "Y",	// Показывать изображение
              "GIFTS_SHOW_NAME" => "Y",	// Показывать название
              "GIFTS_SHOW_OLD_PRICE" => "Y",	// Показывать старую цену
              "GIFTS_TEXT_LABEL_GIFT" => "Подарок",	// Текст метки "Подарка"
              "GIFTS_PLACE" => "BOTTOM",	// Вывод блока "Подарки"
              "HIDE_COUPON" => "N",	// Спрятать поле ввода купона
              "OFFERS_PROPS" => array(	// Свойства, влияющие на пересчет корзины
                  0 => "SIZES_SHOES",
                  1 => "SIZES_CLOTHES",
              ),
              "PATH_TO_ORDER" => "/personal/order.php",	// Страница оформления заказа
              "PRICE_VAT_SHOW_VALUE" => "N",	// Отображать значение НДС
              "QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
              "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
              "USE_GIFTS" => "Y",	// Показывать блок "Подарки"
              "USE_PREPAYMENT" => "N",	// Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
          ),
              false
          );
  }
}
