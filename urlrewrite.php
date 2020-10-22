<?php

$arUrlRewrite = array(
    array(
        'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
        'RULE' => 'alias=$1',
        'ID' => null,
        'PATH' => '/desktop_app/router.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/bitrix/services/ymarket/#',
        'RULE' => '',
        'ID' => '',
        'PATH' => '/bitrix/services/ymarket/index.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/online/(/?)([^/]*)#',
        'RULE' => '',
        'ID' => null,
        'PATH' => '/desktop_app/router.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/stssync/calendar/#',
        'RULE' => '',
        'ID' => 'bitrix:stssync.server',
        'PATH' => '/bitrix/services/stssync/calendar/index.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/articles/#',
        'RULE' => '',
        'ID' => 'bitrix:news',
        'PATH' => '/articles/index.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/catalog/#',
        'RULE' => '',
        'ID' => 'bitrix:catalog',
        'PATH' => '/catalog/index.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^(/search|/search/)#',
        'RULE' => '',
        'ID' => 'bitrix:catalog',
        'PATH' => '/catalog/index.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/rest/#',
        'RULE' => '',
        'ID' => null,
        'PATH' => '/bitrix/services/rest/index.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^/#',
        'RULE' => '',
        'ID' => 'bitrix:main.register',
        'PATH' => '/auth/register.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^#',
        'RULE' => '',
        'ID' => 'bitrix:catalog.section',
        'PATH' => '/bitrix/templates/manom/header.php',
        'SORT' => 100,
    ),
    array(
        'CONDITION' => '#^(.*)/#',
        'RULE' => '/static$1.php',
        'SORT' => 100,
    ),
);
