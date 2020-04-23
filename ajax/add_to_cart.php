<?php


use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Sale\Basket;
use BItrix\Sale\Fuser;
use Manom\Product;
use Manom\CatalogProvider;

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

CModule::IncludeModule('main');
CModule::IncludeModule('iblock');
CModule::IncludeModule('form');
CModule::IncludeModule('catalog');
CModule::IncludeModule('sale');

$product = new Product;

if (!$_REQUEST['METHOD_CART']) {
    exit;
}

if ((int)$_REQUEST['PRODUCT_ID'] > 0) {
    if ($_REQUEST['METHOD_CART'] === 'CHANGE_COUNT' && (int)$_REQUEST['COUNT'] >= 0) {
        $res = CSaleBasket::Update($_REQUEST['PRODUCT_ID'], array('QUANTITY' => $_REQUEST['COUNT']));

        if ($_REQUEST['AJAX_CART'] === 'Y') {
            ajaxShowBasket();
        }
    } elseif ($_REQUEST['METHOD_CART'] === 'add') {
        $productId = (int)$_REQUEST['PRODUCT_ID'];

        $data = $product->getEcommerceData(array($productId), 6);
        $data = $data[$productId];

        if(empty($data['amounts']['main']) && empty($data['amounts']['second'])) {
            exit;
        }

        $basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
        if (!$basket->getExistsItem('catalog', $productId)) {
            $item = $basket->createItem('catalog', $productId);
            $item->setFields(array(
                 'QUANTITY' => 1,
                 'CURRENCY' => CurrencyManager::getBaseCurrency(),
                 'LID' => Context::getCurrent()->getSite(),
                 'PRODUCT_PROVIDER_CLASS' => CatalogProvider::class,
             ));
            $basket->save();
        }

        if ($_REQUEST['AJAX_CART'] === 'Y') {
            ajaxShowBasket();
        }
        if ($_REQUEST['AJAX_MIN_CART'] === 'Y') {
            ajaxShowBasketMin();
        }
    } elseif ($_REQUEST['METHOD_CART'] === 'delete') {
        if ($_REQUEST['clear_all'] === 'Y') {
            \CSaleBasket::DeleteAll(\CSaleBasket::GetBasketUserID());
        } else {
            CSaleBasket::Delete((int)$_REQUEST['PRODUCT_ID']);
        }

        if ($_REQUEST['AJAX_CART'] === 'Y') {
            ajaxShowBasket();
        }
        if ($_REQUEST['AJAX_MIN_CART'] === 'Y') {
            ajaxShowBasketMin();
        }
    }
} else {
    if ($_REQUEST['METHOD_CART'] === 'refredh_mini_cart' && $_REQUEST['AJAX_MIN_CART'] === 'Y') {
        ajaxShowBasketMin();
    }
    if ($_REQUEST['METHOD_CART'] === 'refredh_cart_info' && $_REQUEST['AJAX_CART_INFO'] === 'Y') {
        ajaxShowBasketInfo();
    }
    if ($_REQUEST['METHOD_CART'] === 'refredh_cart' && $_REQUEST['AJAX_CART'] === 'Y') {
        ajaxShowBasket();
    }
    if ($_REQUEST['METHOD_CART'] === 'clear' && $_REQUEST['AJAX_CART'] === 'Y') {
        \CSaleBasket::DeleteAll(\CSaleBasket::GetBasketUserID());
        ajaxShowBasket();
    }
}

function ajaxShowBasket()
{
    global $APPLICATION;
    $APPLICATION->IncludeComponent(
        'bitrix:sale.basket.basket',
        'manom',
        Array(
            'ACTION_VARIABLE' => '',
            'AUTO_CALCULATION' => 'Y',
            'TEMPLATE_THEME' => '',
            'COLUMNS_LIST' => array(
                'NAME',
                'DISCOUNT',
                'WEIGHT',
                'DELETE',
                'DELAY',
                'TYPE',
                'PRICE',
                'QUANTITY',
            ),
            'COMPONENT_TEMPLATE' => '',
            'COUNT_DISCOUNT_4_ALL_QUANTITY' => 'N',
            'GIFTS_BLOCK_TITLE' => '',
            'GIFTS_CONVERT_CURRENCY' => 'Y',
            'GIFTS_HIDE_BLOCK_TITLE' => 'N',
            'GIFTS_HIDE_NOT_AVAILABLE' => 'N',
            'GIFTS_MESS_BTN_BUY' => '',
            'GIFTS_MESS_BTN_DETAIL' => '',
            'GIFTS_PAGE_ELEMENT_COUNT' => 0,
            'GIFTS_PRODUCT_PROPS_VARIABLE' => '',
            'GIFTS_PRODUCT_QUANTITY_VARIABLE' => '',
            'GIFTS_SHOW_DISCOUNT_PERCENT' => '',
            'GIFTS_SHOW_IMAGE' => '',
            'GIFTS_SHOW_NAME' => '',
            'GIFTS_SHOW_OLD_PRICE' => '',
            'GIFTS_TEXT_LABEL_GIFT' => '',
            'GIFTS_PLACE' => '',
            'HIDE_COUPON' => 'N',
            'OFFERS_PROPS' => array(),
            'PATH_TO_ORDER' => '',
            'PRICE_VAT_SHOW_VALUE' => 'N',
            'QUANTITY_FLOAT' => 'N',
            'SET_TITLE' => 'N',
            'USE_GIFTS' => 'N',
            'USE_PREPAYMENT' => 'N',
        ),
        false
    );
}

