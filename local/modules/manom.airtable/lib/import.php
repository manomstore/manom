<?php

namespace Manom\Airtable;

use Manom\Airtable\Bitrix\Element;
use Manom\Airtable\Bitrix\Section;
use Manom\Airtable\Bitrix\Property;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentOutOfRangeException;
use \Bitrix\Main\ObjectPropertyException;

/**
 * Class Import
 * @package Manom\Airtable
 */
class Import
{
    private $iblockId = 6;
    private $changeStatus;
    private $map;
    private $enumValues;
    private $propertiesData;
    private $bitrixElements;
    private $bitrixSections;

    /**
     * Import constructor.
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws LoaderException
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     */
    public function __construct()
    {
        $tools = new Tools;

        $this->changeStatus = $tools->getChangeStatus() === 'Y';

        $fieldsMap = new FieldsMap();
        $this->map = $fieldsMap->getMap();

        $property = new Property($this->iblockId);
        $this->enumValues = $property->getEnumValues();

        $propertiesCode = array();
        foreach ($this->map['properties'] as $item) {
            $propertiesCode[] = $item['bitrix'];
        }

        $this->propertiesData = $property->getPropertiesData($propertiesCode);

        $element = new Element($this->iblockId);
        $this->bitrixElements = $element->getItems();

        $section = new Section($this->iblockId);
        $this->bitrixSections = $section->getItems();
    }

    /**
     * @param array $sections
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws LoaderException
     * @throws SystemException
     */
    public function process($sections = array()): bool
    {
        $element = new Element($this->iblockId);

        $api = new Api();

        $airtableData = array();
        if (empty($sections)) {
            $airtableData = $api->getAll();
        } else {
            foreach ($sections as $section) {
                $airtableData[$section] = $api->getFromSection($section);
            }
        }

        $airtableIdToXmlId = $this->getAirtableIdToXmlIdLinks($airtableData);

        $itemsToUpdate = array();
        foreach ($airtableData as $section => $sectionItems) {
            foreach ($sectionItems as $airtableItem) {
                if (empty($this->bitrixElements[$airtableItem['fields']['Внешний код']])) {
                    continue;
                }

                $airtableItem['section'] = $section;

                $itemsToUpdate[] = $this->prepareFields($airtableItem, $airtableIdToXmlId);
            }
        }

        $updated = array();
        foreach ($itemsToUpdate as $fields) {
            if ($element->update($fields)) {
                $updated[$fields['AIRTABLE_SECTION']][] = $fields['AIRTABLE_ID'];
            }
        }

        if ($this->changeStatus) {
            foreach ($updated as $section => $itemsId) {
                $api->setStatus($section, $itemsId);
            }
        }

        return true;
    }

    /**
     * @param array $airtableItem
     * @param array $airtableIdToXmlId
     * @return array
     * @throws LoaderException
     * @throws SystemException
     */
    private function prepareFields($airtableItem, $airtableIdToXmlId): array
    {
        $bitrixItem = $this->bitrixElements[$airtableItem['fields']['Внешний код']];

        $fields = array(
            'ID' => $bitrixItem['id'],
            'AIRTABLE_ID' => $airtableItem['id'],
            'AIRTABLE_SECTION' => $airtableItem['section'],
        );

        foreach ($this->map['fields'] as $bitrix => $airtable) {
            if (!isset($airtableItem['fields'][$airtable])) {
                continue;
            }

            if ($bitrix === 'NAME' && empty($airtableItem['fields'][$airtable])) {
                continue;
            }

            if ($bitrix === 'IBLOCK_SECTION_ID') {
                $sectionId = $this->getSectionId($airtableItem['fields'][$airtable]);
                if (empty($sectionId)) {
                    continue;
                }

                $airtableItem['fields'][$airtable] = $sectionId;
            }

            $fields[$bitrix] = $airtableItem['fields'][$airtable];
        }

        $fields['PREVIEW_PICTURE'] = '';
        $fields['DETAIL_PICTURE'] = '';
        $fields['PROPERTIES'] = array();

        $fields = $this->prepareImages($airtableItem, $fields);

        $properties = $this->prepareProperties($airtableItem, $airtableIdToXmlId);

        $fields['PROPERTIES'] = array_merge($fields['PROPERTIES'], $properties);

        return $fields;
    }

    /**
     * @param array $airtableItem
     * @param array $fields
     * @return array
     */
    private function prepareImages($airtableItem, $fields): array
    {
        foreach ($this->map['properties'] as $item) {
            if (!isset($airtableItem['fields'][$item['airtable']])) {
                continue;
            }

            if ($item['bitrix'] === 'MORE_PHOTO') {
                $airtableItem['fields'][$item['airtable']] = array_reverse($airtableItem['fields'][$item['airtable']]);
                $result = $this->processImages($airtableItem['fields'][$item['airtable']]);

                if (!empty($result['preview'])) {
                    $fields['PREVIEW_PICTURE'] = $result['preview'];
                }

                if (!empty($result['detail'])) {
                    $fields['DETAIL_PICTURE'] = $result['detail'];
                }

                $airtableItem['fields'][$item['airtable']] = $result['images'];
            }

            $fields['PROPERTIES'][$item['bitrix']] = $airtableItem['fields'][$item['airtable']];
        }

        return $fields;
    }

