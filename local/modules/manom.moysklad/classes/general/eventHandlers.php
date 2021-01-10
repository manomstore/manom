<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
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
    public static function OnSuccessCatalogImport1C($arParams, $arFields): void
    {
        if(Loader::includeModule('manom.moysklad')) {
            $product = new Product;
            $product->updateProperties();
        }
    }
}
