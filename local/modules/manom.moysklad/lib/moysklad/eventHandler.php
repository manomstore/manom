<?php

namespace Manom\Moysklad\Moysklad;

use \Manom\Moysklad\Bitrix\Order;
use \Bitrix\Sale;
use Manom\Moysklad\EventTable;
use Manom\Moysklad\Moysklad\Entity\CustomerOrder;
use Manom\Moysklad\Moysklad\Entity\Payment;
use Manom\Moysklad\Moysklad\Entity\Shipment;

/**
 * Class EventHandler
 * @package Manom\Moysklad\Moysklad
 */
class EventHandler
{
    /**
     * @var array
     */
    private $event;

    /**
     * @param array $event
     */
    public static function process(array $event): void
    {
        $instance = new static();
        $instance->event = $event;

        if ($instance->checkEventType("customerorder", "UPDATE")) {
            $instance->changeOrderEvent();
        } elseif ($instance->checkEventType("paymentin", "CREATE")) {
            $instance->createPaymentEvent();
        } elseif ($instance->checkEventType("demand", "CREATE")) {
            $instance->createShipmentEvent();
        } elseif ($instance->checkEventType("customerorder", "CREATE")) {
            $instance->createOrderEvent();
        }
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     * @throws \Exception
     */
    private function changeOrderEvent(): void
    {
        $customerOrder = new CustomerOrder($this->event["href_entity"]);

        if ($customerOrder->hasOtherError()) {
            return;
        }

        if ($customerOrder->hasNotFoundError() || empty(Sale\Order::load($customerOrder->getId()))) {
            $this->removeEvent();
            return;
        }

        $bitrixOrder = new Order($customerOrder->getId());
        $bitrixOrder->setCustomerOrder($customerOrder);
        $bitrixOrder->updateBasket();
        $bitrixOrder->updateCancel();
        $this->removeEvent();
    }

    /**
     * @throws \Exception
     */
    private function createPaymentEvent(): void
    {
        $payment = new Payment($this->event["href_entity"]);

        if ($payment->hasOtherError()) {
            return;
        }

        if ($payment->hasNotFoundError()) {
            $this->removeEvent();
            return;
        }

        if ($payment->isCreatedByYMarket()) {
            $payment->delete();
        }

        if (!$payment->hasOtherError()) {
            $this->removeEvent();
        }
    }

    /**
     * @throws \Exception
     */
    private function createShipmentEvent(): void
    {
        $shipment = new Shipment($this->event["href_entity"]);

        if ($shipment->hasOtherError()) {
            return;
        }

        if ($shipment->hasNotFoundError()) {
            $this->removeEvent();
            return;
        }

        if ($shipment->isCreatedByYMarket()) {
            $shipment->delete();
        }

        if (!$shipment->hasOtherError()) {
            $this->removeEvent();
        }
    }

    /**
     * @throws \Exception
     */
    private function createOrderEvent(): void
    {
        $customerOrder = new CustomerOrder($this->event["href_entity"]);

        if ($customerOrder->hasOtherError()) {
            return;
        }

        if ($customerOrder->hasNotFoundError()) {
            $this->removeEvent();
            return;
        }

        if ($customerOrder->isCreatedByYMarket()) {
            $customerOrder->changeReserves("set");
        }

        if ($customerOrder->isPreOrder()) {
            $customerOrder->changeReserves("reset");
        }

        if (!$customerOrder->hasOtherError()) {
            $this->removeEvent();
        }
    }

    /**
     * @param string $entity
     * @param string $type
     * @return bool
     */
    private function checkEventType(string $entity, string $type): bool
    {
        $event = $this->event;
        return $event["entity"] === $entity && $event["type"] === $type;
    }

    /**
     * @throws \Exception
     */
    private function removeEvent(): void
    {
        EventTable::delete($this->event["id"]);
    }
}