<?php

namespace Manom;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;

/**
 * Class Price
 * @package Manom
 */
class Price
{
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
        $this->userGroups = $USER->GetUserGroupArray();
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
        $aSelect = array('ID');
        $oDbRes = \CCatalogGroup::GetList(array(), $aFilter, false, false, $aSelect);
        while ($aDbRes = $oDbRes->Fetch()) {
            $this->pricesId[] = $aDbRes['ID'];
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
    public function getItemPrices($iItemId, $iIblockId, $aPricesId, $userGroups): array
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
}
