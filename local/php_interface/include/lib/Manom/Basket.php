<?php

namespace Manom;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;

/**
 * Class Basket
 * @package Manom
 */
class Basket
{
    /**
     * Basket constructor.
     * @throws Exception
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('sale')) {
            throw new Exception('Не подключен модуль sale');
        }
    }

    /**
     * @param array|int $productsId
     * @return array
     */
    public function getInBasketCount($productsId): array
    {
        $items = array();

        foreach ($productsId as $id) {
            $items[$id] = 0;
        }

        $filter = array('PRODUCT_ID' => $productsId, 'ORDER_ID' => false);
        $select = array('ID', 'PRODUCT_ID', 'QUANTITY');
        $result = \CSaleBasket::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $items[$row['PRODUCT_ID']] += $row['QUANTITY'];
        }

        return $items;
    }
}
