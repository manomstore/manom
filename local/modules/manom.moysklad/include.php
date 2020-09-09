<?php

use \Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'manom.moysklad',
    array(
        'eventHandlers' => 'classes/general/eventHandlers.php',
        'Manom\Moysklad\Moysklad\CustomerOrder' => 'lib/moysklad/customerOrder.php',
        'Manom\Moysklad\Agent' => 'lib/Agent.php',
    )
);

require_once 'vendor/autoload.php';
