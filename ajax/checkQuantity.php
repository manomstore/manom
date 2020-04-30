<?php

use Manom\Basket;

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $APPLICATION;
$APPLICATION->RestartBuffer();

$return = array('productsOutOfStock' => false);

$basket = new Basket;
$productsOutOfStock = $basket->getUserOutOfStockProducts();

if (!empty($productsOutOfStock)) {
    $return['productsOutOfStock'] = true;
    setcookie("productsOutOfStock", implode('|', $productsOutOfStock), time() + 3600 * 24 * 30, '/');
}

die(json_encode($return));