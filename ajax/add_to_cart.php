<?php

use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Context;
use Bitrix\Sale\Basket;
use BItrix\Sale\Fuser;
use Manom\Product;
use Manom\CatalogProvider;
use Manom\Store\StoreData;

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

CModule::IncludeModule('main');
CModule::IncludeModule('iblock');
CModule::IncludeModule('form');
CModule::IncludeModule('catalog');
CModule::IncludeModule('sale');

$product = new Product;

$productsOutOfStock = explode('|', $_COOKIE['productsOutOfStock']);

if (!$_REQUEST['METHOD_CART']) {
    exit;
}

if ((int)$_REQUEST['PRODUCT_ID'] > 0) {
    $productId = (int)$_REQUEST['PRODUCT_ID'];

    if ($_REQUEST['METHOD_CART'] === 'CHANGE_COUNT' && (int)$_REQUEST['COUNT'] >= 0) {
        $flag = false;
        $count = (int)$_REQUEST['COUNT'];

        $basketProduct = array();
        $filter = array('ID' => $productId);
        $select = array('ID', 'PRODUCT_ID', 'PRODUCT_PRICE_ID', 'PRICE_TYPE_ID', 'QUANTITY');
        $result = CSaleBasket::GetList(array(), $filter, false, false, $select);
        if ($row = $result->Fetch()) {
            $basketProduct = array(
                'productId' => (int)$row['PRODUCT_ID'],
                'priceId' => (int)$row['PRODUCT_PRICE_ID'],
                'priceTypeId' => (int)$row['PRICE_TYPE_ID'],
                'quantity' => (int)$row['QUANTITY'],
            );
        }

        if (!empty($basketProduct['productId'])) {
            if ($basketProduct['quantity'] >= $count) {
                $flag = true;
            } else {
                $count -= $basketProduct['quantity'];

                $product = new Product;
                $ecommerceData = $product->getEcommerceData(array($basketProduct['productId']), 6);
                /** @var StoreData $storeData */
                $storeData = $ecommerceData[$basketProduct['productId']]['storeData'];
                $mainStore = $storeData->getMain();
                $rrcStore = $storeData->getRrc();

                if (
                    $basketProduct['priceId'] === $mainStore['price']['ID'] &&
                    $mainStore['amount'] >= $count
                ) {
                    $flag = true;
                } elseif (
                    $basketProduct['priceId'] === $rrcStore['price']['ID'] &&
                    $rrcStore['amount'] >= $count
                ) {
                    $flag = true;
                }
            }
        }

        if ($flag) {
            $res = CSaleBasket::Update($productId, array('QUANTITY' => $_REQUEST['COUNT']));
        }

        if ($_REQUEST['AJAX_CART'] === 'Y') {
            ajaxShowBasket();
        }
    } elseif ($_REQUEST['METHOD_CART'] === 'add') {
        $data = $product->getEcommerceData(array($productId), 6);
        $data = $data[$productId];
        /** @var StoreData $storeData */
        $storeData = $data["storeData"];

        if (!$storeData->canBuy()) {
            exit;
        }

        if ($data["preOrder"]["active"]) {
            exit;
        }

        $basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
        if (!$basket->getExistsItem('catalog', $productId)) {
            $item = $basket->createItem('catalog', $productId);
            $item->setFields(
                array(
                    'QUANTITY' => 1,
                    'CURRENCY' => CurrencyManager::getBaseCurrency(),
                    'LID' => Context::getCurrent()->getSite(),
                    'PRODUCT_PROVIDER_CLASS' => CatalogProvider::class,
                )
            );
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
            CSaleBasket::Delete($productId);

            if ((int)$_REQUEST['outOfStock'] === 1) {
                $array = array_flip($productsOutOfStock);
                unset($array[$_REQUEST['productId']]);
                $productsOutOfStock = array_flip($array);
                setcookie("productsOutOfStock", implode('|', $productsOutOfStock), time() + 3600 * 24 * 30, '/');
            }
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
            'productsOutOfStock' => $productsOutOfStock,
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