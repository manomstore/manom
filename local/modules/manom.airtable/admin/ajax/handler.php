<?php

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    die('No direct script access allowed');
}

use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_CHECK', true);

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

$modulePermissions = $APPLICATION::GetGroupRight('main');
if ($modulePermissions < 'W') {
    die(json_encode(array('error' => true, 'message' => 'Permission denied')));
}

$request = Application::getInstance()->getContext()->getRequest();
if (!$request->isAjaxRequest()) {
    die(json_encode(array('error' => true, 'No direct script access allowed')));
}

if (!Loader::includeModule('manom.airtable')) {
    die(json_encode(array('error' => true, 'Не подключен модуль manom.airtable')));
}

$post = $request->getPostList()->toArray();

if ($post['action'] === 'all') {
}

if ($post['action'] === 'sections') {
    $post['sections'] = array_filter($post['sections']);

    if (empty($post['sections'])) {
        die(json_encode(array('error' => true, 'message' => 'Не выбраны разделы')));
    }
}

if ($post['action'] === 'element') {
    if (empty($post['xmlId'])) {
        die(json_encode(array('error' => true, 'message' => 'Не указан внешний код')));
    }
}

die(json_encode(array('error' => false, 'message' => '')));