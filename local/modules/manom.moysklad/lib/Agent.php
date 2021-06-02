<?php

namespace Manom\Moysklad;

use Manom\Moysklad\Moysklad\EventHandler;
use \Manom\Price;
use Manom\Tools;

class Agent
{
    /**
     * @return string
     */
    public static function handleEvents(): string
    {
        try {
            $events = EventTable::getList()->fetchAll();
            if (!is_array($events)) {
                $events = [];
            }

            foreach ($events as $event) {
                EventHandler::process($event);
            }

            if (empty(EventTable::getList()->fetchAll())) {
                Agent::setActiveAgent(false, "handleEvents");
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
            static::setActiveAgent(false, "afterMSImport");
        } catch (\Exception $e) {
            Tools::errorToLog($e, "ms_handler");
        }

        return "\Manom\Moysklad\Agent::afterMSImport();";
    }

    /**
     * @param bool $active
     */
    public static function setActiveAgent(bool $active, string $agentName): void
    {
        $agent = \CAgent::GetList([], ["NAME" => '\\' . static::class . "::{$agentName}();"])->GetNext();
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