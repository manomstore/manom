<?php

namespace Manom\Service;


use Bitrix\Main\Loader;
use Bitrix\Sale\Internals\OrderPropsTable;
use Bitrix\Sale\Internals\OrderPropsVariantTable;
use UniPlug\Settings;

class TimeDelivery
{

    private static $variants = null;
    private static $propertyCode = "TIME_DELIVERY";
    private static $settingsKey = "DELIVERY_RANGES";

    private static function deleteVariants($propertyId)
    {
        $variants = OrderPropsVariantTable::getList(
            [
                "filter" => [
                    "ORDER_PROPS_ID" => $propertyId
                ]
            ]
        )->fetchAll();

        foreach ($variants as $variant) {
            OrderPropsVariantTable::delete($variant["ID"]);
        }
    }

    private static function createVariants($propertyId)
    {
        if (empty(self::getVariants())) {
            return;
        }

        foreach (self::getVariants() as $key => $variant) {
            $propVariant = [
                "ORDER_PROPS_ID" => $propertyId,
                "NAME" => $variant["formatted"],
                "VALUE" => $key + 1,
            ];
            OrderPropsVariantTable::add($propVariant);
        }
    }

    public static function OnBeforeSettingsUpdateHandler($arFields)
    {
        global $APPLICATION;
        $validError = false;
        $deliveryRanges = $arFields["UF_DELIVERY_RANGES"];
        try {
            $deliveryRanges = array_filter($deliveryRanges, function ($field) {
                return !empty($field);
            });

            if (empty($deliveryRanges)) {
                throw new \Exception("Не заполнено ни одного интервала");
            }

            $lastIntervalTo = null;

            foreach ($deliveryRanges as $variant) {
                $variant = explode("-", $variant);
                $interval = [
                    "from" => array_shift($variant),
                    "to" => array_shift($variant),
                ];

                if (empty((int)$interval["from"]) && $interval["from"] !== "0") {
                    $validError = true;
                }

                if (empty((int)$interval["to"]) && $interval["to"] !== "0") {
                    $validError = true;
                }

                $interval["to"] = (int)$interval["to"];
                $interval["from"] = (int)$interval["from"];

                if ($lastIntervalTo !== null && ($interval["from"] < $lastIntervalTo)) {
                    throw new \Exception("Некорректный порядок интервалов");
                }

                if ($interval["from"] < 0 || $interval["from"] > 23) {
                    $validError = true;
                }

                if ($interval["to"] < 0 || $interval["to"] > 23) {
                    $validError = true;
                }

                if ($interval["from"] >= $interval["to"]) {
                    $validError = true;
                }

                if ($validError) {
                    throw new \Exception("Один из интервалов заполнен некорректно");
                }
                $lastIntervalTo = $interval["to"];
            }
        } catch (\Exception $e) {
            $APPLICATION->ThrowException('Ошибка в поле "Диапазоны времени для доставки" - ' . $e->getMessage());
            return false;
        }
    }

    public static function OnAfterSettingsUpdateHandler()
    {
        Loader::includeModule("sale");
        if (empty(self::getVariants())) {
            return;
        }

        $orderProps = OrderPropsTable::getList(
            [
                "filter" => [
                    'CODE' => self::$propertyCode,
                ]
            ]
        )->fetchAll();

        foreach ($orderProps as $orderProp) {
            self::deleteVariants($orderProp["ID"]);
            self::createVariants($orderProp["ID"]);
        }
    }

    public static function setVariants()
    {
        Loader::includeModule("germen.settings");
        self::$variants = [];
        $ranges = Settings::get(self::$settingsKey);
        foreach ($ranges as $key => $range) {
            if (empty($range)) {
                continue;
            }

            $parseRange = explode("-", $range);
            self::$variants[] = [
                "selected" => ($key + 1) === 1,
                "value" => $key + 1,
                "from" => array_shift($parseRange),
                "to" => array_shift($parseRange),
            ];
        }

        foreach (self::$variants as &$variant) {
            if (empty($variant["from"]) || empty($variant["to"])) {
                continue;
            }

            $variant["formatted"] = "c {$variant["from"]}:00 до {$variant["to"]}:00";
        }
    }

    public static function getVariants()
    {
        if (self::$variants === null) {
            self::setVariants();
        }

        return self::$variants;
    }

    public static function getIntervals()
    {
        $result = [];
        foreach (self::getVariants() as $key => $variant) {
            $result[$key + 1] = $variant["from"];
        }

        return $result;
    }
}