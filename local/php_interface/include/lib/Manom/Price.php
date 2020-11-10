<?php

namespace Manom;

use \Bitrix\Catalog\PriceTable;
use \Bitrix\Currency\CurrencyManager;
use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SiteTable;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;
use \Helper;
use \Bitrix\Catalog\Model;

/**
 * Class Price
 * @package Manom
 */
class Price
{
    const SELLING_TYPE_ID = 1;
    const RRC_TYPE_TYPE_ID = 2;
    const CURRENT_TYPE_ID = 3;

    private $userGroups;
    private $pricesId = array();

    /**
     * Price constructor.
     * @throws Exception
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('catalog')) {
            throw new Exception('Не подключен модуль catalog');
        }

        $this->setUserGroups();
    }

    /**
     *
     */
    private function setUserGroups(): void
    {
        global $USER;

        if (is_object($USER)) {
            $this->userGroups = $USER->GetUserGroupArray();
        }
    }

    /**
     * @return mixed
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }

    /**
     * @param $pricesId
     */
    public function setPricesId($pricesId): void
    {
        $this->pricesId = $pricesId;
    }

    /**
     * @param $pricesName
     */
    public function setPricesIdByName($pricesName): void
    {
        $aFilter = array('NAME' => $pricesName);
        $aSelect = array('ID', 'NAME');
        $oDbRes = \CCatalogGroup::GetList(array(), $aFilter, false, false, $aSelect);
        while ($aDbRes = $oDbRes->Fetch()) {
            $this->pricesId[$aDbRes['NAME']] = $aDbRes['ID'];
        }
    }

    /**
     * @return array
     */
    public function getPricesId(): array
    {
        return $this->pricesId;
    }

