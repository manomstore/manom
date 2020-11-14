<?php

use \Manom\Price;

function BXIBlockAfterSave($arFields)
{
    try {
        $price = new Price();
        $price->recalculateTypeCurrent([$arFields["ID"]]);
    } catch (Exception $e) {
    }
}