function ajaxShowBasketMin()
{
    global $APPLICATION;
    $APPLICATION->IncludeComponent(
        'bitrix:sale.basket.basket',
        'min',
        Array(
            'ACTION_VARIABLE' => '',
            'AUTO_CALCULATION' => 'Y',
            'TEMPLATE_THEME' => '',
            'COLUMNS_LIST' => array(
                'NAME',
                'DISCOUNT',
                'WEIGHT',
                'DELETE',
                'DELAY',
                'TYPE',
                'PRICE',
                'QUANTITY',
            ),
            'COMPONENT_TEMPLATE' => '',
            'COUNT_DISCOUNT_4_ALL_QUANTITY' => 'N',
            'GIFTS_BLOCK_TITLE' => '',
            'GIFTS_CONVERT_CURRENCY' => 'Y',
            'GIFTS_HIDE_BLOCK_TITLE' => 'N',
            'GIFTS_HIDE_NOT_AVAILABLE' => 'N',
            'GIFTS_MESS_BTN_BUY' => '',
            'GIFTS_MESS_BTN_DETAIL' => '',
            'GIFTS_PAGE_ELEMENT_COUNT' => 0,
            'GIFTS_PRODUCT_PROPS_VARIABLE' => '',
            'GIFTS_PRODUCT_QUANTITY_VARIABLE' => '',
            'GIFTS_SHOW_DISCOUNT_PERCENT' => '',
            'GIFTS_SHOW_IMAGE' => '',
            'GIFTS_SHOW_NAME' => '',
            'GIFTS_SHOW_OLD_PRICE' => '',
            'GIFTS_TEXT_LABEL_GIFT' => '',
            'GIFTS_PLACE' => '',
            'HIDE_COUPON' => 'N',
            'OFFERS_PROPS' => array(),
            'PATH_TO_ORDER' => '',
            'PRICE_VAT_SHOW_VALUE' => 'N',
            'QUANTITY_FLOAT' => 'N',
            'SET_TITLE' => 'N',
            'USE_GIFTS' => 'N',
            'USE_PREPAYMENT' => 'N',
        ),
        false
    );
}

function ajaxShowBasketInfo()
{
    global $APPLICATION;
    $APPLICATION->IncludeComponent(
        'bitrix:sale.basket.basket',
        'info',
        Array(
            'ACTION_VARIABLE' => '',
            'AUTO_CALCULATION' => 'Y',
            'TEMPLATE_THEME' => '',
            'COLUMNS_LIST' => array(
                'NAME',
                'DISCOUNT',
                'WEIGHT',
                'DELETE',
                'DELAY',
                'TYPE',
                'PRICE',
                'QUANTITY',
            ),
            'COMPONENT_TEMPLATE' => '',
            'COUNT_DISCOUNT_4_ALL_QUANTITY' => 'N',
            'GIFTS_BLOCK_TITLE' => '',
            'GIFTS_CONVERT_CURRENCY' => 'Y',
            'GIFTS_HIDE_BLOCK_TITLE' => 'N',
            'GIFTS_HIDE_NOT_AVAILABLE' => 'N',
            'GIFTS_MESS_BTN_BUY' => '',
            'GIFTS_MESS_BTN_DETAIL' => '',
            'GIFTS_PAGE_ELEMENT_COUNT' => 0,
            'GIFTS_PRODUCT_PROPS_VARIABLE' => '',
            'GIFTS_PRODUCT_QUANTITY_VARIABLE' => '',
            'GIFTS_SHOW_DISCOUNT_PERCENT' => '',
            'GIFTS_SHOW_IMAGE' => '',
            'GIFTS_SHOW_NAME' => '',
            'GIFTS_SHOW_OLD_PRICE' => '',
            'GIFTS_TEXT_LABEL_GIFT' => '',
            'GIFTS_PLACE' => '',
            'HIDE_COUPON' => 'N',
            'OFFERS_PROPS' => array(),
            'PATH_TO_ORDER' => '',
            'PRICE_VAT_SHOW_VALUE' => 'N',
            'QUANTITY_FLOAT' => 'N',
            'SET_TITLE' => 'N',
            'USE_GIFTS' => 'N',
            'USE_PREPAYMENT' => 'N',
        ),
        false
    );
}