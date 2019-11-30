<?php

namespace Sneakerhead\Nextjs\Api;

use \Sneakerhead\Nextjs\Utils;
use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;
use \Bitrix\Sale\Delivery\Restrictions\Manager;
use \Bitrix\Sale\Internals\ServiceRestrictionTable;
use \Bitrix\Sale\Delivery\DeliveryLocationTable;
use \Bitrix\Sale\Delivery\DeliveryLocationExcludeTable;
use \Bitrix\Sale\Delivery\Services\Table as ServicesTable;

/**
 * Class Delivery
 * @package Sneakerhead\Nextjs\Api
 */
class Delivery
{
    /**
     * Delivery constructor.
     * @throws SystemException
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('sale')) {
            throw new SystemException('Не подключен модуль sale');
        }
    }

    /**
     * @param int $deliveryId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getRestrictions($deliveryId)
    {
        $restrictions = array();

        $result = ServiceRestrictionTable::getList(array(
            'filter' => array(
                '=SERVICE_ID' => $deliveryId,
                '=SERVICE_TYPE' => Manager::SERVICE_TYPE_SHIPMENT,
            ),
            'select' => array('ID', 'CLASS_NAME', 'SORT', 'PARAMS'),
            'order' => array('SORT' => 'ASC', 'ID' => 'DESC'),
        ));
        $data = $result->fetchAll();

        $result = new \CDBResult;
        $result->InitFromArray($data);
        while ($row = $result->Fetch()) {
            if (!is_array($row['PARAMS'])) {
                $row['PARAMS'] = array();
            }

            $row['PARAMS'] = $row['CLASS_NAME']::prepareParamsValues($row['PARAMS'], $deliveryId);

            if (!empty($row['PARAMS']['PUBLIC_SHOW'])) {
                $restrictions['publicShow'] = $row['PARAMS']['PUBLIC_SHOW'];
            }
            if ($row['CLASS_NAME'] === '\Bitrix\Sale\Delivery\Restrictions\ByPrice') {
                $restrictions['price']['min'] = (int)$row['PARAMS']['MIN_PRICE'];
                $restrictions['price']['max'] = (int)$row['PARAMS']['MAX_PRICE'];
                $restrictions['price']['currency'] = $row['PARAMS']['CURRENCY'];
            }
            if ($row['CLASS_NAME'] === '\Bitrix\Sale\Delivery\Restrictions\ByDimensions') {
                $restrictions['dimensions']['length'] = (int)$row['PARAMS']['LENGTH'];
                $restrictions['dimensions']['width'] = (int)$row['PARAMS']['WIDTH'];
                $restrictions['dimensions']['height'] = (int)$row['PARAMS']['HEIGHT'];
            }
            if (!empty($row['PARAMS']['MAX_SIZE'])) {
                $restrictions['maxSize'] = (int)$row['PARAMS']['MAX_SIZE'];
            }
            if (!empty($row['PARAMS']['CATEGORIES'])) {
                $restrictions['categories'] = $row['PARAMS']['CATEGORIES'];
            }
            if (!empty($row['PARAMS']['PERSON_TYPE_ID'])) {
                $restrictions['personTypeId'] = $row['PARAMS']['PERSON_TYPE_ID'];
            }
            if ($row['CLASS_NAME'] === '\Bitrix\Sale\Delivery\Restrictions\ByWeight') {
                $restrictions['weight']['min'] = (int)$row['PARAMS']['MIN_WEIGHT'];
                $restrictions['weight']['max'] = (int)$row['PARAMS']['MAX_WEIGHT'];
            }
            if ($row['CLASS_NAME'] === '\Bitrix\Sale\Delivery\Restrictions\ExcludeLocation') {
                $restrictions['excludeLocation'] = $this->getExcludeLocations($deliveryId);
            }
            if (!empty($row['PARAMS']['PAY_SYSTEMS'])) {
                $restrictions['paySystem'] = $row['PARAMS']['PAY_SYSTEMS'];
            }
            if ($row['CLASS_NAME'] === '\Bitrix\Sale\Delivery\Restrictions\ByLocation') {
                $restrictions['location'] = $this->getLocations($deliveryId);
            }
            if (!empty($row['PARAMS']['SITE_ID'])) {
                $restrictions['siteId'] = $row['PARAMS']['SITE_ID'];
            }
        }

        return $restrictions;
    }

    /**
     * @param int $deliveryId
     * @param bool $isExclude
     * @return array
     */
    public function getLocations($deliveryId, $isExclude = false)
    {
        $locations = array();

        $params = array(
            'select' => array('ID', 'COUNTRY_ID', 'REGION_ID', 'CITY_ID', 'NAME' => 'NAME.NAME'),
            'filter' => array('NAME.LANGUAGE_ID' => LANGUAGE_ID),
        );

        if ($isExclude) {
            $result = DeliveryLocationExcludeTable::getConnectedLocations($deliveryId, $params);
        } else {
            $result = DeliveryLocationTable::getConnectedLocations($deliveryId, $params);
        }

        while ($row = $result->fetch()) {
            $locations[] = array(
                'id' => (int)$row['ID'],
            );
        }

        return $locations;
    }

