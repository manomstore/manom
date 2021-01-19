<?php

namespace Manom;

use Bitrix\Iblock\ElementTable;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\Type\Collection;

/**
 * Class Accessories
 * @package Manom
 */
class Accessory {
	private $iblockId = 6;
	private $accessToProduct = [];
	private $sectionsId = [];
	private $productsId = [];

	/**
	 * @param int $sectionId
	 * @param array $accessoriesId
	 *
	 * @return void
	 * @throws ArgumentException
	 * @throws Exception
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
	public function __construct($sectionId, array $accessoriesId) {
		$sectionId = (int) $sectionId;
        $this->accessToProduct = $accessoriesId;
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
        $this->sectionsId = !is_array($section["UF_ACCESSORY_LINK"])
            ? [$section["UF_ACCESSORY_LINK"]] : $section["UF_ACCESSORY_LINK"];

        $this->initProducts();
	}

	/**
	 *
	 * @return void
	 * @throws ArgumentException
	 * @throws Exception
	 * @throws LoaderException
	 * @throws ObjectPropertyException
	 * @throws SystemException
	 */
    private function initProducts()
    {
        $products = [];

        foreach ($this->sectionsId as $sectionId) {
            $result = \CIBlockElement::GetList(
                [],
                [
                    "IBLOCK_ID"  => $this->iblockId,
                    "SECTION_ID" => $sectionId,
                ],
                false,
                false,
                [
                    "ID"
                ]
            );

            while ($row = $result->Fetch()) {
                $products[] = (int)$row["ID"];
            }
        }

        shuffle($products);

        $this->productsId = array_merge($this->accessToProduct, $products);
        Collection::normalizeArrayValuesByInt($this->productsId, false);
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
