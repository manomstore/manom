<?php

namespace Manom\Service;


use \Bitrix\Sale\Delivery\Services\Table as ServicesTable;


class Delivery
{
    private $deliveryIds = [
        "ownDelivery"       => null,
        "ownDeliveryRegion" => null,
        "ownPickup"         => null,
        "cdekDelivery"      => null,
        "cdekPickup"        => null,
    ];

    public function __construct()
    {
        $rsDeliveries = ServicesTable::getList([
            'filter' => [
                'ACTIVE' => 'Y',
            ],
        ]);
        $deliveries = $rsDeliveries->fetchAll();
        foreach ($deliveries as $delivery) {
            if (array_key_exists($delivery["XML_ID"], $this->deliveryIds)) {
                $this->deliveryIds[$delivery["XML_ID"]] = (int)$delivery["ID"];
            }
        }
    }

    public function getId($deliveryCode)
    {
        $deliveryId = $this->deliveryIds[$deliveryCode];
        if (!isset($deliveryId) || empty($deliveryId)) {
            return false;
        }

        return $deliveryId;
    }

    public function getAll()
    {
        return $this->deliveryIds;
    }
}