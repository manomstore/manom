<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use Manom\Moysklad\Agent;
use Manom\Moysklad\Product;

/**
 * Class eventHandlers
 */
class eventHandlers
{
    /**
     * @param array $arParams
     * @param array $arFields
     * @throws LoaderException
     * @throws SystemException
     */
    public static function OnSuccessCatalogImport1C($arParams, $filename): void
    {
        $isOffers = strpos($filename, 'offers') !== false;

        if (!$isOffers) {
            return;
        }

        if (Loader::includeModule('manom.moysklad')) {
            Agent::setActiveAgent(true, "afterMSImport");
        }
    }
}
