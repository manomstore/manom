<?php

namespace Manom\Moysklad;

use Manom\Moysklad\Moysklad\CustomerOrder;
use \Manom\Moysklad\Bitrix\Order;
use \Bitrix\Sale;
use \Manom\Price;
use Manom\Tools;

class Agent
{
    /**
     * @return string
     */
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

    /**
     * @return string
     */
    public static function afterMSImport(): string
    {
        try {
            $product = new Product;
            $price = new Price();
            $product->updateProperties();
            $product->updateYMarketFields();
            $price->processingChanges((new \Manom\Product())->getAll());
            static::setActiveAfterMSImport(false);
        } catch (\Exception $e) {
            Tools::addToLog("Error " . $e->getMessage() . ", Path:" . $e->getFile() . ":" . $e->getLine(), "ms_handler");
        }

        return "\Manom\Moysklad\Agent::afterMSImport();";
    }

    /**
     * @param bool $active
     */
    public static function setActiveAfterMSImport(bool $active): void
    {

        $agent = \CAgent::GetList([], ["NAME" => "\Manom\Moysklad\Agent::afterMSImport();"])->GetNext();
        if (empty($agent)) {
            return;
        }
        $agentId = (int)$agent["ID"];
        if ($agentId <= 0) {
            return;
        }

        \CAgent::Update($agentId, ["ACTIVE" => $active === true ? "Y" : "N"]);
    }
}