    /**
     * @param $iItemId
     * @param $iIblockId
     * @param $aPricesId
     * @param $userGroups
     * @return array
     */
    public function getItemPricesOld($iItemId, $iIblockId, $aPricesId, $userGroups): array
    {
        $aPrices = array(
            'PRICES' => array(),
            'DISCOUNT' => false,
            'DISCOUNT_NAME' => '',
            'DIFFERENCE' => '',
            'DIFFERENCE_PERCENT' => '',
        );

        if (\CCatalogSku::IsExistOffers($iItemId)) {
            $aOffersId = array();
            $iMinimumPrice = 999999999;

            $aFilter = array(
                'CATALOG_AVAILABLE' => 'Y',
            );
            $aSelect = array();
            $aPropertyFilter = array();
            $aOffers = \CCatalogSKU::getOffersList($iItemId, $iIblockId, $aFilter, $aSelect, $aPropertyFilter);
            foreach ($aOffers[$iItemId] as $aOffer) {
                $aOffersId[] = $aOffer['ID'];

                $aOptimalPrice = \CCatalogProduct::GetOptimalPrice($aOffer['ID'], 1, $userGroups);
                if (isset($aOptimalPrice['DISCOUNT']) && !empty($aOptimalPrice['DISCOUNT'])) {
                    $aPrices['DISCOUNT'] = true;
                    $aPrices['DISCOUNT_NAME'] = $aOptimalPrice['DISCOUNT']['NAME'];

                    if ($aOptimalPrice['DISCOUNT_PRICE'] < $iMinimumPrice) {
                        $iMinimumPrice = $aOptimalPrice['DISCOUNT_PRICE'];

                        $aPrices['PRICES'] = array(
                            $aOptimalPrice['DISCOUNT_PRICE'],
                            $aOptimalPrice['RESULT_PRICE']['BASE_PRICE'],
                        );
                        $aPrices['OFFER_ID'] = $aOffer['ID'];
                    }
                }
            }

            if (!empty($aOffersId) && empty($aPrices['PRICES'])) {
                $aOrder = array('PRICE' => 'ASC');
                $aFilter = array('PRODUCT_ID' => $aOffersId, 'CATALOG_GROUP_ID' => $aPricesId);
                $aSelect = array('PRODUCT_ID', 'PRICE');
                $oDbRes = \CPrice::GetList($aOrder, $aFilter, false, false, $aSelect);
                while ($aDbRes = $oDbRes->Fetch()) {
                    $aOffersPrices[$aDbRes['PRODUCT_ID']]['PRICE'][] = $aDbRes['PRICE'];
                }
            }

            if (isset($aOffersPrices) && !empty($aOffersPrices)) {
                foreach ($aOffersPrices as $iProductId => $aOfferPrice) {
                    if ($aOfferPrice['PRICE'][0] < $iMinimumPrice) {
                        $iMinimumPrice = $aOfferPrice['PRICE'][0];
                        $aPrices['PRICES'] = $aOfferPrice['PRICE'];
                        $aPrices['OFFER_ID'] = $iProductId;
                    }
                }
            }
        } else {
            $aOptimalPrice = \CCatalogProduct::GetOptimalPrice($iItemId, 1, $userGroups);
            if (isset($aOptimalPrice['DISCOUNT']) && !empty($aOptimalPrice['DISCOUNT'])) {
                $aPrices['DISCOUNT'] = true;
                $aPrices['DISCOUNT_NAME'] = $aOptimalPrice['DISCOUNT']['NAME'];
                $aPrices['PRICES'] = array(
                    $aOptimalPrice['DISCOUNT_PRICE'],
                    $aOptimalPrice['RESULT_PRICE']['BASE_PRICE'],
                );
            } else {
                $aOrder = array('PRICE' => 'ASC');
                $aFilter = array('PRODUCT_ID' => $iItemId, 'CATALOG_GROUP_ID' => $aPricesId);
                $aSelect = array('PRICE');
                $oDbRes = \CPrice::GetList($aOrder, $aFilter, false, false, $aSelect);
                while ($aDbRes = $oDbRes->Fetch()) {
                    $aPrices['PRICES'][] = $aDbRes['PRICE'];
                }
            }
        }

        if (count($aPrices['PRICES']) > 1) {
            $aPrices['DIFFERENCE'] = $aPrices['PRICES'][1] - $aPrices['PRICES'][0];
            $aPrices['DIFFERENCE_PERCENT'] = ceil(($aPrices['DIFFERENCE'] / $aPrices['PRICES'][1]) * 100);
        }

        return $aPrices;
    }

