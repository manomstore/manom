<?php

namespace Manom\Moysklad;

use Manom\Moysklad\Moysklad\CustomerOrder;
use \Manom\Moysklad\Bitrix\Order;
use \Bitrix\Sale;

class Agent
{
    public static function handleEvents()
    {
        try {
            $events = EventTable::getList()->fetchAll();
            if (empty($events)) {
                throw new \Exception();
            }

            foreach ($events as $event) {
                $customerOrder = new CustomerOrder($event["href_change"]);

                if ($customerOrder->errorRequest) {
                    continue;
                }

                if (!$customerOrder->getId() || empty(Sale\Order::load($customerOrder->getId()))) {
                    EventTable::delete($event["id"]);
                    continue;
                }
                $bitrixOrder = new Order($customerOrder->getId());
                $bitrixOrder->setCustomerOrder($customerOrder);
                $bitrixOrder->updateBasket();
                $bitrixOrder->updateCancel();
                EventTable::delete($event["id"]);
            }
        } catch (\Exception $e) {
        }
        return "\Manom\Moysklad\Agent::handleEvents();";
    }

    public static function afterMSImport()
    {
        $documentRoot = "/var/www/manom";
        $log = __DIR__ . "\n" . dirname(__DIR__);
        file_put_contents($documentRoot . "/logs/agent.txt", $log);
        return "\Manom\Moysklad\Agent::afterMSImport();";
    }
}