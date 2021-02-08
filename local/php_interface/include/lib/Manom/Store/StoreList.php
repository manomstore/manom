<?php

namespace Manom\Store;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use Manom\Exception;


/**
 * Class StoreList
 * @package Manom\Store
 */
class StoreList
{
    /**
     * @var array
     */
    private $stores = [];
    /**
     * @var array
     */
    private $priceTypes = [
        "main" => 'Цена продажи',
        "rrc"  => 'РРЦ',
    ];

    /**
     * @var null
     */
    private static $instance = null;

    /**
     * StoreList constructor.
     * @throws Exception
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('catalog')) {
            throw new Exception('Не подключен модуль catalog');
        }

        $this->initStores();
    }

    /**
     *
     */
    private function initStores(): void
    {
        $result = \CCatalogStore::GetList(
            [
                "SORT" => "ASC"
            ],
            [
                "ACTIVE" => "Y",
            ],
            false,
            false,
            [
                "ID",
                "SORT",
                "UF_CODE",
                "UF_AS_MAIN",
            ]
        );

        while ($row = $result->GetNext()) {

            $storeItem = new StoreItem($row);
            if ($storeItem->isEmpty()) {
                continue;
            }
            $storeItem->setTypePrice($this->priceTypes);
            $this->stores[$storeItem->getId()] = $storeItem;
        }
    }

    /**
     * @param $storeId
     * @return StoreItem|null
     */
    public function getStoreById($storeId): ?StoreItem
    {
        $storeId = (int)$storeId;

        $store = current($this->filter(function (StoreItem $store) use ($storeId) {
            return $store->getId() === $storeId;
        }));


        if (empty($store)) {
            return null;
        }

        return $store;
    }

    /**
     * @return array
     */
    public function getStores(): array
    {
        return $this->stores;
    }

    /**
     * @param array $productsId
     * @return array
     */
    public function getAvailableQuantity(array $productsId): array
    {
        return $productsId;
    }

    /**
     * @return array
     */
    public function getStoreIds(): array
    {
        return array_map(function (StoreItem $store) {
            return $store->getId();
        }, $this->stores);
    }

    /**
     * @return array
     */
    public function getMain(): array
    {
        return $this->filter(function (StoreItem $store) {
            return $store->isMain();
        });
    }

    /**
     * @return array
     */
    public function getRrc(): array
    {
        return $this->filter(function (StoreItem $store) {
            return $store->isRrc();
        });
    }

    /**
     * @return array
     */
    public function getDefects(): array
    {
        return $this->filter(function (StoreItem $store) {
            return $store->isDefects();
        });
    }

    /**
     * @param callable $callback
     * @return array
     */
    private function filter(callable $callback): array
    {
        return array_filter($this->stores, $callback);
    }

    /**
     * @return array
     */
    public function getPriceCodes(): array
    {
        $priceCodes = [];

        foreach ($this->stores as $store) {
            /** @var StoreItem $store */
            if (empty($store->getPriceCode())) {
                continue;
            }

            if (in_array($store->getPriceCode(), $priceCodes, true)) {
                continue;
            }

            $priceCodes[] = $store->getPriceCode();
        }
        return $priceCodes;
    }

    /**
     * @param string $index
     * @return StoreItem
     */
    public function getByindex(string $index): StoreItem
    {
        return $this->stores[$index];
    }

    /**
     * @return StoreList
     * @throws Exception
     * @throws LoaderException
     */
    public static function getInstance(): StoreList
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}
