<?php

namespace Manom\Content;


use Manom\Exception;

/**
 * Class Section
 * @package Manom\Content
 */
class Section
{
    /**
     * @var int
     */
    private $iblockId = \Helper::CATALOG_IB_ID;
    /**
     * @var array
     */
    private $sections = [];
    /**
     * @var array
     */
    private $sectionsWithQuantityItems = [];
    /**
     * @var int
     */
    private $showDepth = 0;

    /**
     * Section constructor.
     * @throws Exception
     */
    public function __construct()
    {
        if (!$this->iblockId) {
            throw new Exception("Id Инфоблока не определён");
        }

        $this->initSections();
    }

    /**
     *
     */
    private function initSections(): void
    {

        $sections = \CIBlockSection::GetList(
            [
                "LEFT_MARGIN" => "ASC",
            ],
            [
                "IBLOCK_ID"     => \Helper::CATALOG_IB_ID,
                "ACTIVE"        => "Y",
                "GLOBAL_ACTIVE" => "Y",
            ],
            false,
            [
                "ID",
                "CODE",
                "NAME",
                "LEFT_MARGIN",
                "RIGHT_MARGIN",
                "DEPTH_LEVEL",
                "SECTION_PAGE_URL",
                "LIST_PAGE_URL",
            ]
        );

        $maxDepthLevel = 0;

        while ($row = $sections->GetNext()) {
            $section = [
                "id"          => (int)$row["ID"],
                "code"        => $row["CODE"],
                "name"        => $row["NAME"],
                "leftMargin"  => (int)$row["LEFT_MARGIN"],
                "rightMargin" => (int)$row["RIGHT_MARGIN"],
                "depthLevel"  => (int)$row["DEPTH_LEVEL"],
                "sectionUrl"  => $row["SECTION_PAGE_URL"],
                "baseUrl"     => $row["LIST_PAGE_URL"],
            ];

            $this->sections[$section["id"]] = $section;

            $maxDepthLevel = $section["depthLevel"] > $maxDepthLevel ?
                $section["depthLevel"] : $maxDepthLevel;
        }

        $this->setShowDepth($maxDepthLevel);
    }

