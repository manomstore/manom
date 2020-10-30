<?php

namespace Manom\Sale;

/**
 * Class Export
 * @package Manom\Sale
 */
class Export extends \CSaleExport
{
    const EXPORTED_PREFIX = 'EXPORTED_ORDERS_ID';

    static function getOrdersExportedPrefix()
    {
        return self::EXPORTED_PREFIX;
    }

    protected static function saveExportParams(array $arOrder)
    {
        parent::saveExportParams($arOrder);

        if (!is_array($_SESSION["BX_CML2_EXPORT"][self::getOrdersExportedPrefix()])){
            $_SESSION["BX_CML2_EXPORT"][self::getOrdersExportedPrefix()] = [];
        }

        if (!in_array($arOrder["ID"], $_SESSION["BX_CML2_EXPORT"][self::getOrdersExportedPrefix()])) {
            $_SESSION["BX_CML2_EXPORT"][self::getOrdersExportedPrefix()][] = $arOrder["ID"];
        }
    }

    protected static function prepareFilter($arFilter = array())
    {
        return $arFilter;
    }

    protected static function deployScripts($arFilter = array())
    {
        $dateExchange = ConvertTimeStamp(\COption::GetOptionString("sale", "last_export_time_committed_/local/php_interface/1", ""),"FULL");
        $orders = \Bitrix\Sale\Order::getList(["filter"=>["DATE_INSERT"<$dateExchange,"!EXTERNAL_ORDER"=>"Y"],"select"=>["ID"]])->fetchAll();

        foreach ($orders as $orderId){
            $order = \Bitrix\Sale\Order::load($orderId);
            $order->setField("EXTERNAL_ORDER", "Y");
            $order->save();
        }
    }

    public static function setExportedOrders()
    {
        $exportedOrders = $_SESSION["BX_CML2_EXPORT"][\Manom\Sale\Export::getOrdersExportedPrefix()];

        foreach ($exportedOrders as $orderId) {
            try {
                $orderId = (int)$orderId;
                if ($orderId <= 0) {
                    continue;
                }

                $order = \Bitrix\Sale\Order::load($orderId);
                $order->setField("EXTERNAL_ORDER", "Y");
                $order->save();
            } catch (\Exception $e) {
            }
        }

        $_SESSION["BX_CML2_EXPORT"][static::getOrdersExportedPrefix()] = [];
    }

}