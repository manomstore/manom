<?php

namespace Manom\Content;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;
use Manom\Airtable\Parser\Review;

/**
 * Class Reviews
 * @package Manom\Content
 */
class Reviews
{
    /**
     * @var int
     */
    private $iBlockId = 11;
    /**
     * @var null
     */
    private $productData = null;
    /**
     * @var null|Review
     */
    private $parser = null;
    /**
     * @var array
     */
    private $reviews = [];


    /**
     * Reviews constructor.
     * @param $productId
     */
    public function __construct($productId)
    {
        $this->initProductData($productId);
        $this->initParser();
    }

    /**
     * @param $productId
     * @return bool
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function initProductData($productId)
    {
        if ((int)$productId <= 0) {
            return false;
        }

        $this->productData = ElementTable::getList(
            [
                "filter" => [
                    "ID" => $productId,
                    "IBLOCK_ID" => \Helper::CATALOG_IB_ID
                ],
                "select" => [
                    "ID",
                    "NAME"
                ]
            ])->fetch();
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    private function initParser()
    {
        if (Loader::includeModule("manom.airtable")) {
            $this->parser = new Review();
        }
    }

    /**
     * @param $reviews
     */
    public function updateFromAT($reviews)
    {
        if ($this->parser) {
            $this->reviews = $this->parser->parse($reviews);
        }

        $this->deleteAT();
        $this->createAT();
    }

    /**
     * @return bool
     */
    private function deleteAT()
    {
        if (!$this->productData["ID"]) {
            return false;
        }

        $reviews = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => $this->iBlockId,
                "PROPERTY_FROM_AT" => "Y",
                "PROPERTY_RV_PRODCTS" => $this->productData["ID"],
            ],
            false,
            false,
            [
                "ID"
            ]
        );

        while ($review = $reviews->GetNext()) {
            \CIBlockElement::Delete($review["ID"]);
        }
        return true;
    }

    /**
     * @return bool
     */
    private function createAT()
    {
        if (!$this->productData["ID"]) {
            return false;
        }

        $iBlockElement = new \CIBlockElement();

        foreach ($this->reviews as $review) {
            if (empty($review["merits"])
                && empty($review["disadvantages"])
                && empty($review["comment"])) {
                continue;
            }
            $reviewFields = [
                "IBLOCK_ID" => $this->iBlockId,
                "PROPERTY_VALUES" => [
                    "FROM_AT" => "Y",
                    "RV_PRODCTS" => $this->productData["ID"],
                    "AUTHOR" => $review["author"],
                    "SOURCE" => "Отзыв взят из Яндекс Маркет",
                    "RV_DISADVANTAGES" => $review["disadvantages"],
                    "RV_MERITS" => $review["merits"],
                ],
                "PREVIEW_TEXT" => $review["comment"],
                "NAME" => $this->productData["NAME"],
            ];

            if (array_key_exists("rating", $review)) {
                $reviewFields["PROPERTY_VALUES"]["RV_RATING"] = $review["rating"];
            }

            $iBlockElement->Add(
                $reviewFields
            );
        }

        return true;
    }
}