<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Manom\Content;
use Manom\Content\Service;
use Manom\Nextjs\Api\Delivery;
use Manom\Nextjs\Api\PaySystem;
use Hozberg\Characteristics;
use Manom\Related;
use Manom\Service\Delivery as ServiceDelivery;
use Manom\Service\TimeDelivery;
use \Manom\WeekTools;

$isMoscow = (int)$arParams['LOCATION']['ID'] === 84;

$arResult['inFavoriteAndCompare'] = checkProdInFavoriteAndCompareList((int)$arResult['ID'], 'UF_FAVORITE_ID');
$rating = getRatingAndCountReviewForList(array((int)$arResult['ID']));
$arResult['rating'] = $rating[$arResult['ID']];
$images = Content::getCatalogItemImages($arResult, false);
foreach ($images as $imageId) {
    $arResult['smallImages'][] = CFile::ResizeImageGet(
        $imageId,
        array('width' => 60, 'height' => 50),
        BX_RESIZE_IMAGE_PROPORTIONAL
    );
    $arResult['images'][] = CFile::ResizeImageGet(
        $imageId,
        array('width' => 1920, 'height' => 1080),
        BX_RESIZE_IMAGE_PROPORTIONAL
    );
}

$prices = Content::getPricesFromStoreData($arParams['ECOMMERCE_DATA']['storeData']);

$arResult["preOrder"] = $arParams['ECOMMERCE_DATA']["preOrder"];
$arResult['price'] = $prices['price'];
$arResult['oldPrice'] = $prices['oldPrice'];
$arResult['showOldPrice'] = !empty((int)$arResult['oldPrice'])
    && (int)$arResult['price'] !== (int)$arResult['oldPrice'];

if (
    empty($arParams['ECOMMERCE_DATA']['amounts']['main']) &&
    empty($arParams['ECOMMERCE_DATA']['amounts']['second']) &&
    !$arResult["preOrder"]["active"]
) {
    $arResult['CATALOG_AVAILABLE'] = 'N';
}

$arResult['onlyCash'] = $arResult['PROPERTIES']['ONLY_CASH']['VALUE'] === 'Y';
$arResult['locationDisallowBuy'] = $arResult['onlyCash'] && !$isMoscow;
$arResult['CHEAPER'] = getCheaper($arResult['ID'], $arParams['IBLOCK_ID']);
$arResult['CURRENT_USER'] = getUser();
$arResult['REVIEWS'] = getReviews($arResult['ID']);
$arResult['QNA_VALUES'] = getQna($arResult['PROPERTIES']['A_N_Q']['VALUE']);
$arResult['DELIV'] = getDelivery();

$related = new Related();
$arResult['RELATED'] = $related->getRelated($arResult['ID']);

if (empty($arResult['OFFERS'])) {
    $arResult['PRODUCT_ID'] = (int)$arResult['ID'];
} else {
    $arResult['PRODUCT_ID'] = (int)$arResult['price']['OFFER_ID'];

    foreach ($arResult['OFFERS'] as $iOfferNum => $offer) {
        $arResult['OFFERS']['OFFERS'][$iOfferNum]['PRICE'] = $price->getItemPrices(
            $offer['ID'],
            $offer['IBLOCK_ID'],
            $pricesId,
            $userGroups
        );
    }
}

foreach ($arResult["DISPLAY_PROPERTIES"] as $propertyCode => $property) {
    $arResult["CHARACTERISTICS"][$propertyCode] = $property;
}

$arResult['DELIVERIES'] = array(
    'COURIER' => array('EXIST' => false, 'NAME' => 'Курьером'),
    'PICKUP' => array('EXIST' => false, 'NAME' => 'Самовывоз'),
);
$arResult['PAY_SYSTEMS'] = array();