    /**
     * @param array|int $itemsId
     * @param array $pricesId
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getItemsPrices($itemsId, $pricesId): array
    {
        $prices = array();

        $result = PriceTable::getList(
            array(
                'order' => array('PRICE' => 'ASC'),
                'filter' => array('PRODUCT_ID' => $itemsId, 'CATALOG_GROUP_ID' => $pricesId),
                'select' => array('ID', 'PRODUCT_ID', 'CATALOG_GROUP_ID', 'PRICE', 'CURRENCY'),
            )
        );
        while ($row = $result->fetch()) {
            $prices[(int)$row['PRODUCT_ID']][] = array(
                'ID' => (int)$row['ID'],
                'CATALOG_GROUP_ID' => (int)$row['CATALOG_GROUP_ID'],
                'PRICE' => (int)$row['PRICE'],
                'CURRENCY' => $row['CURRENCY'],
            );
        }

        return $prices;
    }

    /**
     * @param int $itemId
     * @param int $iblockId
     * @param array $userGroups
     * @param array $price
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getItemPriceWithDiscount($itemId, $iblockId, $userGroups, $price): array
    {
        $price['ELEMENT_IBLOCK_ID'] = $iblockId;
        $siteId = SITE_ID;

        if (defined("ADMIN_SECTION") || \Helper::isImport()) {
            $siteId = SiteTable::getList(["order" => ["SORT" => "ASC"]])->fetch()["LID"];
        }

        $optimalPrice = \CCatalogProduct::GetOptimalPrice($itemId, 1, $userGroups, 'N', [$price], $siteId);
        if (empty($optimalPrice['DISCOUNT'])) {
            $price['DISCOUNT_PRICE'] = $price['PRICE'];
            $price['DISCOUNT'] = false;
            $price['DISCOUNT_NAME'] = '';
        } else {
            $price['DISCOUNT_PRICE'] = (int)$optimalPrice['DISCOUNT_PRICE'];
            $price['DISCOUNT'] = true;
            $price['DISCOUNT_NAME'] = $optimalPrice['DISCOUNT']['NAME'];
        }

        return $price;
    }

    /**
     * @param int $itemId
     * @param int $iblockId
     * @param array $pricesId
     * @param array $userGroups
     * @return array
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getItemPrices($itemId, $iblockId, $pricesId, $userGroups): array
    {
        $prices = array();

        $existOffers = \CCatalogSKU::getExistOffers($itemId, $iblockId);
        if ($existOffers[$itemId]) {
            $offersId = array();
            $offersIblockId = 0;
            $filter = array('CATALOG_AVAILABLE' => 'Y');
            $select = array('IBLOCK_ID', 'ID');
            $offers = \CCatalogSKU::getOffersList($itemId, $iblockId, $filter, $select, array());
            foreach ($offers[$itemId] as $offer) {
                if (empty($offersIblockId)) {
                    $offersIblockId = (int)$offer['IBLOCK_ID'];
                }
                $offersId[] = (int)$offer['ID'];
            }

            if (!empty($offersId)) {
                $prices = $this->getItemsPrices($offersId, $pricesId);
                foreach ($prices as $offerId => $offersPrices) {
                    foreach ($offersPrices as $i => $price) {
                        $prices[$offerId][$i] = $this->getItemPriceWithDiscount(
                            $offerId,
                            $offersIblockId,
                            $userGroups,
                            $price
                        );
                    }
                }

                $minimumPrice = 999999999;
                $minimumPrices = array();
                foreach ($prices as $offerId => $offersPrices) {
                    foreach ($offersPrices as $i => $price) {
                        if ($price['DISCOUNT_PRICE'] < $minimumPrice) {
                            $minimumPrice = $price['DISCOUNT_PRICE'];
                            $minimumPrices = $offersPrices;
                        }
                    }
                }

                $prices = $minimumPrices;
            }
        } else {
            $prices = current($this->getItemsPrices($itemId, $pricesId));
            foreach ($prices as $i => $price) {
                $prices[$i] = $this->getItemPriceWithDiscount($itemId, $iblockId, $userGroups, $price);
            }
        }

        return $prices;
    }

    /**
     * @param array $productsId
     * @throws ArgumentException
     * @throws Exception
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function recalculateTypeCurrent(array $productsId): void
    {
        $product = new Product();
        $ecommerceData = $product->getEcommerceData($productsId, Helper::CATALOG_IB_ID);
        foreach ($ecommerceData as $productId => $item) {
            $prices = Content::getPricesFromStoreData($item['storeData']);
            $this->updatePrice($productId, $prices["price"], static::CURRENT_TYPE_ID);
        }
    }

    /**
     * @param int $productId
     * @param float $value
     * @param int $typeId
     * @return bool
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public function updatePrice(int $productId, float $value, int $typeId): bool
    {
        $res = Model\Price::getList(
            [
                "filter" => [
                    "PRODUCT_ID"       => $productId,
                    "CATALOG_GROUP_ID" => $typeId
                ]
            ]
        );

        if ($price = $res->Fetch()) {
            $price["PRICE"] = $value;
            $result = Model\Price::Update($price["ID"], $price);
            $success = $result->isSuccess();
        } else {
            $result = Model\Price::Add([
                "PRODUCT_ID"       => $productId,
                "CATALOG_GROUP_ID" => $typeId,
                "PRICE"            => $value,
                "CURRENCY"         => CurrencyManager::getBaseCurrency(),
            ]);
            $success = $result->isSuccess();
        }

        return $success;
    }
}
