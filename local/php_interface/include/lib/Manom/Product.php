<?php

namespace Manom;

use \Bitrix\Catalog\ProductTable;
use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;

class Product
{
    /**
     * Product constructor.
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
     * @param array|int $productsId
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getProductsReservedQuantity($productsId): array
    {
        $items = array();

        foreach ($productsId as $id) {
            $items[$id] = 0;
        }

        $result = ProductTable::getList(
            array(
                'filter' => array('ID' => $productsId),
                'select' => array('ID', 'QUANTITY_RESERVED'),
            )
        );
        while ($row = $result->Fetch()) {
            $items[$row['ID']] = $row['QUANTITY_RESERVED'];
        }

        return $items;
    }

    /**
     * @param array $productsId
     * @param int $iblockId
     * @return array
     * @throws ArgumentException
     * @throws Exception
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getEcommerceData($productsId, $iblockId): array
    {
        $data = array();

        $store = new Store;
        $storeSettings = $store->getSettings();
        $amounts = $store->getAmounts($productsId);

        $priceCodes = array();
        foreach ($storeSettings as $storeSetting) {
            if (in_array($storeSetting['priceCode'], $priceCodes, true)) {
                continue;
            }

            $priceCodes[] = $storeSetting['priceCode'];
        }

        $priceObject = new Price;
        $userGroups = $priceObject->getUserGroups();
        $priceObject->setPricesIdByName($priceCodes);
        $pricesId = $priceObject->getPricesId();

        foreach ($productsId as $productId) {
            $prices = $priceObject->getItemPrices($productId, $iblockId, $pricesId, $userGroups);

            $storeData = array();

            foreach ($amounts[$productId] as $code => $amount) {
                $storeData[$code] = array(
                    'amount' => $amount,
                    'price' => array(),
                );

                $priceId = $pricesId[$storeSettings[$code]['priceCode']];
                foreach ($prices as $price) {
                    if ((int)$price['CATALOG_GROUP_ID'] === (int)$priceId) {
                        $storeData[$code]['price'] = $price;
                    }
                }
            }

            $data[$productId] = array(
                'amounts' => $amounts[$productId],
                'prices' => $prices,
                'storeData' => $storeData,
            );
        }

        return $data;
    }
}
