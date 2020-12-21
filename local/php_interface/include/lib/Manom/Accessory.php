<?php

namespace Manom;

use Bitrix\Iblock\ElementTable;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;

/**
 * Class Accessories
 * @package Manom
 */
class Accessory {
	private $iblockId = 6;
	private $productsId = [];

	/**
	 * @param int $sectionId
	 *
	 * @return void
	 * @throws ArgumentException
	 * @throws Exception
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function __construct($sectionId) {
		$sectionId = (int) $sectionId;

		if ($sectionId <= 0) {
			return;
		}

		$section = \CIBlockSection::GetList(
			[],
			[
				"ID"        => $sectionId,
				"IBLOCK_ID" => $this->iblockId,
			],
			false,
			[
				"ID",
				"UF_ACCESSORY_LINK",
			]
		);

		$section = $section->GetNext();

		if (!empty($section["UF_ACCESSORY_LINK"])) {
			$this->initProducts($section["UF_ACCESSORY_LINK"]);
		}
	}

	/**
	 * @param array $sectionsId
	 *
	 * @return void
	 * @throws ArgumentException
	 * @throws Exception
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	private function initProducts($sectionsId) {

        $result = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID"  => $this->iblockId,
                "SECTION_ID" => $sectionsId,
            ],
            false,
            false,
            [
                "ID"
            ]
        );

        $products = [];

        while ($row = $result->Fetch()){
            $products[] = (int)$row["ID"];
        }

        $this->productsId = $products && !is_array($products)
            ? (array)$products : $products;
        shuffle($this->productsId);
	}

	/**
	 * @return array
	 * @throws ArgumentException
	 * @throws Exception
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function getProductsId() {
		return $this->productsId;
	}

	/**
	 * @param array $sectionsId
	 *
	 * @return bool
	 * @throws ArgumentException
	 * @throws Exception
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function existProducts() {
		return count($this->productsId) > 0;
	}
}
