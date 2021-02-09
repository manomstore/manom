<?php

use \Bitrix\Catalog\Model\Product;
use \Manom\Price;
use \Manom\Store\Amount;

function BXIBlockAfterSave($arFields)
{
    try {
        $price = new Price();
        $price->recalculateTypeCurrent([$arFields["ID"]]);
        Product::update($arFields["ID"], ["QUANTITY" => Amount::getAvailableQuantity($arFields["ID"])]);
    } catch (Exception $e) {
    }
}