    /**
     *
     */
    private function setGroupProductBySection(): void
    {
        $products = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID"             => \Helper::CATALOG_IB_ID,
                "ACTIVE"                => "Y",
                "SECTION_GLOBAL_ACTIVE" => "Y",
                "CATALOG_AVAILABLE"     => "Y",
                ">CATALOG_PRICE_1"      => "0",
            ],
            [
                "IBLOCK_SECTION_ID"
            ]
        );

        $groupProducts = [];

        while ($groupProduct = $products->GetNext()) {
            $groupProducts[] = $groupProduct;
        }

        $this->sectionsWithQuantityItems = $groupProducts;
    }

    /**
     * С целью оптимизации, проверяем пустоту разделов не глубже заданного уровня вложенности
     */
    public function checkEmptySectionsMaxLevel(): void
    {
        $depthSlice = array_filter($this->sections, function ($section) {
            return $section["depthLevel"] <= $this->showDepth;
        });

        $this->setGroupProductBySection();
        foreach ($depthSlice as $section) {
            $this->checkEmptySection($section);
        }
    }

    /**
     * @param array $currentSection
     * @return array
     */
    private function filterChildrenForCurrent(array $currentSection): array
    {
        $sections = $this->sections;

        return array_filter(
            $this->sectionsWithQuantityItems,
            function ($item) use ($currentSection, $sections) {
                $sectionData = $sections[$item["IBLOCK_SECTION_ID"]];
                if (empty($sectionData)) {
                    return false;
                }

                return $sectionData["leftMargin"] >= $currentSection["leftMargin"]
                    && $sectionData["rightMargin"] <= $currentSection["rightMargin"];
            });
    }

    /**
     * @param $section
     * @return void
     */
    private function checkEmptySection($section): void
    {
        $children = $this->filterChildrenForCurrent($section);
        $counterProduct = 0;

        array_walk($children, function ($section) use (&$counterProduct) {
            $counterProduct += (int)$section["CNT"];
        });

        if (!empty($this->sections[$section["id"]])) {
            $this->sections[$section["id"]]["disabled"] = $counterProduct <= 0;
        }

        unset($counterProduct);
    }

    /**
     * @param int $sectionId
     * @return bool
     */
    public function isDisabled(int $sectionId): bool
    {
        if (empty($this->sections[$sectionId])) {
            return false;
        }

        return (bool)$this->sections[$sectionId]["disabled"];
    }

    /**
     * @param int $sectionId
     * @return string
     */
    public function getCode(int $sectionId): string
    {
        if (empty($this->sections[$sectionId])) {
            return "";
        }

        return (string)$this->sections[$sectionId]["code"];
    }

    /**
     * @param array $param
     * @return array
     */
    public function get(array $param = []): array
    {
        $sections = $this->sections;

        if (isset($param["id"])) {
            $sections = array_filter($sections, function ($section) use ($param) {
                return $section["id"] === (int)$param["id"];
            });
        }

        if (isset($param["maxDepth"])) {
            $sections = array_filter($sections, function ($section) use ($param) {
                return $section["depthLevel"] <= $param["maxDepth"];
            });
        }

        return $sections;
    }

    /**
     * @param array $param
     * @return array
     */
    public function getFirst(array $param = []): array
    {
        $sections = $this->get($param);

        $section = current($sections);
        if (!is_array($section)) {
            $section = [];
        }

        return $section;
    }

    /**
     * @return array
     */
    public function makeTree(): array
    {
        $tree = $parents = [];
        $sections = $this->get(["maxDepth" => $this->showDepth]);

        foreach ($sections as &$section) {
            $section['children'] = [];
            if (isset($parents[$section['depthLevel']])) {
                unset($parents[$section['depthLevel']]);
            }
            $parents[$section['depthLevel']] = &$section;
            if ($section['depthLevel'] > 1) {
                $parents[$section['depthLevel'] - 1]['children'][] = &$section;
            } else {
                $tree[] = &$section;
            }
        }
        unset($section);
        return $tree;
    }

    /**
     *
     */
    public function setHaveSale(): void
    {
        $result = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID"                => \Helper::CATALOG_IB_ID,
                "PROPERTY_SELL_PROD_VALUE" => "Да",
                "ACTIVE"                   => "Y",
                "SECTION_GLOBAL_ACTIVE"    => "Y",
                "CATALOG_AVAILABLE"        => "Y",
                ">CATALOG_PRICE_1"         => "0",
            ],
            false,
            false,
            [
                "ID",
                "IBLOCK_ID",
            ]
        );

        $saleProductIds = [];
        $saleSections = [];

        while ($row = $result->GetNext()) {
            $saleProductIds[] = $row["ID"];
        }

        $result = \CIBlockElement::GetElementGroups($saleProductIds, ["ID"]);

        while ($row = $result->GetNext()) {
            $saleSections[(int)$row["ID"]] = $this->getFirst(["id" => $row["ID"]]);
        }

        foreach ($this->sections as &$section) {
            $section["isHaveSale"] = !empty(array_filter($saleSections, function ($saleSection) use ($section) {
                return $saleSection["leftMargin"] >= $section["leftMargin"] && $saleSection["rightMargin"] <= $section["rightMargin"];
            }));
        }
        unset($section);
    }

    /**
     * @return Section
     */
    public function onlySale(): Section
    {
        $cloneThis = clone($this);
        $cloneThis->setHaveSale();
        $cloneThis->sections = array_filter($cloneThis->sections, function ($section) {
            return $section["isHaveSale"];
        });

        array_walk($cloneThis->sections, function (&$section) {
            $section["sectionUrl"] = str_replace($section["baseUrl"], "", $section["sectionUrl"]);
            $section["sectionUrl"] = $section["baseUrl"] . "sale/" . $section["sectionUrl"];
        });

        return $cloneThis;
    }

    /**
     * @return Section
     */
    public function onlyWithProducts(): Section
    {
        $cloneThis = clone($this);
        $cloneThis->checkEmptySectionsMaxLevel();
        $cloneThis->sections = array_filter($cloneThis->sections, function ($section) {
            return !$section["disabled"];
        });

        return $cloneThis;
    }

    /**
     * @param int $depth
     */
    public function setShowDepth(int $depth): void
    {
        $this->showDepth = (int)$depth;
    }
}