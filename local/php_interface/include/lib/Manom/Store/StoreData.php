<?php

namespace Manom\Store;

/**
 * Class StoreData
 * @package Manom\Store
 */
class StoreData
{
    /** @var StoreList */
    private $storeList = null;
    /**
     * @var array
     */
    private $data = [];
    /**
     * @var array
     */
    private $defaultStoreData = [
        'amount' => 0,
        'price'  => [],
    ];

    /**
     * StoreData constructor.
     * @throws \Bitrix\Main\LoaderException
     * @throws \Manom\Exception
     */
    public function __construct()
    {
        $this->storeList = StoreList::getInstance();
        foreach ($this->storeList->getStores() as $store) {
            /** @var StoreItem $store */
            $this->data[$store->getId()] = $this->defaultStoreData;
        }
    }

    /**
     * @param string $index
     * @param int $amount
     * @return bool
     */
    public function setAmount(string $index, int $amount): bool
    {
        if (!array_key_exists($index, $this->data)) {
            return false;
        }

        $this->data[$index]["amount"] = $amount;
        return true;
    }

    /**
     * @param string $index
     * @param array $price
     * @return bool
     */
    public function setPrice(string $index, array $price): bool
    {
        if (!array_key_exists($index, $this->data)) {
            return false;
        }

        $this->data[$index]["price"] = $price;
        return true;
    }

    /**
     * @return array
     */
    public function getPrices(): array
    {
        $return = [
            'price'    => 0,
            'oldPrice' => 0,
        ];

        $mainStoreData = $this->getMain();
        $rrcStoreData = $this->getRrc();

        if (
            !empty($mainStoreData['price']['DISCOUNT_PRICE']) &&
            $mainStoreData['price']['DISCOUNT_PRICE'] !== $mainStoreData['price']['PRICE']
        ) {
            $mainPrice = $mainStoreData['price']['DISCOUNT_PRICE'];
        } else {
            $mainPrice = $mainStoreData['price']['PRICE'];
        }

        if (
            !empty($rrcStoreData['price']['DISCOUNT_PRICE']) &&
            $rrcStoreData['price']['DISCOUNT_PRICE'] !== $rrcStoreData['price']['PRICE']
        ) {
            $rrcPrice = $rrcStoreData['price']['DISCOUNT_PRICE'];
        } else {
            $rrcPrice = $rrcStoreData['price']['PRICE'];
        }

        if (!empty($mainStoreData['amount']) && !empty($rrcStoreData['amount'])) {
            $return['price'] = $mainPrice;
            $return['oldPrice'] = $rrcPrice;
        } elseif (!empty($mainStoreData['amount'])) {
            $return['price'] = $mainPrice;
        } elseif (!empty($rrcStoreData['amount'])) {
            $return['price'] = $rrcPrice;
        } elseif ($mainPrice > $rrcPrice) {
            $return['price'] = $mainPrice;
        } else {
            $return['price'] = $rrcPrice;
        }

        if ($return['price'] === $return['oldPrice']) {
            $return['oldPrice'] = 0;
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function canBuy(): bool
    {
        $mainStore = $this->getMain();
        $rrcStore = $this->getRrc();

        return $mainStore["amount"] > 0 || $rrcStore["amount"] > 0;
    }

    /**
     * @return array
     */
    public function getMain(): array
    {
        $result = [];
        foreach ($this->storeList->getMain() as $store) {
            /** @var StoreItem $store */

            $storeData = $this->data[$store->getId()];
            if (empty($storeData)) {
                continue;
            }

            $result = $storeData;

            if ($storeData["amount"] > 0) {
                $result["store"] = $store;
                break;
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getRrc(): array
    {
        $result = [];
        foreach ($this->storeList->getRrc() as $store) {
            /** @var StoreItem $store */

            $storeData = $this->data[$store->getId()];
            if (empty($storeData)) {
                continue;
            }

            $result = $storeData;

            if ($storeData["amount"] > 0) {
                $result["store"] = $store;
                break;
            }
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function existDefects(): bool
    {
        $quantity = 0;
        foreach ($this->storeList->getDefects() as $defectsStore) {
            /** @var StoreItem $defectsStore */
            $quantity += $this->data[$defectsStore->getId()]["amount"];
        }

        return $quantity > 0;
    }

    /**
     * @return bool
     */
    public function isUnlimited(): bool
    {
        $mainStore = $this->getMain();
        $rrcStore = $this->getRrc();

        return $mainStore["amount"] <= 0 && $rrcStore["amount"] > 0;
    }
}
