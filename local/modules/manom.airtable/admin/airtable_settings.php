<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Application;
use \Manom\Airtable\Tools;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';

$modulePermissions = $APPLICATION::GetGroupRight('main');
if ($modulePermissions < 'W') {
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

Loader::includeModule('manom.airtable');

$tools = new Tools;
$modulePath = $tools->getModulePath(false);

Asset::getInstance()->addJs($modulePath.'/admin/js/script.js');

$APPLICATION->SetTitle('Настройки');
$APPLICATION->SetAdditionalCSS($modulePath.'/admin/css/style.css');

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

$request = Application::getInstance()->getContext()->getRequest();