$serviceDelivery = new ServiceDelivery();
if (!empty((int)$arParams['LOCATION']['ID'])) {
    $arParams['LOCATION']['ZIP'] = (int)CSaleLocation::GetLocationZIP((int)$arParams['LOCATION']['ID'])->fetch();

    try {
        if (!Loader::includeModule('manom.nextjs')) {
            throw new \Exception();
        }

        $delivery = new Delivery();
        $paySystem = new PaySystem();

        $deliveries = $delivery->getDeliveries(
            array(
                'locationId' => (int)$arParams['LOCATION']['ID'],
                'zip' => $arParams['LOCATION']['ZIP'],
            ),
            $arResult['PRODUCT_ID']
        );

        $actualDeliveries = [];
        foreach ($deliveries as $delivery) {
            if ($isMoscow) {
                if (in_array((int)$delivery['id'], [
                    $serviceDelivery->getId("ownDelivery"),
                    $serviceDelivery->getId("ownPickup")
                ], true)) {
                    $actualDeliveries[] = $delivery;
                }
            } elseif (in_array((int)$delivery['id'], [
                $serviceDelivery->getId("cdekDelivery"),
                $serviceDelivery->getId("cdekPickup")
            ], true)) {
                $actualDeliveries[] = $delivery;
            }
        }

        foreach ($actualDeliveries as $actualDelivery) {
            if (in_array((int)$actualDelivery['id'], [
                $serviceDelivery->getId("cdekDelivery"),
                $serviceDelivery->getId("ownDelivery")
            ], true)) {
                $arResult['DELIVERIES']['COURIER'] = array_merge(
                    $arResult['DELIVERIES']['COURIER'],
                    [
                        'DESCRIPTION' => getDeliveryDescription($actualDelivery, $serviceDelivery),
                        'ID' => $actualDelivery['id'],
                        'EXIST' => true,
                    ]
                );
            }

            if (in_array((int)$actualDelivery['id'], [
                $serviceDelivery->getId("cdekPickup"),
                $serviceDelivery->getId("ownPickup")
            ], true)) {
                $arResult['DELIVERIES']['PICKUP'] = array_merge(
                    $arResult['DELIVERIES']['PICKUP'],
                    [
                        'DESCRIPTION' => getDeliveryDescription($actualDelivery, $serviceDelivery),
                        'ID' => $actualDelivery['id'],
                        'EXIST' => true,
                    ]
                );
            }
        }

        $arResult['DELIVERIES'] = array_filter(
            $arResult['DELIVERIES'],
            static function ($item) {
                return $item['EXIST'] === true;
            }
        );

        $deliveriesIds = array_values(
            array_map(
                static function ($item) {
                    return $item['ID'];
                },
                $arResult['DELIVERIES']
            )
        );

        $allPaySystems = $paySystem->getList(array('locationId' => (int)$arParams['LOCATION']['ID']));
        foreach ($allPaySystems as $paySystem) {
            if (count(array_intersect($paySystem['deliveries'], $deliveriesIds)) <= 0) {
                continue;
            }

            if (in_array((int)$paySystem['id'], [4, 8, 10], true)) {
                $arResult['PAY_SYSTEMS'][] = 'CARD';
            }

            if ((int)$paySystem['id'] === 7) {
                $arResult['PAY_SYSTEMS'][] = 'CASH';
            }
        }

        $arResult['PAY_SYSTEMS'] = array_unique($arResult['PAY_SYSTEMS']);
    } catch (\Exception $e) {
    }
}


$arResult["ACCESSORIES"] = new \Manom\Accessory($arResult["SECTION"]["ID"]);
$arResult["SERVICES"] = new Service((int)$arResult["SECTION"]["ID"]);

$arResult["ATTACH_DOCS"] = [];
$certificate = $arResult['DISPLAY_PROPERTIES']["CERTIFICATE"]["FILE_VALUE"];
$instructions = $arResult['DISPLAY_PROPERTIES']["INSTRUCTIONS"]["FILE_VALUE"];

if (!empty($certificate)) {
    if (!empty($certificate["ID"])) {
        $certificate = [$certificate];
    }
    $arResult["ATTACH_DOCS"]["CERTIFICATE"] = $certificate;
}

if (!empty($instructions)) {
    if (!empty($instructions["ID"])) {
        $instructions = [$instructions];
    }
    $arResult["ATTACH_DOCS"]["INSTRUCTIONS"] = $instructions;
}

$labels = [];

if ($arResult["DISPLAY_PROPERTIES"]["NEW_PRODUCT"]["VALUE"] === "Да") {
    $labels["NEW"] = true;
}

if ($arResult["DISPLAY_PROPERTIES"]["PRODUCT_OF_THE_DAY"]["VALUE"] === "Да") {
    $labels["PRODUCT_DAY"] = true;
}

