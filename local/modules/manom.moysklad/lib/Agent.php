<?php

namespace Manom\Moysklad;


use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketItem;
use Bitrix\Sale\Order;
use Manom\Moysklad\Moysklad\CustomerOrder;
use \Bitrix\Sale\Compatible\OrderCompatibility;

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
                $orderId = $customerOrder->getId();
                $order = Order::load($orderId);
                if (empty($order)) {
                    throw new \Exception();
                }

                $status = $customerOrder->getStatus();
                if ($status->name === "Отменен" && !$order->isCanceled()) {
                    OrderCompatibility::cancel($order->getId(), "Y");
                }

                if ($status->name !== "Отменен" && $order->isCanceled()) {
                    OrderCompatibility::cancel($order->getId(), "N");
                }

                $positions = $customerOrder->getPositions();

                /** @var Basket $basket */
                /** @var BasketItem $basketItem */
                $basket = $order->getBasket();
                foreach ($basket as $basketItem) {
                    if (!$customerOrder->existProductId((int)$basketItem->getProductId())) {
                        $basketItem->delete();
                    }
                }

                foreach ($positions as $orderPosition) {
                    $productId = $customerOrder->getIdByXmlId($orderPosition->externalCode);
                    if (empty($productId)) {
                        continue;
                    }
                    $basketItem = $basket->getExistsItem("catalog", $productId);
                    if ($basketItem) {
                        $basketItem->setField("QUANTITY", $orderPosition->quantity);
                    } else {
                        $item = $basket->createItem('catalog', $productId);
                        $item->setFields(
                            [
                                'QUANTITY' => $orderPosition->quantity,
                                'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                                'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
                                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                            ]
                        );

                        $basket->addItem($item);
                    }
                }
                $basket->save();
                $order->save();

                EventTable::delete($event["id"]);
            }
        } catch (\Exception $e) {
        }
        return "\Manom\Moysklad\Agent::handleEvents();";
    }
}