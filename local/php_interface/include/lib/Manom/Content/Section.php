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
            [],
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
                "IBLOCK_SECTION_ID",
            ]
        );

        while ($row = $sections->GetNext()) {
            $section = [
                "id"          => (int)$row["ID"],
                "code"        => $row["CODE"],
                "name"        => $row["NAME"],
                "leftMargin"  => (int)$row["LEFT_MARGIN"],
                "rightMargin" => (int)$row["RIGHT_MARGIN"],
                "depthLevel"  => (int)$row["DEPTH_LEVEL"],
                "sectionUrl"  => $row["SECTION_PAGE_URL"],
            ];

            if ($row["IBLOCK_SECTION_ID"]) {
                $section["parentId"] = (int)$row["IBLOCK_SECTION_ID"];
            }
            $this->sections[$section["id"]] = $section;
        }
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
     * @param int $depthLevel
     *
     * С целью оптимизации, проверяем пустоту разделов только на задонном уровне вложенности
     */
    public function checkEmptySectionsOnLevel(int $depthLevel): void
    {
        $depthSlice = array_filter($this->sections, function ($section) use ($depthLevel) {
            return $section["depthLevel"] === $depthLevel;
        });

        $this->setGroupProductBySection();
        foreach ($depthSlice as $section) {
            $this->checkEmptySection($section);
        }
        return;
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
}