if ($arResult["DISPLAY_PROPERTIES"]["SELL_PROD"]["VALUE"] === "Да" || $arResult['showOldPrice']) {
    $labels["SALE"] = true;
}

$labels["PREORDER"] = $arResult["preOrder"]["active"];

$arResult["LABELS"] = $labels;

$defectsCount = (int)$arParams["ECOMMERCE_DATA"]["storeData"]["defects"]["amount"];
$arResult['EXIST_DEFECTS'] = $defectsCount > 0;

function getCheaper($productId, $iblockId)
{
    $cheaper = array();

    $order = array('SORT' => 'ASC');
    $filter = array('IBLOCK_ID' => $iblockId, 'PROPERTY_SELLOUT' => $productId, 'ACTIVE' => 'Y');
    $select = array('IBLOCK_ID', 'ID');
    $result = CIBlockElement::GetList($order, $filter, false, false, $select);
    while ($row = $result->Fetch()) {
        $cheaper[] = (int)$row['ID'];
    }

    return $cheaper;
}

function getUser()
{
    $return = array();

    global $USER;

    if (!$USER->IsAuthorized()) {
        return $return;
    }

    $filter = array('ID' => $USER->GetID());
    $params = array('FIELDS' => array('ID', 'PERSONAL_PHONE'));
    $user = \CUser::GetList($by = 'ID', $order = 'ASC', $filter, $params)->Fetch();

    $return['NAME'] = $USER->GetFullName();
    $return['PHONE'] = strlen($user['PERSONAL_PHONE']) > 10 ?
        substr($user['PERSONAL_PHONE'], -10) :
        $user['PERSONAL_PHONE'];

    return $return;
}

function getReviews($productId)
{
    $reviews = array();

    $usersId = array();
    $order = array('ID' => 'DESC');
    $filter = array(
        'IBLOCK_ID' => 11,
        'PROPERTY_RV_PRODCTS' => $productId,
        'ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y',
    );
    $select = array(
        'IBLOCK_ID',
        'ID',
        'ACTIVE_FROM',
        'DATE_CREATE',
        'PREVIEW_TEXT',
        'PROPERTY_RV_USER',
        'PROPERTY_RV_RATING',
        'PROPERTY_RV_MERITS',
        'PROPERTY_RV_DISADVANTAGES',
        'PROPERTY_SOURCE',
        'PROPERTY_AUTHOR',
        'PROPERTY_RECOMMEND',
        'PROPERTY_FROM_AT',
    );
    $result = CIBlockElement::GetList($order, $filter, false, false, $select);
    while ($row = $result->Fetch()) {
        $dateCreate = explode(' ', !empty($row['ACTIVE_FROM']) ? $row['ACTIVE_FROM'] : $row['DATE_CREATE']);

        $item = array(
            'id' => (int)$row['ID'],
            'user_id' => (int)$row['PROPERTY_RV_USER_VALUE'],
            'review_text' => $row['PREVIEW_TEXT'],
            'rating' => (int)$row['PROPERTY_RV_RATING_VALUE'],
            'merits' => $row['PROPERTY_RV_MERITS_VALUE']['TEXT'],
            'source' => $row['PROPERTY_SOURCE_VALUE'],
            'author' => trim($row['PROPERTY_AUTHOR_VALUE']),
            'disadvantages' => $row['PROPERTY_RV_DISADVANTAGES_VALUE']['TEXT'],
            'user' => array(),
        );

        if ($row["PROPERTY_FROM_AT_VALUE"] !== "Y") {
            $item["recommend"] = $row['PROPERTY_RECOMMEND_VALUE'] === 'Y';
            $item["date"] = $dateCreate[0];
        }

        if (
            !empty($row['PROPERTY_RV_USER_VALUE']) &&
            !in_array((int)$row['PROPERTY_RV_USER_VALUE'], $usersId, true)
        ) {
            $usersId[] = (int)$row['PROPERTY_RV_USER_VALUE'];
        }

        $reviews[] = $item;
    }

    if (!empty($reviews)) {
        if (!empty($usersId)) {
            $filter = array('ID' => implode('|', $usersId));
            $params = array('FIELDS' => array('ID', 'NAME', 'LAST_NAME'));
            $result = CUser::GetList($by = 'ID', $order = 'ASC', $filter, $params);
            while ($row = $result->Fetch()) {
                foreach ($reviews as $i => $item) {
                    if ($item['user_id'] === $row['ID']) {
                        $reviews[$i]['user'] = array(
                            'name' => $row['NAME'],
                            'last_name' => $row['LAST_NAME'],
                            'full_name' => $row['NAME'].' '.$row['LAST_NAME'],
                        );
                    }
                }
            }
        }

        foreach ($reviews as $i => $item) {
            if (!empty(trim($item['user']['full_name']))) {
                $item['author'] = $item['user']['full_name'];
            }

            if ($item['rating'] > 5) {
                $item['rating'] = 5;
            } elseif ($item['rating'] < 0) {
                $item['rating'] = 0;
            }

            $reviews[$i] = $item;
        }
    }

    return $reviews;
}

