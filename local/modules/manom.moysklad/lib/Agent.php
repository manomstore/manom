<?php

namespace Manom\Moysklad;

use Manom\Moysklad\Moysklad\CustomerOrder;
use \Manom\Moysklad\Bitrix\Order;
use \Bitrix\Sale;
use \Manom\Price;

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
            static::addLogAfterMSImport("Error " . $e->getMessage() . ", Path:" . $e->getFile() . ":" . $e->getLine());
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

    /**
     * @param string $content
     */
    public static function addLogAfterMSImport(string $content): void
    {
        $logPath = $_SERVER["DOCUMENT_ROOT"] . "/logs/ms_handler.log";
        $log = file_get_contents($logPath);
        $log .= $content . "\n";
        file_put_contents($logPath, date("d.m.Y H:i:s") . " " . $log);
    }
}