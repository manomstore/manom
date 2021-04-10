<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

CJSCore::Init(array('fx', 'popup'));

global $hideSmartFilter;
global $filterValues;
$hideSmartFilter = $arResult["HAS_FILTER_ELEMENT"] === false;
$filterValues = $arResult["FILTER_VALUES"];