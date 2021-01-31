<?php

namespace Manom;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\EventManager;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Sale\Result;
use Manom\Store\StoreData;

/**
 * Class CatalogProvider
 * @package Manom
 */
class CatalogProvider extends \Bitrix\Catalog\Product\CatalogProvider
{
    private static $productsPrice = array();

    /**
     * @param array $products
     * @return Result
     * @throws ArgumentException
     * @throws Exception
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getProductData(array $products): Result
    {
        return self::customGetData($products, __FUNCTION__);
    }

    /**
     * @param array $products
     * @return Result
     * @throws ArgumentException
     * @throws Exception
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getCatalogData(array $products): Result
    {
        return self::customGetData($products, __FUNCTION__);
    }

    /**
     * @param array $products
     * @param string $methodName
     * @return Result
     * @throws Exception
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function customGetData(array $products, $methodName): Result
    {
        $productsId = array();
        foreach ($products as $product) {
            $productsId[] = $product['PRODUCT_ID'];
        }

        $product = new Product;
        $ecommerceData = $product->getEcommerceData($productsId, 6);

        $userBasketProductsPriceId = self::getUserBasketProductsPriceId();

        $productsPrice = array();
        foreach ($products as $product) {
            /** @var StoreData $storeData */
            $storeData = $ecommerceData[$product['PRODUCT_ID']]['storeData'];
            $mainStore = $storeData->getMain();
            $rrcStore = $storeData->getRrc();

            if (
                !empty($userBasketProductsPriceId[$product['PRODUCT_ID']]) &&
                (int)$mainStore['price']['ID'] === $userBasketProductsPriceId[$product['PRODUCT_ID']]
            ) {
                $productsPrice[$product['PRODUCT_ID']] = $mainStore['price'];
            } elseif (empty($mainStore['amount'])) {
                $productsPrice[$product['PRODUCT_ID']] = $rrcStore['price'];
            } else {
                $productsPrice[$product['PRODUCT_ID']] = $mainStore['price'];
            }
        }

        self::$productsPrice = $productsPrice;

        $eventManager = EventManager::getInstance();
        $eventManager->addEventHandler(
            'catalog',
            'OnGetOptimalPrice',
            '\\'.static::class.'::onGetOptimalPriceHandler'
        );

        $arResult = parent::$methodName($products);

        $eventManager->removeEventHandler(
            'catalog',
            'OnGetOptimalPrice',
            '\\'.static::class.'::onGetOptimalPriceHandler'
        );

        return $arResult;
    }

    /**
     * @param int $productId
     * @param int $quantity
     * @param array $userGroups
     * @param string $renewal
     * @param array $prices
     * @param bool $siteId
     * @param bool $coupons
     * @return array|bool
     */
    public function onGetOptimalPriceHandler(
        $productId,
        $quantity = 1,
        $userGroups = array(),
        $renewal = 'N',
        $prices = array(),
        $siteId = false,
        $coupons = false
    ) {
        if (empty(self::$productsPrice[$productId])) {
            return true;
        }

        return array('PRICE' => self::$productsPrice[$productId]);
    }

    /**
     * @return array
     */
    private static function getUserBasketProductsPriceId(): array
    {
        $items = array();

        $filter = array(
            'FUSER_ID' => \CSaleBasket::GetBasketUserID(),
            'LID' => SITE_ID,
            'ORDER_ID' => 'NULL',
        );
        $select = array('ID', 'PRODUCT_ID', 'PRODUCT_PRICE_ID', 'PRICE_TYPE_ID');
        $result = \CSaleBasket::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $items[(int)$row['PRODUCT_ID']] = (int)$row['PRODUCT_PRICE_ID'];
        }

        return $items;
    }
}
