<?php

use \Manom\Content;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['BANNER'] = Content::getSectionBanner((int)$arResult['SECTION']['ID']);
