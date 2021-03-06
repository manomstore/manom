<?php

namespace Manom\References;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Manom\Exception;

/**
 * Class Brand
 * @package Manom
 */
class Brand
{
    /**
     * @var string
     */
    private $iblockCode = 'brands';

    /**
     * @var int
     */
    public $iblockId;

    /**
     * @var array
     */
    private $list = null;

    /**
     * @var array
     */
    private $sectionsWithBrand = null;

    /**
     * @var string
     */
    private $brandPropertyCode = 'brand';

    /**
     * @var array
     */
    private $recentlyCreated = [];

    /**
     * @var string
     */
    public $iblockType;

    /**
     * Brand constructor.
     * @throws Exception
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception('Не подключен модуль iblock');
        }

        $iblockData = $this->getIblockData();

        $this->iblockId = $iblockData["id"];
        $this->iblockType = $iblockData["type"];

        if (empty($this->iblockId)) {
            throw new Exception('Не установлен ид инфоблока');
        }

        $this->setAll();

        if (!is_array($this->list)) {
            throw new Exception('Не удалось инициализировать бренды');
        }
    }

    /**
     * @return array
     */
    private function getIblockData(): array
    {
        $data = [];

        $result = \CIBlock::GetList([], ['CODE' => $this->iblockCode]);
        if ($row = $result->Fetch()) {
            $data = [
                "id"   => (int)$row['ID'],
                "type" => $row['IBLOCK_TYPE_ID'],
            ];
        }

        return $data;
    }

    /**
     * @return void
     */
    private function setAll(): void
    {
        $items = [];

        $order = ['SORT' => "ASC"];
        $filter = ['IBLOCK_ID' => $this->iblockId, 'ACTIVE' => 'Y'];
        $select = ['IBLOCK_ID', 'ID', 'CODE', 'NAME', "PROPERTY_LOGO", "SORT"];
        $result = \CIBlockElement::GetList($order, $filter, false, false, $select);
        while ($row = $result->GetNext()) {
            $items[$row['NAME']] = $this->formattedData($row);
        }

        $this->list = $items;
    }

    /**
     * @param $row
     * @return array
     */
    private function formattedData($row): array
    {
        $resultData = [];

        if (!empty($row["ID"])) {
            $resultData["id"] = (int)$row["ID"];
        }

        if (!empty($row["CODE"])) {
            $resultData["code"] = $row["CODE"];
        }

        if (!empty($row["NAME"])) {
            $resultData["name"] = $row["NAME"];
        }

        if (!empty($row["SORT"])) {
            $resultData["sort"] = $row["SORT"];
        }

        if (!empty($row["PROPERTY_LOGO_VALUE"])) {
            $logo = \CFile::GetFileArray($row['PROPERTY_LOGO_VALUE']);
            if ($logo) {
                $resultData["logo"] = $logo["SRC"];
            }
        }

        return $resultData;
    }

    /**
     * @return void
     */
    private function setSectionsWithBrand(): void
    {
        if (!is_array($this->getAll())) {
            $this->sectionsWithBrand = [];
        }

        $rsRootSections = \CIBlockSection::GetList(
            [],
            [
                "IBLOCK_ID"      => \Helper::CATALOG_IB_ID,
                "ACTIVE"         => "Y",
                "DEPTH_LEVEL"    => 1,
                "UF_SHOW_BRANDS" => "1"
            ],
            false,
            [
                "ID",
                "CODE",
                "NAME",
                "UF_SHOW_BRANDS"
            ]
        );

        $rootSections = [];

        while ($rootSection = $rsRootSections->GetNext()) {
            $groupBrands = \CIBlockElement::GetList(
                [],
                [
                    "IBLOCK_ID"             => \Helper::CATALOG_IB_ID,
                    "SECTION_ID"            => $rootSection,
                    "INCLUDE_SUBSECTIONS"   => "Y",
                    "ACTIVE"                => "Y",
                    "SECTION_GLOBAL_ACTIVE" => "Y",
                    "!PROPERTY_BRAND"       => false,
                    "CATALOG_AVAILABLE"     => "Y",
                    ">CATALOG_PRICE_1"      => "0",
                ],
                [
                    "PROPERTY_BRAND"
                ]
            );

            $brands = [];
            while ($groupBrand = $groupBrands->GetNext()) {
                $brands[] = $groupBrand;
            }

            uasort($brands, function ($a, $b) {
                if ((int)$a["CNT"] === (int)$b["CNT"]) {
                    return 0;
                }
                return ((int)$a["CNT"] < (int)$b["CNT"]) ? 1 : -1;
            });

            foreach ($brands as $brand) {
                $brandName = $brand["PROPERTY_BRAND_VALUE"];

                if ($this->exist($brandName) && $this->hasLogo($brandName)) {
                    $rootSections[$rootSection["CODE"]][] = $this->getByName($brandName);
                }
            }

            if (!is_array($rootSections[$rootSection["CODE"]])) {
                $rootSections[$rootSection["CODE"]] = [];
            }
        }
        $this->sectionsWithBrand = $rootSections;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->list;
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        return array_map(function ($item) {
            return $item["name"];
        }, $this->getAll());
    }

    /**
     * @param $name
     * @return array
     */
    public function getByName($name): array
    {
        $result = $this->list[trim($name)];

        if (empty($result)) {
            $result = [];
        }

        return $result;
    }

    /**
     * @param $code
     * @return array
     */
    public function getByCode($code): array
    {
        $result = current(array_filter($this->list, function ($item) use ($code) {
            return $item["code"] === $code;
        }));

        if (empty($result)) {
            $result = [];
        }

        return $result;
    }

    /**
     * @param $name
     * @return bool
     */
    public function exist($name): bool
    {
        return array_key_exists(trim($name), $this->list);
    }

