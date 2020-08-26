<?php

use \Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'manom.airtable',
    array(
        'eventHandlers' => 'classes/general/eventHandlers.php',
        '\Manom\Airtable\Parser\Review' => 'lib/parser/Review.php',
        '\Manom\Airtable\Parser\Question' => 'lib/parser/Question.php',
    )
);

require_once 'vendor/autoload.php';
