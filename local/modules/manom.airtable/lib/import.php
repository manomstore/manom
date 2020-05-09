<?php

namespace Manom\Airtable;

use Manom\Airtable\Bitrix\Element;
use Manom\Airtable\Bitrix\Section;
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

        $enumValues = $this->getEnumValues();

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
                    $enumValues
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
     * @return array
     */
    private function getEnumValues(): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->iblockId);
        $result = \CIBlockPropertyEnum::GetList(array(), $filter);
        while ($row = $result->Fetch()) {
            $items[$row['PROPERTY_CODE']][(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'xmlId' => $row['XML_ID'],
                'value' => $row['VALUE'],
            );
        }

        return $items;
    }

    /**
     * @param array $airtableItem
     * @param array $bitrixItem
     * @param array $map
     * @param array $sections
     * @param array $enumValues
     * @return array
     */
    private function prepareFields($airtableItem, $bitrixItem, $map, $sections, $enumValues): array
    {
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
            }

            if ($bitrix === 'CERTIFICATES') {
                if (empty($enumValues['CERTIFICATES'])) {
                    continue;
                }

                $values = array();
                foreach ($airtableItem['fields'][$airtable] as $value) {
                    foreach ($enumValues['CERTIFICATES'] as $enum) {
                        if ($enum['value'] === $value) {
                            $values[] = $enum['id'];
                            break;
                        }
                    }
                }

                $airtableItem['fields'][$airtable] = $values;
            }

            if ($bitrix === 'FEATURES2') {
                $values = explode('/', $airtableItem['fields'][$airtable]);
                $airtableItem['fields'][$airtable] = $values;
            }

            if ($bitrix === 'features' || $bitrix === 'contents_of_delivery') {
                $airtableItem['fields'][$airtable] = array(
                    'VALUE' => array(
                        'TYPE' => 'HTML',
                        'TEXT' => nl2br($airtableItem['fields'][$airtable]),
                    ),
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
}
