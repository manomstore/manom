<?php

namespace Manom\Moysklad\Bitrix;

use \Bitrix\Sale;
use \Bitrix\Sale\Compatible\OrderCompatibility;
use Manom\Moysklad\Moysklad\CustomerOrder;

/**
 * Class Order
 * @package Manom\Moysklad\Bitrix
 */
class Order
{
    /** @var Sale\Order|null */
    private $order = null;
    /** @var CustomerOrder|null */
    private $customerOrder = null;

    /**
     * Order constructor.
     * @param int $orderId
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Exception
     */
    public function __construct(int $orderId)
    {
        $this->order = Sale\Order::load($orderId);
        if (empty($this->order)) {
            throw new \Exception();
        }
    }

    /**
     * @param CustomerOrder $customerOrder
     */
    public function setCustomerOrder(CustomerOrder $customerOrder): void
    {
        $this->customerOrder = $customerOrder;
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
    public function updateBasket(): void
    {
        if ($this->customerOrder === null) {
            return;
        }

        /** @var Sale\Basket $basket */
        /** @var Sale\BasketItem $basketItem */
        $basket = $this->order->getBasket();

        foreach ($basket as $basketItem) {
            if (!$this->customerOrder->existProductId((int)$basketItem->getProductId())) {
                $basketItem->delete();
            }
        }

        foreach ($this->customerOrder->getPositions() as $orderPosition) {
            if (empty($orderPosition) || !is_object($orderPosition)) {
                continue;
            }

            $productId = $this->customerOrder->getIdByXmlId($orderPosition->externalCode);
            if (empty($productId)) {
                continue;
            }

            $basketItem = null;

            /** @var Sale\BasketItem $item */
            foreach ($basket as $item) {
                if ((int)$item->getProductId() === $productId) {
                    $basketItem = $item;
                }
            }

            if ($basketItem) {
                $basketItem->setField("QUANTITY", $orderPosition->quantity);
            } else {
                $basketItem = $basket->createItem('catalog', $productId);
                $basketItem->setFields(
                    [
                        'QUANTITY' => $orderPosition->quantity,
                        'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                        'LID' => \Bitrix\Main\Context::getCurrent()->getSite(),
                        'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                    ]
                );

            }
            $basketItem->save();
        }

        $basket->refresh();
        $basket->save();
        $this->order->refreshData();
        $this->order->doFinalAction();
        $discount = $this->order->getDiscount();
        \Bitrix\Sale\DiscountCouponsManager::clearApply(true);
        \Bitrix\Sale\DiscountCouponsManager::useSavedCouponsForApply(true);
        $discount->setOrderRefresh(true);
        $discount->setApplyResult(array());

        $discount->calculate();

        if (!$this->order->isPaid()) {
            /** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
            if (($paymentCollection = $this->order->getPaymentCollection()) && count($paymentCollection) == 1) {
                /** @var \Bitrix\Sale\Payment $payment */
                if (($payment = $paymentCollection->rewind()) && !$payment->isPaid()) {
                    $payment->setFieldNoDemand('SUM', $this->order->getPrice());
                }
            }
        }

        $this->order->save();
    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Exception
     */
    public function updateCancel(): void
    {
        $status = $this->customerOrder->getStatus();
        if (empty($status) || !is_object($status)) {
            return;
        }

        if ($status->name === "Отказ клиента" && !$this->order->isCanceled()) {
            OrderCompatibility::cancel($this->order->getId(), "Y");
        }

        if ($status->name !== "Отказ клиента" && $this->order->isCanceled()) {
            OrderCompatibility::cancel($this->order->getId(), "N");
        }
    }
}