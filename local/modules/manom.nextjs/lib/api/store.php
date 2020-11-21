<?php

namespace Manom\Nextjs\Api;

use \Manom\Nextjs\Utils;
use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;

/**
 * Class Store
 * @package Manom\Nextjs\Api
 */
class Store
{
    /**
     * Store constructor.
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
     * @param array $storesId
     * @return array
     */
    public function getStoresById($storesId)
    {
        $stores = array();

        $order = array('SORT' => 'DESC', 'ID' => 'DESC');
        $filter = array('ACTIVE' => 'Y', 'ID' => $storesId, 'ISSUING_CENTER' => 'Y', '+SITE_ID' => SITE_ID);
        $select = array(
            'ID',
            'TITLE',
            'ADDRESS',
            'DESCRIPTION',
            'PHONE',
            'SCHEDULE',
            'GPS_N',
            'GPS_S',
            'ISSUING_CENTER',
        );
        $result = \CCatalogStore::GetList($order, $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $stores[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'name' => $row['TITLE'],
                'description' => $row['DESCRIPTION'],
                'address' => $row['ADDRESS'],
                'phone' => Utils::formatPhone($row['PHONE']),
                'schedule' => $row['SCHEDULE'],
                'gps' => (!empty($row['GPS_S']) && !empty($row['GPS_N'])) ? $row['GPS_S'].','.$row['GPS_N'] : '',
                'issuingCenter' => $row['ISSUING_CENTER'] === 'Y',
            );
        }

        return $stores;
    }

    public function getMain()
    {
        return current($this->getStoresById([1]));
    }
}