    /**
     * @param $name
     * @return bool
     */
    private function inRecentlyCreated($name): bool
    {
        return array_key_exists(trim($name), $this->recentlyCreated);
    }

    /**
     * @param $name
     * @return bool
     */
    private function hasLogo($name): bool
    {
        return (bool)$this->list[$name]["logo"];
    }

    /**
     * @param $sectionCode
     * @return array
     */
    public function getForSection($sectionCode): array
    {
        $sections = $this->getSectionsWithBrand();

        return $sections[$sectionCode] ?? [];
    }

    /**
     * @return array
     */
    public function getSectionsWithBrand(): array
    {
        if (!is_array($this->sectionsWithBrand)) {
            $this->setSectionsWithBrand();
        }

        return $this->sectionsWithBrand;
    }

    /**
     * @param $filterName
     * @param $brandCode
     */
    public function setBrandFilter($filterName, $brandCode): void
    {
        if (!$filterName) {
            return;
        }

        $brand = $this->getByCode($brandCode);
        $filterVal = !empty($brand) ? $brand["name"] : $brandCode;

        if (is_array($GLOBALS[$filterName])) {
            $GLOBALS[$filterName] = array_merge(
                $GLOBALS[$filterName],
                [
                    'PROPERTY_BRAND' => $filterVal
                ]
            );
        } else {
            $GLOBALS[$filterName] = ['PROPERTY_BRAND' => $filterVal];
        };
    }

    /**
     * @param $filterName
     * @param $brandCode
     */
    public function setBrandSectionFilter($filterName, $brandCode): void
    {
        if (!$filterName) {
            return;
        }

        $brand = $this->getByCode($brandCode);
        $filterVal = !empty($brand) ? $brand["name"] : $brandCode;

        if (is_array($GLOBALS[$filterName])) {
            $GLOBALS[$filterName] = array_merge(
                $GLOBALS[$filterName],
                [
                    'PROPERTY' => [
                        "BRAND" => $filterVal,
                    ]
                ]
            );
        } else {
            $GLOBALS[$filterName] = [
                'PROPERTY' => [
                    "BRAND" => $filterVal,
                ]
            ];
        };
    }

    /**
     * @param $property
     * @return bool
     */
    public function isBrandProperty($property): bool
    {
        return $property === $this->brandPropertyCode;
    }

    /**
     * @param $id
     */
    private function addRecentlyCreated($id): void
    {
        $data = \CIBlockElement::GetByID($id)->GetNext();
        if (!empty($data)) {
            $this->recentlyCreated[$data["NAME"]] = $this->formattedData($data);
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function create($name): bool
    {
        $name = trim($name);
        if ($this->exist($name) || $this->inRecentlyCreated($name)) {
            return true;
        }

        $element = new \CIBlockElement();
        $fields = [
            "NAME"      => $name,
            "CODE"      => \CUtil::translit($name, "en", ["max_len" => 10]),
            "IBLOCK_ID" => $this->iblockId,
            "ACTIVE"    => "Y",
        ];

        $brandId = $element->Add($fields);

        $success = (int)$brandId > 0;

        if ($success) {
            $this->addRecentlyCreated($brandId);
        }

        return $success;
    }

    /**
     * @return array
     */
    public function getRecentlyCreated()
    {
        return $this->recentlyCreated;
    }

    /**
     * @param mixed $type
     * @return Brand
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getImportCreatedInfo(): string
    {
        $message = "";
        $createdBrands = $this->getRecentlyCreated();
        if (!empty($createdBrands) && is_array($createdBrands)) {
            $message = "<p>В процессе импорта были созданы новые бренды. Нужно добавить им логотипы по ссылкам ниже:</p>";
            $linkTemplate = "/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=#IBLOCK_TYPE#&lang=ru&ID=#BRAND_ID#";
            foreach ($createdBrands as $brand) {
                $link = str_replace(
                    [
                        "#IBLOCK_ID#",
                        "#IBLOCK_TYPE#",
                        "#BRAND_ID#"
                    ],
                    [
                        $this->iblockId,
                        $this->iblockType,
                        $brand["id"]
                    ],
                    $linkTemplate);
                $message .= "<a href='{$link}' target='_blank'>Добавить логотип для {$brand["name"]}</a><br>";
            }
            $message .= "<p>(До тех пор, пока не будут добавлены логотипы, бренд не будет отображаться на сайте)</p>";
        }

        return $message;
    }

    /**
     * @return array
     */
    public function getForMenu(): array
    {
        $groupBrands = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID"             => \Helper::CATALOG_IB_ID,
                "INCLUDE_SUBSECTIONS"   => "Y",
                "ACTIVE"                => "Y",
                "SECTION_GLOBAL_ACTIVE" => "Y",
                "!PROPERTY_BRAND"       => false,
                "CATALOG_AVAILABLE"     => "Y",
                ">CATALOG_PRICE_1"      => "0",
            ],
            [
                "PROPERTY_BRAND"
            ]
        );

        $brandGroups = [];
        $brands = [];
        while ($groupBrand = $groupBrands->GetNext()) {
            $brandGroups[] = $groupBrand;
        }

        foreach ($brandGroups as $brandGroup) {
            $brandName = $brandGroup["PROPERTY_BRAND_VALUE"];

            if ($this->exist($brandName) && $this->hasLogo($brandName)) {
                $brands[$brandName] = $this->getByName($brandName);
                $brands[$brandName]["url"] = "/catalog/brand/" . $brands[$brandName]["code"] . "/";
            }
        }

        uasort($brands, function ($a, $b) {
            if ((int)$a["sort"] === (int)$b["sort"]) {
                return 0;
            }
            return ((int)$a["sort"] < (int)$b["sort"]) ? -1 : 1;
        });

        return $brands;
    }
}
