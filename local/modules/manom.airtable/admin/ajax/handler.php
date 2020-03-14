<?php

use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_CHECK', true);

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

$modulePermissions = $APPLICATION::GetGroupRight('main');
if ($modulePermissions < 'W') {
    die(json_encode(array('error' => true)));
}

$request = Application::getInstance()->getContext()->getRequest();
if (!$request->isAjaxRequest()) {
    die(json_encode(array('error' => true)));
}

Loader::includeModule('manom.airtable');