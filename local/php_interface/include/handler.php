<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Loader;

if (Loader::includeModule("manom.moysklad")) {
    $handler = new \Manom\Moysklad\Handler();
    $handler->process();
}
