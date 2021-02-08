<?php

namespace Manom;

use \Bitrix\Catalog\ProductTable;
use Bitrix\Iblock\ElementTable;
use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;
use Manom\Store\Amount;
use Manom\Store\StoreData;
use Manom\Store\StoreList;

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
    public function getProductsQuantity($productsId): array
    {
        $items = array();

        foreach ($productsId as $id) {
            $items[$id] = 0;
        }

        $result = ProductTable::getList(
            array(
                'filter' => array('ID' => $productsId),
                'select' => array('ID', 'QUANTITY'),
            )
        );
        while ($row = $result->Fetch()) {
            $items[$row['ID']] = $row['QUANTITY'];
        }

        return $items;
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
        $services = array();

        if (!empty($productsId)) {
            $productsData = ElementTable::getList([
                "filter" => ["ID" => $productsId],
                "select" => ["ID", "IBLOCK_ID"]
            ])->fetchAll();

            $productsData = array_filter($productsData, function ($item) {
                return (int)$item["IBLOCK_ID"] === \Helper::SERVICE_IB_ID;
            });

            $services = array_map(function ($item) {
                return (int)$item["ID"];
            }, $productsData);
        }

        $storeList = StoreList::getInstance();
        $amounts = Amount::getAmounts($productsId);
        $preOrder = new PreOrder($productsId);

        $priceObject = new Price;
        $userGroups = $priceObject->getUserGroups();
        $priceObject->setPricesIdByName($storeList->getPriceCodes());
        $pricesId = $priceObject->getPricesId();

        foreach ($productsId as $productId) {
            $prices = $priceObject->getItemPrices($productId, $iblockId, $pricesId, $userGroups);

            $storeData = new StoreData();

            foreach ($amounts[$productId] as $index => $amount) {
                $storeData->setAmount($index, $amount);

                $priceCode = $storeList->getByindex($index)->getPriceCode();
                if (empty($priceCode)) {
                    continue;
                }

                $priceId = $pricesId[$priceCode];
                foreach ($prices as $price) {
                    if ((int)$price['CATALOG_GROUP_ID'] === (int)$priceId) {
                        $storeData->setPrice($index, $price);
                    }
                }
            }

            $data[$productId] = array(
                'amounts' => $amounts[$productId],
                'prices' => $prices,
                'storeData' => $storeData,
                'preOrder' => $preOrder->getByProductId($productId),
            );

            if (in_array($productId, $services)) {
                $data[$productId]["isService"] = true;
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $rsProducts = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => \Helper::CATALOG_IB_ID],
            false,
            false,
            [
                'ID',
                'IBLOCK_ID',
            ]
        );
        while ($arProduct = $rsProducts->GetNext()) {
            $arProductIds[] = $arProduct['ID'];
        }
        return $arProductIds;
    }
}