function getQna($ids)
{
    $qna = array();

    if (empty($ids)) {
        return $qna;
    }

    $order = array('SORT' => 'AC');
    $filter = array('IBLOCK_ID' => 12, 'ID' => $ids, 'ACTIVE' => 'Y');
    $select = array('IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_TEXT');
    $result = CIBlockElement::GetList($order, $filter, false, false, $select);
    while ($row = $result->Fetch()) {
        $qna[] = array(
            'id' => (int)$row['ID'],
            'title' => $row['NAME'],
            'answer' => $row['PREVIEW_TEXT'],
        );
    }

    return $qna;
}

function getDelivery()
{
    $delivery = array();

    $order = array('SORT' => 'ASC');
    $filter = array('IBLOCK_ID' => 13, 'ACTIVE' => 'Y');
    $select = array('IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_TEXT');
    $result = CIBlockElement::GetList($order, $filter, false, false, $select);
    while ($row = $result->Fetch()) {
        $delivery[] = array(
            'id' => (int)$row['ID'],
            'title' => $row['NAME'],
            'text' => $row['PREVIEW_TEXT'],
        );
    }

    return $delivery;
}

function getDeliveryDescription($delivery, ServiceDelivery $serviceDelivery)
{
    $result = null;

    $deliveryPeriod = $delivery['period'];

    $week = new WeekTools();

    $shop = [
        'exist' => false,
        'time' => ['start' => 0, 'end' => 0],
        'dates' => ['start' => 0, 'end' => 0],
    ];

    $shop['exist'] = !empty($delivery['selfDeliveryPoints']);

    if ($shop['exist'] && $delivery['id'] === $serviceDelivery->getId("ownPickup")) {
        $scheduleData = $week->parseScheduleShop($delivery['selfDeliveryPoints'][0]['schedule']);

        $shop['time']['start'] = $scheduleData["hourStart"];
        $shop['time']['end'] = $scheduleData["hourEnd"];
        $shop['dates']['start'] = $scheduleData["dayStart"];
        $shop['dates']['end'] = $scheduleData["dayEnd"];
    }

    if ($shop['exist'] && $delivery['id'] === $serviceDelivery->getId("ownPickup")) {
        $deliveryPeriod = $week->getTextPeriod($shop);
    } elseif ($delivery['id'] === $serviceDelivery->getId("ownDelivery")) {
        $intervals = TimeDelivery::getIntervals();
        $courier = [
            'time' => [
                'start' => (int)array_shift($intervals)["fromHour"],
                'end' => (int)array_pop($intervals)["fromHour"],
            ],
            'dates' => [
                'start' => 1,
                'end' => 5,
            ],
        ];
        $deliveryPeriod = $week->getTextPeriod($courier);
    } elseif (in_array($delivery['id'], [$serviceDelivery->getId("cdekDelivery"), $serviceDelivery->getId("cdekPickup")], true)) {
        $sdek = [
            'isSdek' => true,
            'currentPeriod' => $deliveryPeriod,
            'time' => [
                'start' => 0,
                'end' => 0,
            ],
            'dates' => [
                'start' => 1,
                'end' => 5,
            ],
        ];
        $deliveryPeriod = $week->getTextPeriod($sdek);
    }

    $delivery['price'] = (int)$delivery['price'] > 0 ? $delivery['price'].' ₽' : 'Бесплатно';

    return ($deliveryPeriod ? $deliveryPeriod.', ' : '').$delivery['price'];
}

$this->__component->SetResultCacheKeys(["PROPERTIES"]);