    /**
     * @param array $airtableItem
     * @param array $airtableIdToXmlId
     * @return array
     * @throws LoaderException
     * @throws SystemException
     */
    private function prepareProperties($airtableItem, $airtableIdToXmlId): array
    {
        $items = array();

        foreach ($this->map['properties'] as $item) {
            if (!isset($airtableItem['fields'][$item['airtable']])) {
                continue;
            }

            if ($item['bitrix'] === 'FEATURES2' || $item['bitrix'] === 'contents_of_delivery') {
                $values = explode('–', $airtableItem['fields'][$item['airtable']]);
                $airtableItem['fields'][$item['airtable']] = $values;
            } elseif ($item['bitrix'] === 'features') {
                $airtableItem['fields'][$item['airtable']] = array(
                    'VALUE' => array(
                        'TYPE' => 'HTML',
                        'TEXT' => nl2br($airtableItem['fields'][$item['airtable']]),
                    ),
                );
            } elseif ($this->propertiesData[$item['bitrix']]['type'] === 'L') {
                $result = $this->processListProperty($item['bitrix'], $airtableItem['fields'][$item['airtable']]);

                if (empty($result)) {
                    continue;
                }

                $airtableItem['fields'][$item['airtable']] = $result;
            } elseif ($this->propertiesData[$item['bitrix']]['type'] === 'E') {
                $airtableItem['fields'][$item['airtable']] = $this->processElementLinkProperty(
                    $airtableItem['fields'][$item['airtable']],
                    $airtableIdToXmlId,
                    $this->propertiesData[$item['bitrix']]['multiple']
                );
            } elseif ($item['bitrix'] === 'MORE_PHOTO') {
                continue;
            }

            $items[$item['bitrix']] = $airtableItem['fields'][$item['airtable']];
        }

        return $items;
    }

    /**
     * @param string $name
     * @return int
     */
    private function getSectionId($name): int
    {
        $sectionId = 0;

        foreach ($this->bitrixSections as $section) {
            if ($section['name'] === $name) {
                $sectionId = $section['id'];
                break;
            }
        }

        return $sectionId;
    }

    /**
     * @param array $images
     * @return array
     */
    private function processImages($images): array
    {
        $result = array(
            'preview' => '',
            'detail' => '',
            'images' => array(),
        );

        $tmpImages = array();
        foreach ($images as $image) {
            if (empty($image['url'])) {
                continue;
            }

            $img = \CFile::MakeFileArray($image['url']);
            $img['name'] = $image['filename'];

            $tmpImages[] = $img;
        }

        $first = true;
        foreach ($tmpImages as $image) {
            if ($first) {
                $result['preview'] = $image;
                $result['detail'] = $image;
                $first = false;
            }

            $result['images'][] = array(
                'VALUE' => $image,
                'DESCRIPTION' => '',
            );
        }

        return $result;
    }

    /**
     * @param string $bitrixCode
     * @param string|array $airtableValue
     * @return array|int
     * @throws LoaderException
     * @throws SystemException
     */
    private function processListProperty($bitrixCode, $airtableValue)
    {
        $property = new Property($this->iblockId);

        if ($this->propertiesData[$bitrixCode]['multiple']) {
            $values = array();

            foreach ($airtableValue as $value) {
                $values[$value] = 0;

                foreach ($this->enumValues[$bitrixCode] as $enumValue) {
                    if (trim($enumValue['value']) === trim($value)) {
                        $values[$value] = $enumValue['id'];
                    }
                }
            }

            foreach ($values as $value => $enumId) {
                if (empty($enumId)) {
                    $enumId = $property->addEnumValue($this->propertiesData[$bitrixCode]['id'], trim($value));
                }

                if (empty($enumId)) {
                    unset($values[$value]);
                } else {
                    $values[$value] = $enumId;

                    $this->enumValues[$bitrixCode][$enumId] = array(
                        'id' => $enumId,
                        'xmlId' => trim($value),
                        'value' => trim($value),
                    );
                }
            }

            $result = array_values($values);
        } else {
            $enumId = 0;

            foreach ($this->enumValues[$bitrixCode] as $enumValue) {
                if (trim($enumValue['value']) === trim($airtableValue)) {
                    $enumId = $enumValue['id'];
                }
            }

            if (empty($enumId)) {
                $enumId = $property->addEnumValue($this->propertiesData[$bitrixCode]['id'], trim($airtableValue));
            }

            if (!empty($enumId)) {
                $this->enumValues[$bitrixCode][$enumId] = array(
                    'id' => $enumId,
                    'xmlId' => trim($airtableValue),
                    'value' => trim($airtableValue),
                );
            }

            $result = $enumId;
        }

        return $result;
    }

    /**
     * @param string|array $airtableValue
     * @param array $airtableIdToXmlId
     * @param array $bitrixElements
     * @param bool $multiple
     * @return array
     */
    private function processElementLinkProperty(
        $airtableValue,
        $airtableIdToXmlId,
        $multiple = false
    ): array {
        $result = '';

        if ($multiple) {
            $values = array();
            foreach ($airtableValue as $airtableId) {
                if (empty($airtableIdToXmlId[$airtableId])) {
                    continue;
                }

                if (empty($this->bitrixElements[$airtableIdToXmlId[$airtableId]]['id'])) {
                    continue;
                }

                $values[] = $this->bitrixElements[$airtableIdToXmlId[$airtableId]]['id'];
            }

            $result = $values;
        } elseif (
            !empty($airtableIdToXmlId[$airtableValue]) &&
            !empty($this->bitrixElements[$airtableIdToXmlId[$airtableValue]]['id'])
        ) {
            $result = $this->bitrixElements[$airtableIdToXmlId[$airtableValue]]['id'];
        }

        return $result;
    }

    /**
     * @param array $airtableData
     * @return array
     */
    private function getAirtableIdToXmlIdLinks($airtableData): array
    {
        $items = array();

        foreach ($airtableData as $section => $sectionItems) {
            foreach ($sectionItems as $item) {
                if (empty($item['fields']['Внешний код'])) {
                    continue;
                }

                $items[$item['id']] = $item['fields']['Внешний код'];
            }
        }

        return $items;
    }
}
