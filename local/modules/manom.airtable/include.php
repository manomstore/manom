<?php

use \Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'manom.airtable',
    array(
        'eventHandlers' => 'classes/general/eventHandlers.php',
    )
);

require_once 'vendor/autoload.php';
