<?php

namespace Manom\Store;


use Manom\Basket;
use Manom\Product;


/**
 * Class Amount
 * @package Manom\Store
 */
class Amount
{
    /**
     * @param array $productsId
     * @param bool $checkInBasket
     * @return array
     */
    public static function getAmounts(array $productsId, bool $checkInBasket = false): array
    {
        $storeList = StoreList::getInstance();
        $amounts = [];

        foreach ($productsId as $id) {
            foreach ($storeList->getStores() as $store) {
                /** @var StoreItem $store */
                $amounts[$id][$store->getId()] = 0;
            }
        }

        if (!empty($storeList->getStoreIds())) {
            $filter = ['PRODUCT_ID' => $productsId, "STORE_ID" => $storeList->getStoreIds()];
            $select = ['ID', 'PRODUCT_ID', 'STORE_ID', 'AMOUNT'];
            $result = \CCatalogStoreProduct::GetList([], $filter, false, false, $select);
            while ($row = $result->Fetch()) {
                /** @var StoreItem $store */
                $store = $storeList->getStoreById($row['STORE_ID']);
                if (!empty($store)) {
                    $amounts[$row['PRODUCT_ID']][$store->getId()] = (int)$row['AMOUNT'];
                }
            }
        }

        $count = static::getReservedCount($productsId, $checkInBasket);

        foreach ($amounts as $productId => $storesAmount) {
            foreach (array_merge($storeList->getMain(), $storeList->getRrc()) as $store) {
                if (empty($count[$productId])) {
                    continue;
                }

                if ($count[$productId] >= $storesAmount[$store->getId()]) {
                    $count[$productId] -= $storesAmount[$store->getId()];
                    $storesAmount[$store->getId()] = 0;
                } else {
                    $storesAmount[$store->getId()] -= $count[$productId];
                    $count[$productId] = 0;
                }
            }

            $amounts[$productId] = $storesAmount;
        }

        return $amounts;
    }

    /**
     * @param int $productId
     * @return int
     */
    public static function getAvailableQuantity(int $productId): int
    {
        $result = 0;

        $storeList = StoreList::getInstance();
        $amounts = static::getAmounts([$productId]);
        $amounts = $amounts[$productId];

        foreach ($storeList->getStores() as $store) {
            /** @var StoreItem $store */
            if ($store->isRrc() || $store->isMain()) {
                $result += $amounts[$store->getId()];
            }
        }
        return $result;
    }

    /**
     * @param array $productsId
     * @param bool $checkInBasket
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \Manom\Exception
     */
    private static function getReservedCount(array $productsId, $checkInBasket = false): array
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
}