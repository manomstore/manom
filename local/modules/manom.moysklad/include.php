<?php

use \Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'manom.moysklad',
    array(
        'eventHandlers' => 'classes/general/eventHandlers.php',
        'Manom\Moysklad\Agent' => 'lib/Agent.php',
        'Manom\Moysklad\EventTable' => 'lib/EventTable.php',
        'Manom\Moysklad\Handler' => 'lib/Handler.php',
        'Manom\Moysklad\Bitrix\Order' => 'lib/bitrix/order.php',
//        'Manom\Moysklad\Moysklad\Entity\CustomerOrder' => 'lib/moysklad/entity/customerOrder.php',
//        'Manom\Moysklad\Moysklad\Entity\Payment' => 'lib/moysklad/entity/payment.php',
//        'Manom\Moysklad\Moysklad\Entity\Shipment' => 'lib/moysklad/entity/shipment.php',
//        'Manom\Moysklad\Moysklad\Entity\BaseEntity' => 'lib/moysklad/entity/baseEntity.php',
    )
);

require_once 'vendor/autoload.php';
