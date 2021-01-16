<?php

namespace Manom;


/**
 * Class PreOrder
 * @package Manom
 */
class PreOrder
{
    /**
     *
     */
    const STATUS_ID = "PO";

    /**
     * @var array
     */
    private $productsId;

    /**
     * @var array
     */
    private $defaultData = [
        "active"       => false,
        "release_date" => false,
    ];

    /**
     * @var array
     */
    private $products = [];

    /**
     * PreOrder constructor.
     * @param $productsId
     */
    public function __construct($productsId)
    {
        if (!is_array($productsId)) {
            $productsId = [$productsId];
        }

        $this->productsId = $productsId;
        $this->setData();
        return;
    }

    /**
     *
     */
    private function setData(): void
    {
        if (empty($this->productsId)) {
            return;
        }

        $this->products = [];

        $result = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => \Helper::CATALOG_IB_ID,
                "=ID"       => $this->productsId,
            ],
            false,
            false,
            [
                "IBLOCK_ID",
                "ID",
                "PROPERTY_PREORDER",
                "PROPERTY_RELEASE_DATE",
            ]
        );

        while ($row = $result->Fetch()) {
            $row["ID"] = (int)$row["ID"];
            $this->products[$row["ID"]] = $this->defaultData;
            $preOrderData = [];

            $isPreOrder = $row['PROPERTY_PREORDER_VALUE'] === 'Да';
            $releaseDate = $row['PROPERTY_RELEASE_DATE_VALUE'];

            if (!$releaseDate) {
                continue;
            }

            $preOrderData["active"] = $isPreOrder && (int)MakeTimeStamp($releaseDate, \CSite::GetDateFormat()) >= time();
            if ($preOrderData["active"]) {
                $preOrderData["release_date"] = $releaseDate;
            }

            $this->products[$row["ID"]] = $preOrderData;
        }
    }

    /**
     * @param int $productId
     * @return array
     */
    public function getByProductId(int $productId): array
    {
        if (!array_key_exists($productId, $this->products)) {
            return $this->defaultData;
        }

        return $this->products[$productId];
    }
}