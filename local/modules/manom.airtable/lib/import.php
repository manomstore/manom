<?php

namespace Manom\Airtable;

use Manom\Airtable\Bitrix\Element;
use Manom\Airtable\Bitrix\Section;
use Manom\Airtable\Bitrix\Property;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentOutOfRangeException;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;

/**
 * Class Import
 * @package Manom\Airtable
 */
class Import
{
    private $iblockId = 6;
    private $changeStatus;

    /**
     * Import constructor.
     */
    public function __construct()
    {
        $tools = new Tools;

        $this->changeStatus = $tools->getChangeStatus() === 'Y';
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
        $fieldsMap = new FieldsMap();
        $map = $fieldsMap->getMap();

        $property = new Property($this->iblockId);
        $enumValues = $property->getEnumValues();

        $propertiesData = $property->getPropertiesData(array_keys($map['properties']));

        $element = new Element($this->iblockId);
        $bitrixElements = $element->getItems();

        $section = new Section($this->iblockId);
        $bitrixSections = $section->getItems();

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
                if (empty($bitrixElements[$airtableItem['fields']['Внешний код']])) {
                    continue;
                }

                $airtableItem['section'] = $section;

                $itemsToUpdate[] = $this->prepareFields(
                    $airtableItem,
                    $bitrixElements[$airtableItem['fields']['Внешний код']],
                    $map,
                    $bitrixSections,
                    $enumValues,
                    $propertiesData,
                    $airtableIdToXmlId,
                    $bitrixElements
                );
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
     * @param array $bitrixItem
     * @param array $map
     * @param array $sections
     * @param array $enumValues
     * @param array $propertiesData
     * @param array $airtableIdToXmlId
     * @param array $bitrixElements
     * @return array
     * @throws LoaderException
     * @throws SystemException
     */
    private function prepareFields(
        $airtableItem,
        $bitrixItem,
        $map,
        $sections,
        $enumValues,
        $propertiesData,
        $airtableIdToXmlId,
        $bitrixElements
    ): array {
        $fields = array(
            'ID' => $bitrixItem['id'],
            'AIRTABLE_ID' => $airtableItem['id'],
            'AIRTABLE_SECTION' => $airtableItem['section'],
        );

        foreach ($map['fields'] as $bitrix => $airtable) {
            if (!isset($airtableItem['fields'][$airtable])) {
                continue;
            }

            if ($bitrix === 'NAME' && empty($airtableItem['fields'][$airtable])) {
                continue;
            }

            if ($bitrix === 'IBLOCK_SECTION_ID') {
                $sectionId = $this->getSectionId($airtableItem['fields'][$airtable], $sections);
                if (empty($sectionId)) {
                    continue;
                }

                $airtableItem['fields'][$airtable] = $sectionId;
            }

            $fields[$bitrix] = $airtableItem['fields'][$airtable];
        }

        $fields['PROPERTIES'] = array();

        foreach ($map['properties'] as $bitrix => $airtable) {
            if (!isset($airtableItem['fields'][$airtable])) {
                continue;
            }

            if ($bitrix === 'MORE_PHOTO') {
                $result = $this->prepareImages($airtableItem['fields'][$airtable]);

                if (!empty($result['preview'])) {
                    $fields['PREVIEW_PICTURE'] = $result['preview'];
                }

                if (!empty($result['detail'])) {
                    $fields['DETAIL_PICTURE'] = $result['detail'];
                }

                $airtableItem['fields'][$airtable] = $result['images'];
            } elseif ($bitrix === 'FEATURES2') {
                $values = explode('/', $airtableItem['fields'][$airtable]);
                $airtableItem['fields'][$airtable] = $values;
            } elseif ($bitrix === 'features' || $bitrix === 'contents_of_delivery') {
                $airtableItem['fields'][$airtable] = array(
                    'VALUE' => array(
                        'TYPE' => 'HTML',
                        'TEXT' => nl2br($airtableItem['fields'][$airtable]),
                    ),
                );
            } elseif ($propertiesData[$bitrix]['type'] === 'L') {
                $result = $this->processListProperty(
                    $enumValues[$bitrix],
                    $airtableItem['fields'][$airtable],
                    $propertiesData[$bitrix]['id'],
                    $propertiesData[$bitrix]['multiple']
                );

                if (empty($result['result'])) {
                    continue;
                }

                $enumValues[$bitrix] = $result['enumValues'];

                $airtableItem['fields'][$airtable] = $result;
            } elseif ($propertiesData[$bitrix]['type'] === 'E') {
                $airtableItem['fields'][$airtable] = $this->processElementLinkProperty(
                    $airtableItem['fields'][$airtable],
                    $airtableIdToXmlId,
                    $bitrixElements,
                    $propertiesData[$bitrix]['multiple']
                );
            }

            $fields['PROPERTIES'][$bitrix] = $airtableItem['fields'][$airtable];
        }

        return $fields;
    }

    /**
     * @param array $images
     * @return array
     */
    private function prepareImages($images): array
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
     * @param string $name
     * @param array $sections
     * @return int
     */
    private function getSectionId($name, $sections): int
    {
        $sectionId = 0;

        foreach ($sections as $section) {
            if ($section['name'] === $name) {
                $sectionId = $section['id'];
                break;
            }
        }

        return $sectionId;
    }

    /**
     * @param array $enumValues
     * @param string|array $airtableValue
     * @param int $propertyId
     * @param bool $multiple
     * @return array
     * @throws LoaderException
     * @throws SystemException
     */
    private function processListProperty($enumValues, $airtableValue, $propertyId, $multiple = false): array
    {
        $property = new Property($this->iblockId);

        if ($multiple) {
            $values = array();

            foreach ($airtableValue as $value) {
                $values[$value] = 0;

                foreach ($enumValues as $enumValue) {
                    if (trim($enumValue['value']) === trim($value)) {
                        $values[$value] = $enumValue['id'];
                    }
                }
            }

            foreach ($values as $value => $enumId) {
                if (empty($enumId)) {
                    $enumId = $property->addEnumValue($propertyId, trim($value));
                }

                if (empty($enumId)) {
                    unset($values[$value]);
                } else {
                    $values[$value] = $enumId;

                    $enumValues[$enumId] = array(
                        'id' => $enumId,
                        'xmlId' => trim($value),
                        'value' => trim($value),
                    );
                }
            }

            $result = array_values($values);
        } else {
            $enumId = 0;

            foreach ($enumValues as $enumValue) {
                if (trim($enumValue['value']) === trim($airtableValue)) {
                    $enumId = $enumValue['id'];
                }
            }

            if (empty($enumId)) {
                $enumId = $property->addEnumValue($propertyId, trim($airtableValue));
            }

            if (!empty($enumId)) {
                $enumValues[$enumId] = array(
                    'id' => $enumId,
                    'xmlId' => trim($airtableValue),
                    'value' => trim($airtableValue),
                );
            }

            $result = $enumId;
        }

        return array('result' => $result, 'enumValues' => $enumValues);
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
        $bitrixElements,
        $multiple = false
    ): array {
        $result = '';

        if ($multiple) {
            $values = array();
            foreach ($airtableValue as $airtableId) {
                if (empty($airtableIdToXmlId[$airtableId])) {
                    continue;
                }

                if (empty($bitrixElements[$airtableIdToXmlId[$airtableId]]['id'])) {
                    continue;
                }

                $values[] = $bitrixElements[$airtableIdToXmlId[$airtableId]]['id'];
            }

            $result = $values;
        } elseif (!empty($airtableIdToXmlId[$airtableValue]) && !empty($bitrixElements[$airtableIdToXmlId[$airtableValue]]['id'])) {
            $result = $bitrixElements[$airtableIdToXmlId[$airtableValue]]['id'];
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
