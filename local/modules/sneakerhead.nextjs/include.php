<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loader::registerAutoLoadClasses(
    'sneakerhead.nextjs',
    array()
);

if (!($_REQUEST['lang_code']) || $_REQUEST['lang_code'] == '') {
    $_REQUEST['lang_code'] = 'ru';
}
if (isset($_SESSION['lang']) && $_SESSION['lang'] != '') {
    $_REQUEST['lang_code'] = $_SESSION['lang'];
}

Loc::setCurrentLang($_REQUEST['lang_code']);