    /**
     * @param int $deliveryId
     * @return array
     */
    public function getExcludeLocations($deliveryId)
    {
        return $this->getLocations($deliveryId, true);
    }

    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getList()
    {
        $items = array();

        $result = ServicesTable::getList(array(
            'filter' => array(
                'ACTIVE' => 'Y',
                '=CLASS_NAME' => '\Bitrix\Sale\Delivery\Services\Configurable',
            ),
            'select' => array('ID', 'NAME', 'DESCRIPTION', 'CONFIG'),
        ));
        while ($row = $result->fetch()) {
            $price = 0;
            if (!empty($row['CONFIG']['MAIN']['PRICE'])) {
                $price = (int)$row['CONFIG']['MAIN']['PRICE'];
            }

            $currency = '';
            if (!empty($row['CONFIG']['MAIN']['CURRENCY'])) {
                $currency = $row['CONFIG']['MAIN']['CURRENCY'];
            }

            $period = array(
                'from' => 0,
                'to' => 0,
                'type' => '',
            );
            if (!empty($row['CONFIG']['MAIN']['PERIOD'])) {
                $period['from'] = (int)$row['CONFIG']['MAIN']['PERIOD']['FROM'];
                $period['to'] = (int)$row['CONFIG']['MAIN']['PERIOD']['TO'];
                $period['type'] = $row['CONFIG']['MAIN']['PERIOD']['TYPE'];
            }

            $restrictions = $this->getRestrictions($row['ID']);

            $items[] = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'description' => $row['DESCRIPTION'],
                'price' => $price,
                'currency' => $currency,
                'period' => $period,
                'paySystems' => $restrictions['paySystem'],
            );
        }

        return $items;
    }

    /**
     * @param array $request
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function getDeliveries($request = array(), $productId = 0)
    {
        $deliveries = array();

        $order = new Order;
        foreach ($order->getDeliveries($request, $productId) as $item) {
            $restrictions = $this->getRestrictions((int)$item['ID']);

            $price = !empty((int)$item['PRICE']) ? (int)$item['PRICE'] : 0;
            $discountPrice = !empty((int)$item['DELIVERY_DISCOUNT_PRICE']) ? (int)$item['DELIVERY_DISCOUNT_PRICE'] : 0;

            $offices = array();
            foreach ($item['office_data'] as $office) {
                $offices[] = array(
                    'id' => $office['id'],
                    'code' => $office['code'],
                    'name' => $office['name'],
                    'address' => $office['address_full'],
                    'phone' => Utils::formatPhone($office['tel']),
                    'schedule' => $office['schedule'],
                    'gps' => $office['gps'],
                    'metro' => $office['metro'],
                );
            }

            $deliveries[] = array(
                'id' => (int)$item['ID'],
                'name' => $item['NAME'],
                'description' => !empty($item['DESCRIPTION']) ? $item['DESCRIPTION'] : '',
                'price' => $price,
                'priceFormat' => number_format($price, 0, '', ' '),
                'discountPrice' => $discountPrice,
                'discountPriceFormat' => number_format($discountPrice, 0, '', ' '),
                'period' => !empty($item['PERIOD_TEXT']) ? $item['PERIOD_TEXT'] : '',
                'selfDeliveryPoints' => !empty($item['STORES']) ? array_values($item['STORES']) : array(),
                'offices' => $offices,
                'paySystems' => !empty($restrictions['paySystem']) ? $restrictions['paySystem'] : array(),
                'locations' => !empty($restrictions['location']) ? $restrictions['location'] : array(),
                'excludeLocations' => !empty($restrictions['excludeLocation']) ? $restrictions['excludeLocation'] : array(),
            );
        }

        return $deliveries;
    }
}
