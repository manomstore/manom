<?php

namespace Manom;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;

/**
 * Class Store
 * @package Manom
 */
class Store
{
    private $settings = array(
        'main' => array(
            'id' => 1,
            'priceCode' => 'Цена продажи',
        ),
        'second' => array(
            'priceCode' => 'РРЦ',
        ),
    );

    /**
     * Store constructor.
     * @throws Exception
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('catalog')) {
            throw new Exception('Не подключен модуль catalog');
        }
    }

    /**
     * @param int $storeId
     * @return bool
     */
    private function isMain($storeId): bool
    {
        return $storeId === $this->settings['main']['id'];
    }

    /**
     * @param array|int $productsId
     * @param bool $checkInBasket
     * @return array
     * @throws ArgumentException
     * @throws Exception
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getReservedCount($productsId, $checkInBasket = false): array
    {
        $basket = new Basket;
        $product = new Product;

        $items = $product->getProductsReservedQuantity($productsId);

        if ($checkInBasket) {
            $itemsInBasket = $basket->getInBasketCount($productsId);

            foreach ($items as $productId => $count) {
                if (empty($itemsInBasket[$productId])) {
                    continue;
                }

                $items[$productId] += $itemsInBasket[$productId];
            }
        }

        return $items;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * @param array|int $productsId
     * @param bool $checkInBasket
     * @return array
     * @throws ArgumentException
     * @throws Exception
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getAmounts($productsId, $checkInBasket = false): array
    {
        $amounts = array();

        foreach ($productsId as $id) {
            $amounts[$id] = array(
                'main' => 0,
                'second' => 0,
            );
        }

        $storesId = [];

        $result = \CCatalogStore::GetList([], ["ACTIVE" => "Y"]);

        while ($row = $result->Fetch()) {
            $storesId[] = (int)$row["ID"];
        }

        if (!empty($storesId)) {
            $filter = ['PRODUCT_ID' => $productsId, "STORE_ID" => $storesId];
            $select = ['ID', 'PRODUCT_ID', 'STORE_ID', 'AMOUNT'];
            $result = \CCatalogStoreProduct::GetList([], $filter, false, false, $select);
            while ($row = $result->Fetch()) {
                if ($this->isMain((int)$row['STORE_ID'])) {
                    $amounts[$row['PRODUCT_ID']]['main'] = $row['AMOUNT'];
                } else {
                    $amounts[$row['PRODUCT_ID']]['second'] = $row['AMOUNT'];
                }
            }
        }

        $count = $this->getReservedCount($productsId, $checkInBasket);

        foreach ($amounts as $productId => $storesAmount) {
            if (empty($count[$productId])) {
                continue;
            }

            if ($count[$productId] >= $storesAmount['main']) {
                $count[$productId] -= $storesAmount['main'];
                $storesAmount['main'] = 0;
            } else {
                $storesAmount['main'] -= $count[$productId];
                $count[$productId] = 0;
            }

            if (!empty($count[$productId])) {
                if ($count[$productId] >= $storesAmount['second']) {
                    $count[$productId] -= $storesAmount['second'];
                    $storesAmount['second'] = 0;
                } else {
                    $storesAmount['second'] -= $count[$productId];
                    $count[$productId] = 0;
                }
            }

            $amounts[$productId] = $storesAmount;
        }

        return $amounts;
    }
}
