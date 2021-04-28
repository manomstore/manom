<?php

namespace Manom\Airtable;

use Bitrix\Main\Loader;
use Hozberg\Characteristics;
use Manom\Airtable\Bitrix\Element;
use Manom\Airtable\Bitrix\Section;
use Manom\Airtable\Bitrix\Property;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;
use \Bitrix\Main\ArgumentOutOfRangeException;
use \Bitrix\Main\ObjectPropertyException;
use Manom\Content\Questions;
use Manom\Content\Reviews;
use Manom\Exception;
use Manom\Product;
use Manom\References\Brand;

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
    private $errors = [];
    private $warnings = [];
    /**
     * @var Brand
     */
    public $brand;

    /**
     * Import constructor.
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws LoaderException
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws Exception
     */
    public function __construct()
    {
        $tools = new Tools;

        $this->changeStatus = $tools->getChangeStatus() === 'Y';

        $this->serviceFields = $tools->getServiceFields();
        $this->setPropertiesData();

        $element = new Element($this->iblockId);
        $this->bitrixElements = $element->getItems();

        $section = new Section($this->iblockId);
        $this->bitrixSections = $section->getItems();
        $this->brand = new Brand();
    }

    /**
     * @return void
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws LoaderException
     * @throws SystemException
     */
    public function setPropertiesData(): void
    {
        $fieldsMap = new FieldsMap();
        $this->map = $fieldsMap->getMap();

        $property = new Property($this->iblockId);
        $this->enumValues = $property->getEnumValues();

        $propertiesCode = array();
        foreach ($this->map['properties'] as $item) {
            $propertiesCode[] = $item['bitrix'];
        }

        $this->propertiesData = $property->getPropertiesData($propertiesCode);
    }

    /**
     * @param array $sections
     * @return bool
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws LoaderException
     * @throws SystemException
     * @throws Exception
     */
    public function process($sections = array()): bool
    {
        $element = new Element($this->iblockId);
        $element->propertiesData = $this->propertiesData;

        $api = new Api();

        $airtableData = array();
        if (empty($sections)) {
            $airtableData = $api->getAll();
        } else {
            foreach ($sections as $section) {
                $airtableData[$section] = $api->getFromSection($section);
            }
        }
        $this->trimFields($airtableData);


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

        $this->throwErrors();
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
     * @return array
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws LoaderException
     * @throws SystemException
     * @throws Exception
     */
    public function getMissingPropertiesForAllSections(): array
    {
        $api = new Api();
        $api->scanTables = true;
        $airtableData = $api->getAll();

        $missingProperties = [];
        foreach ($airtableData as $section => $sectionItems) {
            foreach ($sectionItems as $airtableItem) {
                $missingProperties = array_merge(
                    $missingProperties,
                    array_keys(
                        $this->getMissingProperties($airtableItem["fields"])
                    )
                );
                $missingProperties = array_unique($missingProperties);
            }
        }

        return $missingProperties;
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

            if ($bitrix === 'IBLOCK_SECTION') {
                $sectionsId = $this->getSectionsId($airtableItem['fields'][$airtable]);
                if (empty($sectionsId)) {
                    continue;
                }

                $airtableItem['fields'][$airtable] = $sectionsId;
            }

            $fields[$bitrix] = $airtableItem['fields'][$airtable];
        }

        $fields['PREVIEW_PICTURE'] = '';
        $fields['DETAIL_PICTURE'] = '';
        $fields['PROPERTIES'] = array();

        $fields = $this->prepareFiles($airtableItem, $fields);

        $properties = $this->prepareProperties($airtableItem, $airtableIdToXmlId);

        (new Reviews($fields["ID"]))->updateFromAT($airtableItem["fields"]["Отзывы"]);
        (new Questions($fields["ID"]))->updateFromAT($airtableItem["fields"]["Вопрос/Ответ"]);

        $fields['PROPERTIES'] = array_merge($fields['PROPERTIES'], $properties);
        $fields['PRODUCT'] = $this->prepareProductFields($airtableItem);

        return $fields;
    }

    /**
     * @param array $airtableItem
     * @param array $fields
     * @return array
     */
    private function prepareFiles($airtableItem, $fields): array
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

            if (in_array($item['bitrix'], ["CERTIFICATE", "INSTRUCTIONS"])) {
                $airtableItem['fields'][$item['airtable']] = $this->processFiles($airtableItem['fields'][$item['airtable']]);
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

        $this->createCharacteristics($airtableItem["fields"]);

        foreach ($this->map['properties'] as $item) {
            if (!isset($airtableItem['fields'][$item['airtable']])) {
                continue;
            }

            if ($this->brand->isBrandProperty($item["bitrix"])) {
                $this->brand->create($airtableItem['fields'][$item['airtable']]);
            }

            if ($item['bitrix'] === 'FEATURES2' || $item['bitrix'] === 'contents_of_delivery') {
                $values = explode("\n", $airtableItem['fields'][$item['airtable']]);
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
            } elseif (in_array($item['bitrix'], ["MORE_PHOTO", "CERTIFICATE", "INSTRUCTIONS"])) {
                continue;
            }

            $items[$item['bitrix']] = $airtableItem['fields'][$item['airtable']];
        }

        return $items;
    }

    /**
     * @param array $airtableItem
     * @return array
     * @throws LoaderException
     * @throws SystemException
     */
    private function prepareProductFields(array $airtableItem): array
    {
        $items = [];

        foreach ($this->map["product"] as $airtable => $bitrix) {
            if (empty($airtableItem['fields'][$airtable])) {
                continue;
            }

            $convertedVal = Product::convertDimensions($airtableItem["fields"][$airtable], $bitrix === "WEIGHT");

            if ($convertedVal === null) {
                $this->addWarning("Значение поля '{$airtable}' имеет некорректный формат " .
                    "веса или габарит [XML_ID: {$airtableItem["fields"]["Внешний код"]}, Раздел: {$airtableItem["section"]}]");
                continue;
            }

            if (empty($items[$bitrix])) {
                $items[$bitrix] = $convertedVal;
            }
        }

        return $items;
    }

    /**
     * @param array $properties
     * @return array
     */
    public function getMissingProperties($properties): array
    {
        $existProperties = array_merge(
            array_map(function ($field) {
                return $field["airtable"];
            }, $this->map['properties']),
            $this->map['fields'],
            $this->serviceFields
        );

        foreach ($existProperties as $existProperty) {
            unset($properties[$existProperty]);
        }

        return $properties;
    }

    /**
     * @param array $airtableFields
     * @return void
     * @throws ArgumentNullException
     * @throws ArgumentOutOfRangeException
     * @throws LoaderException
     * @throws SystemException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws \Exception
     */
    private function createCharacteristics($airtableFields): void
    {
        $missingProperties = $this->getMissingProperties($airtableFields);
        $property = new Property($this->iblockId);
        $fieldsMap = new FieldsMap();


        foreach ($missingProperties as $propertyName => $value) {
            $addedProperty = $property->createProperty($propertyName, $value, $this);
            if (empty($addedProperty["ID"])) {
                continue;
            }

            $fieldsMap->addLink(
                [
                    "airtable" => $propertyName,
                    "bitrix" => $addedProperty["CODE"],
                ]
            );

            if (Loader::includeModule("hozberg.characteristics")) {
                Characteristics::add($addedProperty["ID"]);
            }
        }
        $this->setPropertiesData();
    }

    /**
     * @param string $name
     * @return int
     */
    private function getSectionsId($sectionsList): array
    {
        $sectionsResult = [];

        if (!is_array($sectionsList)){
            $sectionsList = [$sectionsList];
        }

        foreach ($sectionsList as $name) {
            $sections = explode("/", $name);
            $sectionData = [
                "parentName" => array_shift($sections),
                "name"       => array_shift($sections),
            ];

            if (empty($sectionData["name"]) || empty($sectionData["parentName"])) {
                continue;
            }

            foreach ($this->bitrixSections as $section) {
                if ($section['name'] === $sectionData["name"]
                    && $section["parent"]['name'] === $sectionData["parentName"]) {
                    $sectionsResult[] = $section['id'];
                    break;
                }
            }
        }

        return $sectionsResult;
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
     * @param array $files
     * @return array
     */
    private function processFiles($files): array
    {
        $result = [];
        foreach ($files as $file) {
            if (empty($file['url'])) {
                continue;
            }

            $newFile = \CFile::MakeFileArray($file['url']);
            $newFile['name'] = $file['filename'];

            $result[] = array(
                'VALUE' => $newFile,
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
    ): array
    {
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

    /**
     * @throws Exception
     * @return void
     */
    private function throwErrors(): void
    {
        if (!empty($this->errors)) {
            $errorString = implode("<br>", $this->errors);
            throw new Exception($errorString);
        }
    }

    /**
     * @param string $error
     * @return void
     */
    public function addError($error): void
    {
        if (!empty($error) && is_string($error)) {
            $this->errors[] = $error;
        }
    }

    /**
     * @param string $error
     * @return void
     */
    public function addWarning($warning): void
    {
        if (!empty($warning) && is_string($warning)) {
            $this->warnings[] = $warning;
        }
    }

    /**
     * @return string
     */
    public function getWarnings(): string
    {
        $message = "";

        foreach ($this->warnings as $warning) {
            $message .= "<p>{$warning}</p>";
        }

        return $message;
    }

    /**
     * @param array $airtableData
     */
    private function trimFields(array &$airtableData): void
    {
        foreach ($airtableData as &$section) {
            foreach ($section as &$item) {
                $trimmedFields = [];
                foreach ($item["fields"] as $key => $field) {
                    if (is_string($field)) {
                        $field = trim($field);
                    }

                    $trimmedFields[trim($key)] = $field;
                }
                $item["fields"] = $trimmedFields;
            }
            unset($item);
        }
        unset($section);
    }
}
