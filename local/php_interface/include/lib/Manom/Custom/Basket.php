<?php

namespace Manom\Custom;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Context;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\NotSupportedException;
use Bitrix\Main\ObjectNotFoundException;
use Bitrix\Main\SystemException;
use Bitrix\Sale\BasketItemBase;
use Manom\CatalogProvider;
use \Manom\Nextjs\Api;
use Manom\Price;

/**
 * Class Basket
 * @package Manom\Custom
 */
class Basket extends Api\Basket
{
    /**
     * Basket constructor.
     * @param int $orderId
     * @param int $productId
     * @throws ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     * @throws ObjectNotFoundException
     * @throws SystemException
     */
    public function __construct($orderId = 0, $productId = 0)
    {
        parent::__construct($orderId, $productId);
    }

    /**
     * @param $productId
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws NotImplementedException
     * @throws NotSupportedException
     * @throws ObjectNotFoundException
     */
    protected function addBasketItem($productId): void
    {
        if ((int)$productId <= 0) {
            return;
        };

        if ($this->basket) {
            Price::$useRealPriceType = true;
            $tmpBasketItem = $this->addTmpBasketItem($productId);
            Price::$useRealPriceType = false;
            if ($item = $this->basket->getExistsItem('catalog', $productId)) {
                if ($item->getField("PRODUCT_PRICE_ID") !== $tmpBasketItem->getField('PRODUCT_PRICE_ID')) {
                    $item->delete();
                }
            }

            if ($item = $this->basket->getExistsItem('catalog', $productId)) {
                $item->setField('QUANTITY', $item->getQuantity() + 1);
            } else {
                $item = $this->basket->createItem('catalog', $productId);
                Price::$useRealPriceType = true;
                $item->setFields([
                    'QUANTITY'               => 1,
                    'CURRENCY'               => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                    'LID'                    => \Bitrix\Main\Context::getCurrent()->getSite(),
                    'PRODUCT_PROVIDER_CLASS' => CatalogProvider::class,
                ]);
                Price::$useRealPriceType = false;
            }
        }
    }

    /**
     * @param $productId
     * @return BasketItemBase
     * @throws ArgumentException
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentTypeException
     * @throws NotImplementedException
     * @throws NotSupportedException
     */
    private function addTmpBasketItem($productId): BasketItemBase
    {
        $tmpBasket = \Bitrix\Sale\Basket::create(Context::getCurrent()->getSite());
        $item = $tmpBasket->createItem('catalog', $productId);
        $item->setFields([
            'QUANTITY'               => 1,
            'CURRENCY'               => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
            'LID'                    => \Bitrix\Main\Context::getCurrent()->getSite(),
            'PRODUCT_PROVIDER_CLASS' => CatalogProvider::class,
        ]);

        return $item;
    }
}
