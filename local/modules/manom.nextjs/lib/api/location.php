<?php

namespace Manom\Nextjs\Api;

use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;
use \Bitrix\Sale\Location\LocationTable;

/**
 * Class Location
 * @package Manom\Nextjs\Api
 */
class Location
{
    /**
     * Location constructor.
     * @throws SystemException
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('sale')) {
            throw new SystemException('Не подключен модуль sale');
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getById($id)
    {
        $location = array();

        $result = LocationTable::getList(array(
            'order' => array('ID' => 'ASC'),
            'filter' => array('ID' => $id, '=NAME.LANGUAGE_ID' => LANGUAGE_ID),
            'select' => array('ID', 'COUNTRY_ID', 'REGION_ID', 'CITY_ID', 'NAME', 'PARENTS'),
        ));
        if ($row = $result->fetch()) {
            $location = array(
                'id' => (int)$row['ID'],
                'countryId' => (int)$row['SALE_LOCATION_LOCATION_PARENTS_COUNTRY_ID'],
            );
        }

        return $location;
    }

    /**
     * @param string $name
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function getByName($name)
    {
        $location = array();

        $result = LocationTable::getList(array(
            'order' => array('ID' => 'ASC'),
            'filter' => array('SALE_LOCATION_LOCATION_NAME_NAME' => $name, '=NAME.LANGUAGE_ID' => LANGUAGE_ID),
            'select' => array('ID', 'COUNTRY_ID', 'REGION_ID', 'CITY_ID', 'NAME', 'PARENTS'),
        ));
        if ($row = $result->fetch()) {
            $location = array(
                'id' => (int)$row['ID'],
                'countryId' => (int)$row['SALE_LOCATION_LOCATION_PARENTS_COUNTRY_ID'],
            );
        }

        return $location;
    }

    /**
     * @param array $locationData
     *
     * @return array $location
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Exception
     */
    public function findLocation($locationData)
    {
        $componentClass = \CBitrixComponent::includeComponentClass('bitrix:sale.location.selector.search');
        if (empty($componentClass) || !class_exists($componentClass)) {
            throw new \Exception();
        }

        $fieldNames = [
            'city',
            'settlement',
            'area',
            'region',
        ];

	    //Несоответствие названий регионов в битриксе и DaData
	    $locationData["region"] = $locationData["region"] === "Ханты-Мансийский Автономный округ - Югра" ?
		    "Ханты-Мансийский Автономный округ" : $locationData["region"];

	    $locationData["region"] = str_replace(" - ", "-", $locationData["region"]);

        $searchPhrase = '';

        foreach ($fieldNames as $field) {
	        if ($field === 'region' && in_array($locationData['capitalMarker'], [2, 3])) {
		        continue;
	        }

            $searchPhrase .= !empty($locationData[$field]) ? $locationData[$field].' ' : '';
        }

        $searchPhrase = trim($searchPhrase);
        if (empty($searchPhrase)) {
            throw new \Exception();
        }

        $params = [
            'select' => [
                'CODE',
                'TYPE_ID',
                'VALUE' => 'ID',
                'DISPLAY' => 'NAME.NAME',
            ],
            'additionals' => [
                'PATH',
            ],
            'filter' => [
                '=PHRASE' => $searchPhrase,
                '=NAME.LANGUAGE_ID' => LANGUAGE_ID,
                '=SITE_ID' => SITE_ID,
            ],
            'PAGE_SIZE' => 10,
            'PAGE' => 0,
        ];

	    $data = \CBitrixLocationSelectorSearchComponent::processSearchRequestV2($params);
	    if (empty($data['ITEMS'])) {
		    if (!empty($locationData["settlement"]) && count($locationData) > 1) {
			    unset($locationData["settlement"]);

			    return $this->findLocation($locationData);
		    }
		    throw new \Exception();
	    }

        $resultLocation = [];

        //Берём местоположение, которое находится на территории РФ
        foreach ($data['ITEMS'] as $item) {
            if (LocationTable::checkNodeIsParentOfNode(1, $item["VALUE"])) {
                $resultLocation = $item;
                break;
            }
        }

        if (empty($resultLocation)) {
            return [];
        }

        return [
            'id' => (int)$resultLocation['VALUE'],
            'name' => $resultLocation['DISPLAY'],
        ];
    }
}
