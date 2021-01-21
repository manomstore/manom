<?php

namespace Manom\Content;

use Bitrix\Main\Type\Collection;

/**
 * Class Accessories
 * @package Manom
 */
class Accessory
{
    /**
     * @var int
     */
    private $iblockId = \Helper::CATALOG_IB_ID;
    /**
     * @var array
     */
    private $accessToProduct = [];
    /**
     * @var array
     */
    private $sectionsId = [];
    /**
     * @var array
     */
    private $productsId = [];

    /**
     * Accessory constructor.
     * @param int $sectionId
     * @param array $accessoriesId
     */
    public function __construct(int $sectionId, array $accessoriesId)
    {
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
     */
    private function initProducts(): void
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
     */
    public function getProductsId(): array
    {
        return $this->productsId;
    }

    /**
     * @return bool
     */
    public function existProducts(): bool
    {
        return count($this->productsId) > 0;
    }
}
