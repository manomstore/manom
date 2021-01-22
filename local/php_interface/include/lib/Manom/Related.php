<?php

namespace Manom;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Manom\References\Color;

/**
 * Class Related
 * @package Manom
 */
class Related
{
    private $iblockId = 6;
    private $relatedPropertiesCodes = array(
        'RELATED_COLOR',
        'RELATED_MEMORY',
        'RELATED_MEMORY2',
        'RELATED_CPU',
        'RELATED_GPU',
        'RELATED_SCREEN',
        'RELATED_LTE',
    );
    private $propertiesMap = array(
        'COLOR' => 'color',
        'MEMORY' => 'memory_size',
        'MEMORY2' => 'SSD_VALUE',
        'CPU' => 'PROCESSOR',
        'GPU' => 'GPU_NAME',
        'SCREEN' => 'SCREEN_SiZE',
        'LTE' => 'chetyreg_lte',
    );

    /**
     * Related constructor.
     * @throws Exception
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception('Не подключен модуль iblock');
        }
    }

    /**
     * @param int $elementId
     * @return array
     * @throws Exception
     * @throws LoaderException
     */
    public function getRelated($elementId): array
    {
        $result = array();

        $relatedElements = $this->getElementRelatedElements($elementId);

        $elementsId = array();
        foreach ($relatedElements as $propertyElements) {
            foreach ($propertyElements as $id) {
                if (!in_array((int)$id, $elementsId, true)) {
                    $elementsId[] = (int)$id;
                }
            }
        }

        if (empty($elementsId)) {
            return $result;
        }

        $relatedElementsData = $this->getRelatedElementsData($elementsId);
        if (empty($elementsId)) {
            return $result;
        }

        foreach ($relatedElements as $property => $propertyElements) {
            foreach ($propertyElements as $id) {
                $code = str_replace('RELATED_', '', $property);

                if (empty($relatedElementsData[$id][$code])) {
                    continue;
                }

                if (isset($result[$property][$relatedElementsData[$id][$code]])) {
                    continue;
                }

                $result[$property][$relatedElementsData[$id][$code]] = array(
                    'elementId' => $id,
                    'url' => $relatedElementsData[$id]['url'],
                    'value' => $relatedElementsData[$id][$code],
                    'current' => (int)$id === (int)$elementId,
                    'canBuy' => $relatedElementsData[$id]['canBuy'],
                );
            }
        }

        if (!empty($result['RELATED_COLOR'])) {
            $result['RELATED_COLOR'] = $this->processColors($result['RELATED_COLOR']);

            if (empty($result['RELATED_COLOR'])) {
                unset($result['RELATED_COLOR']);
            }
        }

        $result = $this->setCanBuy($result);

        return $result;
    }

    /**
     * @param $result
     * @return array
     */
    private function setCanBuy($result): array
    {
        $obProduct = new Product();

        foreach ($result as $property => &$products) {
            $productsId = array_map(function ($item) {
                return (int)$item["elementId"];
            }, $products);

            if (!empty($productsId)) {
                $ecommerceData = $obProduct->getEcommerceData($productsId, $this->iblockId);
            }

            foreach ($products as &$product) {
                $ecommerceProduct = $ecommerceData[$product['elementId']];
                if (!empty($ecommerceProduct)) {
                    if (
                        empty($ecommerceProduct['amounts']['main']) &&
                        empty($ecommerceProduct['amounts']['second']) &&
                        !$ecommerceProduct["preOrder"]["active"]
                    ) {
                        $product["canBuy"] = false;
                    }
                }
            }
            unset($product);
        }
        unset($products);

        return $result;
    }

    /**
     * @param int $elementId
     * @return array
     */
    private function getElementRelatedElements($elementId): array
    {
        $items = array();

        $properties = $this->relatedPropertiesCodes;
        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $elementId);
        $select = array('IBLOCK_ID', 'ID');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($rowResult = $result->getNextElement(true, false)) {
            foreach ($properties as $code) {
                $property = $rowResult->getProperty($code);
                if (!empty($property['VALUE'])) {
                    $items[$code] = $property['VALUE'];
                }
            }
        }

        return $items;
    }

    /**
     * @param array $elementsId
     * @return array
     */
    private function getRelatedElementsData($elementsId): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $elementsId);
        $select = array('IBLOCK_ID', 'ID', 'DETAIL_PAGE_URL', "CATALOG_AVAILABLE");

        foreach ($this->propertiesMap as $code) {
            $select[] = 'PROPERTY_' . $code;
        }

        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->GetNext(false, false)) {
            $items[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'url' => $row['DETAIL_PAGE_URL'],
                'canBuy' => $row['CATALOG_AVAILABLE'] === "Y",
            );
            foreach ($this->propertiesMap as $code => $bitrixCode) {
                $key = 'PROPERTY_' . mb_strtoupper($bitrixCode) . '_VALUE';

                $items[(int)$row['ID']][$code] = $row[$key];
            }
        }

        return $items;
    }

    /**
     * @param array $items
     * @return array
     * @throws Exception
     * @throws LoaderException
     */
    public function processColors($items): array
    {
        $result = array();

        $colors = array();
        foreach ($items as $item) {
            if (!in_array((string)$item['value'], $colors, true)) {
                $colors[] = (string)$item['value'];
            }
        }

        if (empty($colors)) {
            return $result;
        }

        $color = new Color;
        $colorsData = $color->getDataByNames($colors);
        if (empty($colorsData)) {
            return $result;
        }

        foreach ($items as $key => $item) {
            foreach ($colorsData as $colorData) {
                if ($colorData['name'] === $item['value']) {
                    $item['code'] = $colorData['code'];
                    $item['name'] = $colorData['name'];
                    $item['value'] = $colorData['colorCode'];

                    $result[$key] = $item;

                    break;
                }
            }
        }

        return $result;
    }
}
