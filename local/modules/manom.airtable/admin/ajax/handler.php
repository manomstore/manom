<?php

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    die('No direct script access allowed');
}

use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use \Manom\Airtable\Import;
use \Manom\Airtable\FieldsMap;

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

$message = '';
$post = $request->getPostList()->toArray();

if ($post['action'] === 'all') {
    try {
        $import = new Import();

        if (!$import->process()) {
            die(json_encode(array('error' => true, 'message' => 'Не удалось выполнить импорт')));
        }
    } catch (Exception $e) {
        $errorMes = $e->getMessage();
        if (empty($errorMes)) {
            $errorMes = 'Не удалось выполнить импорт';
        }

        die(json_encode(array('error' => true, 'message' => $errorMes)));
    }

    $message = $import->getBrandResultMessage();
}

if ($post['action'] === 'sections') {
    $post['sections'] = array_filter($post['sections']);

    if (empty($post['sections'])) {
        die(json_encode(array('error' => true, 'message' => 'Не выбраны разделы')));
    }

    try {
        $import = new Import();

        if (!$import->process($post['sections'])) {
            die(json_encode(array('error' => true, 'message' => 'Не удалось выполнить импорт')));
        }
    } catch (Exception $e) {
        $errorMes = $e->getMessage();
        if (empty($errorMes)) {
            $errorMes = 'Не удалось выполнить импорт';
        }

        die(json_encode(array('error' => true, 'message' => $errorMes)));
    }

    $message = $import->getBrandResultMessage();
}

if ($post['action'] === 'element') {
    if (empty($post['xmlId'])) {
        die(json_encode(array('error' => true, 'message' => 'Не указан внешний код')));
    }
}

if ($post['action'] === 'deleteLink') {
    if (empty((int)$post['id'])) {
        die(json_encode(array('error' => true, 'message' => 'Не указан ид привязки')));
    }

    $fieldsMap = new FieldsMap();

    if (!$fieldsMap->deleteLink((int)$post['id'])) {
        die(json_encode(array('error' => true, 'message' => 'Не удалось удалить привязку')));
    }
}

die(json_encode(array('error' => false, 'message' => $message)));