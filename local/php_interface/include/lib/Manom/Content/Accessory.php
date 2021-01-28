<?php

namespace Manom\Content;

use Bitrix\Main\Type\Collection;

/**
 * Class Accessories
 * @package Manom
 */
class Accessory extends SectionBindings
{
    /**
     * @var int
     */
    protected static $iblockId = \Helper::CATALOG_IB_ID;
    /**
     * @var string
     */
    protected static $sectionField = "UF_ACCESSORY_LINK";

    /**
     * Accessory constructor.
     * @param int $sectionId
     * @param array $accessoriesId
     */
    public function __construct(int $sectionId, array $accessoriesId)
    {
        parent::__construct($sectionId);
        $this->initList();
        $this->addAccessToProduct($accessoriesId);
    }

    /**
     * @param array $accessoriesId
     */
    private function addAccessToProduct(array $accessoriesId): void
    {
        $this->list = array_merge($accessoriesId, $this->list);
        Collection::normalizeArrayValuesByInt($this->list, false);
    }
}
