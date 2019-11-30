<?php

namespace Manom\Nextjs\Api;

use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;
use \Bitrix\Sale\Services\PaySystem\Restrictions\Manager;

/**
 * Class PaySystem
 * @package Manom\Nextjs\Api
 */
class PaySystem
{
    /**
     * PaySystem constructor.
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
     * @param int $paySystemId
     * @return array
     */
    public function getRestrictions($paySystemId)
    {
        $restrictions = array();

        $result = new \CDBResult;
        $result->InitFromArray(Manager::getRestrictionsList($paySystemId));
        while ($row = $result->Fetch()) {
            if (!is_array($row['PARAMS'])) {
                $row['PARAMS'] = array();
            }

            $row['PARAMS'] = $row['CLASS_NAME']::prepareParamsValues($row['PARAMS'], $paySystemId);

            if (!empty($row['PARAMS']['CURRENCY'])) {
                $restrictions['currency'] = $row['PARAMS']['CURRENCY'];
            }
            if ($row['CLASS_NAME'] === '\Bitrix\Sale\Services\PaySystem\Restrictions\Price') {
                $restrictions['price']['min'] = (int)$row['PARAMS']['MIN_VALUE'];
                $restrictions['price']['max'] = (int)$row['PARAMS']['MAX_VALUE'];
            }
            if (!empty($row['PARAMS']['SITE_ID'])) {
                $restrictions['siteId'] = $row['PARAMS']['SITE_ID'];
            }
		        if (!empty($row['PARAMS']['DELIVERY'])) {
			        $restrictions['delivery'] = $row['PARAMS']['DELIVERY'];
		        }
		        if (!is_array($restrictions['delivery'])) {
			        $restrictions['delivery'] = (array) $restrictions['delivery'];
		        }

		        foreach ($restrictions['delivery'] as &$delivery) {
			        $delivery = (int) $delivery;
		        }

		        unset($delivery);

            if (!empty($row['PARAMS']['PERSON_TYPE_ID'])) {
                $restrictions['personTypeId'] = $row['PARAMS']['PERSON_TYPE_ID'];
            }
            if ($row['CLASS_NAME'] === '\Bitrix\Sale\Services\PaySystem\Restrictions\PercentPrice') {
                $restrictions['pricePercent']['min'] = (int)$row['PARAMS']['MIN_VALUE'];
                $restrictions['pricePercent']['max'] = (int)$row['PARAMS']['MAX_VALUE'];
            }
        }

        return $restrictions;
    }

    /**
     * @param bool $skipInternalAccount
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function getList($request = array(),$skipInternalAccount = true)
    {
        $items = array();

        $isNotRuID = (int)(new Location())->getByName('Беларусь')["id"];

        $result = \Bitrix\Sale\Internals\PaySystemActionTable::getList(array(
	          'order'  => array(
	          	  'SORT' => 'ASC'
	          ),
            'filter' => array(
                'ACTIVE' => 'Y',
            ),
            'select' => array('ID', 'PAY_SYSTEM_ID', 'NAME', 'PSA_NAME', 'CODE', 'DESCRIPTION','ACTION_FILE'),
        ));
        while ($row = $result->fetch()) {
            if ($skipInternalAccount && (int)$row['PAY_SYSTEM_ID'] === 0) {
                continue;
            }

            if ($isNotRuID === (int)$request["locationId"] && $row["ACTION_FILE"] === "cash") {
                continue;
            }

            $restrictions = $this->getRestrictions($row['PAY_SYSTEM_ID']);

            $items[] = array(
                'id' => (int)$row['PAY_SYSTEM_ID'],
                'code' => $row['CODE'],
                'name' => $row['NAME'],
                'note' => ($row['PSA_NAME'] !== $row['NAME']) ? $row['PSA_NAME'] : '',
                'description' => $row['DESCRIPTION'],
                'deliveries' => $restrictions['delivery'],
            );
        }

        return $items;
    }
}
