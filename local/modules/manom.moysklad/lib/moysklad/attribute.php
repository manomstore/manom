<?php

namespace Manom\Moysklad\Moysklad;

use Manom\Moysklad\Tools;

/**
 * Class Attribute
 * @package Manom\Moysklad\Moysklad
 */
class Attribute
{
    /**
     * @var array
     */
    private $list = [];

    /**
     * @var array
     */
    private $dimensionsFields = [
        "width",
        "height",
        "weight",
        "package",
    ];

    /**
     * Attribute constructor.
     * @param $fields
     */
    public function __construct($fields)
    {
        $attributes = Tools::sendRequest("https://online.moysklad.ru/api/remap/1.2/entity/product/metadata/attributes");
        $list = &$this->list;

        array_walk($attributes->rows, function ($attribute) use (&$list, $fields) {
            $key = array_search($attribute->name, $fields);
            if ($key !== false) {
                $attribute->meta = (array)$attribute->meta;
                $list[$key] = $attribute;
            }
        });
        unset($list);
    }

    /**
     * @param $msProduct
     * @param $bitrixProduct
     * @return array
     */
    public function getForUpdate($msProduct, $bitrixProduct): array
    {
        $attributes = [];
        $dimensions = [];

        foreach ($this->list as $code => $attributeData) {
            $msProductValue = $this->getAttributeValue($msProduct, $attributeData->name);

            if (in_array($code, $this->dimensionsFields)) {
                $dimensions[$code] = $msProductValue;
            }

            if (!is_array($bitrixProduct) || !array_key_exists($code, $bitrixProduct)) {
                continue;
            }

            $bitrixProductValue = (string)$bitrixProduct[$code];

            if ($msProductValue !== $bitrixProductValue) {
                $attributes[] = [
                    "meta"  => $attributeData->meta,
                    "value" => $bitrixProductValue,
                ];
            }
        };

        $this->addDimensionsForUpdate($attributes, $dimensions);

        return $attributes;
    }

    /**
     * @param $msProduct
     * @param $attributeName
     * @return string
     */
    private function getAttributeValue($msProduct, $attributeName): string
    {
        $msAttrs = [];
        if (!empty($msProduct->fields->attributes->attrs)) {
            $msAttrs = $msProduct->fields->attributes->attrs;
        }
        if (!is_array($msAttrs)) {
            $msAttrs = [];
        }

        $msAttr = array_filter($msAttrs, function ($attr) use ($attributeName) {
            if (!is_object($attr)) {
                return false;
            }

            return $attr->name === $attributeName;
        });

        $msAttr = current($msAttr);
        $msProductValue = is_object($msAttr) ? (string)$msAttr->value : "";

        return $msProductValue;
    }

    /**
     * @param $attributes
     * @param $dimensions
     */
    private function addDimensionsForUpdate(&$attributes, $dimensions): void
    {
        $packageData = [];
        $parsePackage = explode("/", $dimensions["package"]);
        $packageData["length"] = (float)$parsePackage[0];
        $packageData["width"] = (float)$parsePackage[1];
        $packageData["height"] = (float)$parsePackage[2];

        $packageData = array_filter($packageData, function ($val) {
            return $val > 0;
        });

        $isValidPackageData = count($packageData) === 3;

        $checkForUpdate = [
            "width",
            "height",
        ];

        if ($isValidPackageData) {
            foreach ($checkForUpdate as $code) {
                if ((float)$dimensions[$code] !== $packageData[$code] && is_array($this->list[$code]->meta)) {
                    $attributes[] = [
                        "meta"  => $this->list[$code]->meta,
                        "value" => (string)$packageData[$code],
                    ];
                }
            }
        }
    }
}
