<?php

namespace Manom\Content;

/**
 * Class SectionBindings
 * @package Manom\Content
 */
abstract class SectionBindings
{
    /**
     * @var int
     */
    protected static $iblockId;
    /**
     * @var string
     */
    protected static $sectionField;
    /**
     * @var array
     */
    protected $sectionsId = [];
    /**
     * @var array
     */
    protected $list = [];

    /**
     * SectionBindings constructor.
     * @param int $sectionId
     */
    public function __construct(int $sectionId)
    {
        if ($sectionId <= 0) {
            return;
        }

        $section = \CIBlockSection::GetList(
            [],
            [
                "ID"        => $sectionId,
                "IBLOCK_ID" => \Helper::CATALOG_IB_ID,
            ],
            false,
            [
                "ID",
                static::$sectionField,
            ]
        );

        $section = $section->GetNext();

        if ($section[static::$sectionField]) {
            $this->sectionsId = !is_array($section[static::$sectionField])
                ? [$section[static::$sectionField]] : $section[static::$sectionField];
        }
    }

    /**
     * @return void
     */
    protected function initList(): void
    {
        $this->list = [];

        foreach ($this->sectionsId as $sectionId) {
            $result = \CIBlockElement::GetList(
                [],
                [
                    "IBLOCK_ID"  => static::$iblockId,
                    "SECTION_ID" => $sectionId,
                ],
                false,
                false,
                [
                    "ID"
                ]
            );

            while ($row = $result->Fetch()) {
                $this->list[] = (int)$row["ID"];
            }
        }

        shuffle($this->list);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->list;
    }

    /**
     * @return bool
     */
    public function existItems(): bool
    {
        return count($this->list) > 0;
    }
}