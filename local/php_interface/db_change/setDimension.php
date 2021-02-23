<?php

use Bitrix\Catalog\Model\Product;
use Manom\Product as mProduct;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

global $USER;
if (!$USER->IsAdmin()) {
    die();
}

$listCatalog = [];
$result = Product::getList([
    "select" => [
        "WEIGHT",
        "LENGTH",
        "WIDTH",
        "HEIGHT",
        "ID",
    ]
]);

while ($row = $result->fetch()) {
    $listCatalog[$row["ID"]] = $row;
}

$result = \CIBlockElement::GetList(
    [],
    [
        "IBLOCK_ID" => \Helper::CATALOG_IB_ID,
    ],
    false,
    false,
    [
        'ID',
        'IBLOCK_ID',
        'PROPERTY_thickness',
        'PROPERTY_shipping_weight',
        'PROPERTY_width',
        'PROPERTY_length',
        'PROPERTY_Weight',
    ]
);

$successCnt = 0;

while ($row = $result->GetNext()) {
    $data = [];
    $catalogData = $listCatalog[$row["ID"]];

    $dimensions = [
        "WEIGHT" => $row["PROPERTY_SHIPPING_WEIGHT_VALUE"],
        "LENGTH" => $row["PROPERTY_LENGTH_VALUE"],
        "WIDTH"  => $row["PROPERTY_WIDTH_VALUE"],
        "HEIGHT" => $row["PROPERTY_THICKNESS_VALUE"],
    ];

    if (empty($dimensions["WEIGHT"])) {
        $dimensions["WEIGHT"] = $row["PROPERTY_WEIGHT_VALUE"];
    } else if (mProduct::convertDimensions($dimensions["WEIGHT"], true) === null) {
        $dimensions["WEIGHT"] = $row["PROPERTY_WEIGHT_VALUE"];
    }

    foreach ($dimensions as $catalogKey => $property) {
        if (empty($catalogData[$catalogKey]) && !empty($property)) {
            $convertedVal = mProduct::convertDimensions($property, $catalogKey === "WEIGHT");
            if ($convertedVal) {
                $data[$catalogKey] = $convertedVal;
            }
        }
    }

    if (!empty($data)) {
        $status = Product::update($row["ID"], $data);
        if ($status->isSuccess()) {
            $successCnt++;
        }
    }
}

echo "У {$successCnt} товаров были обновлены вес и габариты<br>";
