<?php

namespace Manom;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;
use Bitrix\Sale\BasketItem;
use Manom\Store\StoreData;
use Manom\Store\StoreItem;
use \Manom\Custom;

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

    /**
     * @return array
     * @throws Exception
     * @throws LoaderException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getUserOutOfStockProducts(): array
    {
        $productsOutOfStock = array();
        $productsId = array();
        $productsPriceId = array();

        $filter = array('FUSER_ID' => \CSaleBasket::GetBasketUserID(), 'ORDER_ID' => false);
        $select = array('ID', 'PRODUCT_ID', 'PRODUCT_PRICE_ID');
        $result = \CSaleBasket::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $productsId[] = (int)$row['PRODUCT_ID'];
            $productsPriceId[$row['PRODUCT_ID']] = (int)$row['PRODUCT_PRICE_ID'];
        }

        $product = new Product;
        $ecommerceData = $product->getEcommerceData($productsId, 6);

        foreach ($ecommerceData as $productId => $productEcommerceData) {
            /** @var StoreData $storeData */
            $storeData = $productEcommerceData['storeData'];
            $mainStore = $storeData->getMain();

            if (
                $mainStore['price']['ID'] === $productsPriceId[$productId] &&
                empty($mainStore['amount']) &&
                !$productEcommerceData["isService"]
            ) {
                $productsOutOfStock[] = $productId;
            }
        }

        return $productsOutOfStock;
    }

    /**
     * @param int $productId
     * @return int
     */
    public static function getAssemblyTimeData($productId = 0): int
    {
        try {
            $basket = new Custom\Basket(0, $productId);
            $ecommerceData = (new Product())->getEcommerceData($basket->getOffersId(), \Helper::CATALOG_IB_ID);

            $commonAssemblyTime = 0;
            foreach ($basket->getBitrixBasket()->getBasketItems() as $item) {
                /** @var BasketItem $item */
                /** @var StoreData $storeData */
                $storeData = $ecommerceData[$item->getField("PRODUCT_ID")]['storeData'];
                $mainStore = $storeData->getMain();
                $rrcStore = $storeData->getRrc();

                /** @var StoreItem|null $currentStore */
                $currentStore = null;
                if ((int)$mainStore['price']['ID'] === (int)$item->getField('PRODUCT_PRICE_ID')) {
                    $currentStore = $mainStore["store"];
                } elseif ((int)$rrcStore['price']['ID'] === (int)$item->getField('PRODUCT_PRICE_ID')) {
                    $currentStore = $rrcStore["store"];
                }

                $assemblyTime = $currentStore instanceof StoreItem ?
                    $currentStore->getAssemblyTime() : 0;

                if ($assemblyTime > $commonAssemblyTime) {
                    $commonAssemblyTime = $assemblyTime;
                }
            }
        } catch (\Exception $e) {
            $commonAssemblyTime = 0;
        }
        return $commonAssemblyTime;
    }
}
