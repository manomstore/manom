<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();


$arResult['DISPLAY_OFFERS'] = array();
$arResult['OFFERS_BY_DISPLAY_PROP'] = array();
$arResult['DATA_PROP_FOR_CHOICE'] = array();
$arResult['COLOR_CODE_LIST'] = array();
$arResult['PHOTOS'] = array();
$photoHash = array();
$morePhotoHash = array();

$prodProps = array();
if($arResult['PROPERTIES']['BS_STR']['VALUE']) {
  foreach ($arResult['PROPERTIES']['BS_STR']['VALUE'] as $key => $val) {
    $prodProps[] = array(
      'id' => $arResult['PROPERTIES']['BS_STR']['VALUE']['PROPERTY_VALUE_ID']."_prod",
      'prop_id' => $arResult['PROPERTIES']['BS_STR']['VALUE']['ID']."_prod",
      'prop_code' => $arResult['PROPERTIES']['BS_STR']['VALUE']['CODE']."_prod",
      'title' => $val
    );
  }
}

// echo "<pre style='text-align:left;'>";print_r($arResult['OFFERS'][0]);echo "</pre>";
//Перебераем все предложения, исключаем недоступные, групируем свойства для сортировки
foreach ($arResult['OFFERS'] as $key => $offer) {
    $price = (float)$offer["CATALOG_PRICE_1"];
  if ($offer['CAN_BUY'] and $offer['CATALOG_QUANTITY'] > 0 && $price > 0) {
    $arResult['DISPLAY_OFFERS'][] = $offer;
    $priceNow = $offer['MIN_PRICE']['DISCOUNT_VALUE_VAT'];
    $priceOld = $offer['MIN_PRICE']['VALUE_VAT'];
    $priceDifference = '';
    if ($priceNow < $priceOld) {
      $priceDifference = $priceOld-$priceNow;
    }
    $offByDP = array(
      'id_offer' => $offer['ID'],
      'article' => $offer['PROPERTIES']['CML2_ARTICLE']['VALUE'],
      'name' => $offer['NAME'],
      'old_price' => $priceOld,
      'new_price' => $priceNow,
      'difference_price' => $priceDifference,
      'props' => array(),//$prodProps,
      'photo_hash' => false,
      'prod_code_top' => '',
      'model_top' => ''
    );
    $colorHash = false;
    if ($offer['PROPERTIES'][$arParams['TOP_MODEL_CODE']]['VALUE']) {
      $offByDP['model_top'] = $offer['PROPERTIES'][$arParams['TOP_MODEL_CODE']]['VALUE'];
    }
    if ($offer['PROPERTIES'][$arParams['TOP_CODE_PRODE_CODE']]['VALUE']) {
      $offByDP['prod_code_top'] = $offer['PROPERTIES'][$arParams['TOP_CODE_PRODE_CODE']]['VALUE'];
    }
    foreach ($offer['DISPLAY_PROPERTIES'] as $op => $prop) {
      if(!$prop['DISPLAY_VALUE']) continue;
      if($prop['CODE'] != $arParams['COLOR_REF_PROP_CODE']){
        if ($prop['PROPERTY_TYPE'] == 'S') {
          $offByDP['props'][$prop['CODE']] = array(
            'id' => $prop['PROPERTY_VALUE_ID'],
            'prop_id' => $prop['ID'],
            'prop_code' => $prop['CODE'],
            'title' => $prop['DISPLAY_VALUE']
          );
        }elseif($prop['PROPERTY_TYPE'] == 'L'){
          $offByDP['props'][$prop['CODE']] = array(
            'id' => $prop['VALUE_ENUM_ID'],
            'title' => $prop['DISPLAY_VALUE'],
            'prop_id' => $prop['ID'],
            'prop_code' => $prop['CODE'],
          );
          if (!$arResult['DATA_PROP_FOR_CHOICE'][$prop['CODE']]) {
            $arResult['DATA_PROP_FOR_CHOICE'][$prop['CODE']] = array(
              'CODE' => $prop['CODE'],
              'TITLE' => $prop['NAME'],
              'ID' => $prop['ID'],
              'VALUE' => array()
            );
          }
          $arResult['DATA_PROP_FOR_CHOICE'][$prop['CODE']]['VALUE'][md5($prop['VALUE_ENUM_ID'])] = array(
            'id' => $prop['VALUE_ENUM_ID'],
            'prop_id' => $prop['ID'],
            'prop_code' => $prop['CODE'],
            'title' => $prop['DISPLAY_VALUE']
          );
        }
      } else {
        $offByDP['props'][$prop['CODE']] = array(
          'id' => $prop['VALUE'],
          'title' => $prop['DISPLAY_VALUE'],
          'prop_id' => $prop['ID'],
          'prop_code' => $prop['CODE'],
        );
        if (!$arResult['DATA_PROP_FOR_CHOICE'][$prop['CODE']]) {
          $arResult['DATA_PROP_FOR_CHOICE'][$prop['CODE']] = array(
            'CODE' => $prop['CODE'],
            'TITLE' => $prop['NAME'],
            'ID' => $prop['ID'],
            'VALUE' => array()
          );
        }
        $colorHash = md5($prop['DISPLAY_VALUE']);
        $arResult['COLOR_CODE_LIST'][] = $prop['DISPLAY_VALUE'];
        $arResult['DATA_PROP_FOR_CHOICE'][$prop['CODE']]['VALUE'][$colorHash] = array(
          'id' => $prop['VALUE'],
          'prop_id' => $prop['ID'],
          'prop_code' => $prop['CODE'],
          'hash_offer_color' => $colorHash,
          'title' => $prop['DISPLAY_VALUE']
        );
      }
    }
    if ($colorHash and ($offer['PREVIEW_PICTURE'] or $offer['DETAIL_PICTURE'])){
      if(!$photoHash[$colorHash]){
        if ($offer['PREVIEW_PICTURE']['SRC']) {
          $offByDP['photo_hash'] = $colorHash;
          $photoHash[$colorHash] = true;
          $arResult['PHOTOS'][] = array(
            'id' => $offer['PREVIEW_PICTURE']['ID'],
            'is_offer' => true,
            'is_more_photo' => false,
            'hash_offer_color' => $colorHash,
            'src' => $offer['PREVIEW_PICTURE']['SRC']
          );
        } elseif($offer['DETAIL_PICTURE']['SRC']) {
          $offByDP['photo_hash'] = $colorHash;
          $photoHash[$colorHash] = true;
          $arResult['PHOTOS'][] = array(
            'id' => $offer['DETAIL_PICTURE']['ID'],
            'is_offer' => true,
            'is_more_photo' => false,
            'hash_offer_color' => $colorHash,
            'src' => $offer['DETAIL_PICTURE']['SRC']
          );
        }
      }else{
        $offByDP['photo_hash'] = $colorHash;
      }
    } elseif ($colorHash) {
      $offByDP['photo_hash'] = $colorHash;
    }

    if ($colorHash and $offer['PROPERTIES']['MORE_PHOTO']['VALUE']){
      if(!$morePhotoHash[$colorHash]){
        foreach ($offer['PROPERTIES']['MORE_PHOTO']['VALUE'] as $ind => $valuePhotoID) {
          // $offByDP['photo_hash'] = $colorHash;
          $morePhotoHash[$colorHash] = true;
          $arResult['PHOTOS'][] = array(
            'id' => $valuePhotoID,
            'is_offer' => true,
            'is_more_photo' => true,
            'hash_offer_color' => $colorHash,
            'src' => CFile::GetPath($valuePhotoID)
          );
        }
      }
    }
    $arResult['OFFERS_BY_DISPLAY_PROP'][] = $offByDP;
  }
}

if (empty($arResult['OFFERS_BY_DISPLAY_PROP'])) {
    \Bitrix\Iblock\Component\Tools::process404(
        'Элемент не найден', //Сообщение
        true, // Нужно ли определять 404-ю константу
        true // Устанавливать ли статус
    );
    exit;
}

// echo "<pre style='text-align:left;'>";print_r($arResult['OFFERS_BY_DISPLAY_PROP']);echo "</pre>";
//Проверка на доступность товара
if($arResult['DISPLAY_OFFERS']){
  $arResult['CAN_BUY'] = 'Y';
} else {
  $arResult['CAN_BUY'] = 'N';
}
//докидываем в общий масив основные фото товара
foreach ($arResult['PROPERTIES'][$arParams['PHOTO_PROP_CODE']]['VALUE'] as $key => $prop) {
  $arResult['PHOTOS'][] = array(
    'id' => $prop,
    'src' => CFile::GetPath($prop)
  );
}

//Получаем картинки цветов для свойства "Цвет"
CModule::IncludeModule('highloadblock');
$ID = $arParams['COLOR_REF_HB_ID']; //СЮДА ID ВАШЕГО HL ИНФОБЛОКА
$hldata = Bitrix\Highloadblock\HighloadBlockTable::getById($ID)->fetch();
$hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
$hlDataClass = $hldata['NAME'].'Table';

$arFilterHB = Array(
     Array(
        "LOGIC"=>"AND",
        Array(
           "UF_NAME"=> $arResult['COLOR_CODE_LIST'] //НАШ МАССИВ С ЦВЕТАМИ
        )
     )
  );
$result = $hlDataClass::getList(array(
  'select' => array('UF_FILE','UF_NAME'), //НАМ НУЖНЫ ТОЛЬКО НАЗВАНИЕ И КАРТИНКА
  'order' => array('UF_NAME' =>'ASC'),
  'filter' => $arFilterHB //ПРИМЕНЯМ СОЗДАННЫЙ ВЫШЕ ФИЛЬТР
));
while($res = $result->fetch())
{
  $img_path = CFile::GetPath($res["UF_FILE"]);
  $arResult['DATA_PROP_FOR_CHOICE'][$arParams['COLOR_REF_PROP_CODE']]['VALUE'][md5($res["UF_NAME"])]['img'] = $img_path;
}


//получаем отзывы
$arResult['PRODUCT_RATING'] = 0;
$arResult['REVIEWS'] = array();
$getAllReviewByProdID = CIBlockElement::GetList(
    array('ID' => 'DESC'),
    array(
        'IBLOCK_ID' => $arParams['REVIEW_IBLOCK_ID'],
        'PROPERTY_RV_PRODCTS' => $arResult['ID'],
        'ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y',
    ),
    false,
    false,
    array(
        'PROPERTY_RV_USER',
        'PROPERTY_RV_RATING',
        'PREVIEW_TEXT',
        'DATE_CREATE',
        'ACTIVE_FROM',
        'ID',
        'PROPERTY_RV_MERITS',
        'PROPERTY_RV_DISADVANTAGES',
        'PROPERTY_SOURCE',
        'PROPERTY_AUTHOR',
        'PROPERTY_RECOMMEND',
    )
);
$sumRating = 0;
$users = array();
while ($resReview = $getAllReviewByProdID->Fetch()) {
    $dateCreate = !empty($resReview['ACTIVE_FROM']) ? $resReview['ACTIVE_FROM'] : $resReview['DATE_CREATE'];
    $dateCreate = explode(' ', $dateCreate);
  $review_item = array(
    'id' => $resReview['ID'],
    'user_id' => $resReview['PROPERTY_RV_USER_VALUE'],
    'review_text' => $resReview['PREVIEW_TEXT'],
    'rating' => (int)$resReview['PROPERTY_RV_RATING_VALUE'],
    'merits' => $resReview['PROPERTY_RV_MERITS_VALUE']['TEXT'],
    'source' => $resReview['PROPERTY_SOURCE_VALUE'],
    'author' => $resReview['PROPERTY_AUTHOR_VALUE'],
    'disadvantages' => $resReview['PROPERTY_RV_DISADVANTAGES_VALUE']['TEXT'],
    'recommend' => $resReview['PROPERTY_RECOMMEND_VALUE'] === "Y",
    'date' => $dateCreate[0],
    'user' => array()
  );
  $sumRating += (int)$resReview['PROPERTY_RV_RATING_VALUE'];
  $arResult['REVIEWS'][] = $review_item;
  $users[md5($resReview['PROPERTY_RV_USER_VALUE'])] = $resReview['PROPERTY_RV_USER_VALUE'];
}
if ($arResult['REVIEWS']) {
  //получаем общий рейтинг товара
  if ($sumRating > 0) {
    $arResult['PRODUCT_RATING'] = ($sumRating/count($arResult['REVIEWS']));
  }
  //получаем инфу о юзерах из комментов
  $users_id_for_filter = '';
  foreach ($users as $value) {
    if (!$users_id_for_filter)
      $users_id_for_filter = $value;
    else
      $users_id_for_filter .= ' | '.$value;
  }

  $sortBy = "ID";
  $sortOrder = "ASC";

  $getUsersByID = CUser::GetList(
      $sortBy,
      $sortOrder,
    array(
      'ID' => $users_id_for_filter
    ),
    array(
      'FIELDS' => array(
        'NAME',
        'LAST_NAME',
        'ID'
      )
    )
  );
  while ($resUser = $getUsersByID->Fetch()) {
    foreach ($arResult['REVIEWS'] as $key => $rev) {
      if ($rev['user_id'] == $resUser['ID']) {
        $arResult['REVIEWS'][$key]['user'] = array(
          'name' => $resUser['NAME'],
          'last_name' => $resUser['LAST_NAME'],
          'full_name' => $resUser['NAME'].' '.$resUser['LAST_NAME']
        );
      }
    }
  }

    foreach ($arResult['REVIEWS'] as $key => &$rev) {
        if (!empty($rev["author"])) {
            $rev["author"] = trim($rev["author"]);
        }

        $rev["author"] = empty($rev["author"]) ? "" : $rev["author"];

        if (!empty($rev['user']) && !empty(trim($rev['user']["full_name"]))) {
            $rev["author"] = $rev['user']["full_name"];
        }

        $rev["rating"] = $rev["rating"] > 5 ? 5 : ($rev["rating"] < 0 ? 0 : $rev["rating"]);
    }
    unset($rev);
}
//Получаем Q'n'A

$arResult['QNA_VALUES'] = array();
if ($arResult['PROPERTIES'][$arParams['QNA_CODE']]['VALUE']) {
  $getQestionAndAnswer = CIBlockElement::GetList(
    array('sort' => 'asc'),
    array(
      'IBLOCK_ID' => $arParams['QNA_IBLOCK_ID'],
      'ID' => $arResult['PROPERTIES'][$arParams['QNA_CODE']]['VALUE'],
      'ACTIVE' => 'Y',
    ),
    false,
    false,
    array(
      'ID',
      'PREVIEW_TEXT',
      'NAME'
    )
  );
  while ($resQuestionAndAnswer = $getQestionAndAnswer->Fetch()) {
    $arResult['QNA_VALUES'][] = array(
      'title' => $resQuestionAndAnswer['NAME'],
      'answer' => $resQuestionAndAnswer['PREVIEW_TEXT'],
      'id' => $resQuestionAndAnswer['ID']
    );
  }
}
//получаем пункты доставки и оплаты
$arResult['DELIV'] = array();
$getDeliv = CIBlockElement::GetList(
  array('SORT' => 'asc'),
  array(
    'IBLOCK_ID' => $arParams['DELIV_IBLOCK_ID'],
    'ACTIVE' => 'Y'
  ),
  false,
  false,
  array(
    'ID',
    'NAME',
    'PREVIEW_TEXT'
  )
);
while ($resDeliv = $getDeliv->Fetch()) {
  $arResult['DELIV'][] = array(
    'title' => $resDeliv['NAME'],
    'text' => $resDeliv['PREVIEW_TEXT'],
    'id' => $resDeliv['ID']
  );
}

//получаем связаные товары из "Купить дешевле"
$arResult['CHEAPER'] = array();
// print_r($arResult['CHEAPER']);


$getCheaper = CIBlockElement::GetList(
  array('SORT' => 'asc'),
  array(
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'PROPERTY_SELLOUT' => $arResult['ID'],
    'ACTIVE' => 'Y'
  ),
  false,
  false,
  array(
    'ID'
  )
);
while ($resCheaper = $getCheaper->Fetch()) {
  $arResult['CHEAPER'][] = $resCheaper['ID'];
}

global $USER;

$arResult["CURRENT_USER"] = [
    "NAME" => "",
    "PHONE" => "",
];

if ($USER->IsAuthorized()) {
    $currentUser = \CUser::GetList(
        $by,
        $order,
        [
            "ID" => $USER->GetID()
        ],
        [
            "ID",
            "PERSONAL_PHONE"
        ]
    )->GetNext();

    $arResult["CURRENT_USER"]["NAME"] = $USER->GetFullName();
    $arResult["CURRENT_USER"]["PHONE"] = strlen($currentUser["PERSONAL_PHONE"]) > 10 ?
        substr($currentUser["PERSONAL_PHONE"], -10) : $currentUser["PERSONAL_PHONE"];
}

global $userCityByGeoIP;
$arResult["ONLY_CASH"] = $arResult["DISPLAY_PROPERTIES"]["ONLY_CASH"]["DISPLAY_VALUE"] === "Y";
$arResult["LOCATION_DISALLOW_BUY"] = $arResult["ONLY_CASH"] && ((int)$userCityByGeoIP["ID"] !== 84);

if (\Bitrix\Main\Loader::includeModule("hozberg.characteristics")) {
    $characteristicsIds = \Hozberg\Characteristics::getShowCharacteristics();
    foreach ($arResult["DISPLAY_PROPERTIES"] as $propertyCode => $property) {
        if (in_array($property["ID"], $characteristicsIds)) {
            $arResult["CHARACTERISTICS"][$propertyCode] = $property;
        }
    }
} else {
    $arResult["CHARACTERISTICS"] = $arResult["DISPLAY_PROPERTIES"];
}

global $userLocationInfo;
$arResult["CURRENT_CITY"] = $userLocationInfo;

$arResult["DELIVERIES"] = [
    "COURIER" => [
        "EXIST" => false,
        "NAME" => "Курьером",
    ],
    "PICKUP" => [
        "EXIST" => false,
        "NAME" => "Самовывоз",
    ],
];

$arResult["PAY_SYSTEMS"] = [];


if ((int)$arResult["CURRENT_CITY"]["ID"] > 0) {
    $saleLocationData = CSaleLocation::GetLocationZIP($arResult["CURRENT_CITY"]["ID"])->fetch();
    $arResult["CURRENT_CITY"]["ZIP"] = (int)$userLocationInfo;
    $arResult["CURRENT_CITY"]["ID"] = (int)$arResult["CURRENT_CITY"]["ID"];
    try {
        if (!\Bitrix\Main\Loader::includeModule('manom.nextjs')) {
            throw new \Exception();
        }

        $delivery = new \Manom\Nextjs\Api\Delivery();
        $paySystem = new \Manom\Nextjs\Api\PaySystem();

        $deliveries = $delivery->getDeliveries(
            [
                'locationId' => $arResult["CURRENT_CITY"]["ID"],
                'zip' => $arResult["CURRENT_CITY"]["ZIP"],
            ],
            $arResult['OFFERS_BY_DISPLAY_PROP'][0]["id_offer"]
        );

        $actualDeliveries = [];

        $isMoscow = $arResult["CURRENT_CITY"]["ID"] === 84;

        foreach ($deliveries as $delivery) {
            if ($isMoscow) {
                if (in_array($delivery["id"], [8, 13])) {
                    $actualDeliveries[] = $delivery;
                }
            } else {
                if (in_array($delivery["id"], [5, 6])) {
                    $actualDeliveries[] = $delivery;
                }
            }
        }

        foreach ($actualDeliveries as $actualDelivery) {
            if (in_array($actualDelivery["id"], [5, 8])) {
                $arResult["DELIVERIES"]["COURIER"] = array_merge(
                    $arResult["DELIVERIES"]["COURIER"],
                    [
                        "DESCRIPTION" => getDeliveryDescription($actualDelivery),
                        "ID" => $actualDelivery["id"],
                        "EXIST" => true,
                    ]
                );

            }

            if (in_array($actualDelivery["id"], [6, 13])) {
                $arResult["DELIVERIES"]["PICKUP"] = array_merge(
                    $arResult["DELIVERIES"]["PICKUP"],
                    [
                        "DESCRIPTION" => getDeliveryDescription($actualDelivery),
                        "ID" => $actualDelivery["id"],
                        "EXIST" => true,
                    ]
                );
            }
        }

        $arResult["DELIVERIES"] = array_filter($arResult["DELIVERIES"], function ($item) {
            return $item["EXIST"] === true;
        });


        $allPaySystems = $paySystem->getList(['locationId' => $arResult["CURRENT_CITY"]["ID"]]);

        $deliveriesIds = array_values(array_map(
            function ($item) {
                return $item["ID"];
            },
            $arResult["DELIVERIES"]
        ));

        foreach ($allPaySystems as $paySystem) {
            if (in_array($paySystem["id"], [4, 8, 10])
                && count(array_intersect($paySystem["deliveries"], $deliveriesIds)) > 0) {
                $arResult["PAY_SYSTEMS"][] = "CARD";
            }
            if (in_array($paySystem["id"], [7])
                && count(array_intersect($paySystem["deliveries"], $deliveriesIds)) > 0) {
                $arResult["PAY_SYSTEMS"][] = "CASH";
            }
        }

        $arResult["PAY_SYSTEMS"] = array_unique($arResult["PAY_SYSTEMS"]);

    } catch (\Exception $e) {
    }
}

function getDeliveryDescription($delivery)
{
    $result = null;
    $deliveryPeriod = $delivery["period"];

    $week = new class
    {
        public $currentDay = 0;
        public $currentHour = 0;
        public $days = [
            'вс',
            'пн',
            'вт',
            'ср',
            'чт',
            'пт',
            'сб'
        ];

        public function __construct()
        {
            $this->currentDay = (int)date("w");
            $this->currentHour = (int)date("G");
        }

        public function calcDiffDay($startDay, $endDay)
        {
            $sumDays = 0;
            $roundNum = 0;

            if ($endDay > count($this->days)) {
                return $sumDays;
            }

            $i = $startDay;
            while ($i < count($this->days)) {
                if ($roundNum <= 0) {
                    $roundNum = 1;
                }
                if ($i > $startDay || $roundNum > 1) {
                    $sumDays++;
                }

                if ($i === $endDay) {
                    break;
                }
                if ($i === (count($this->days) - 1)) {
                    $i = 0;
                    $roundNum++;
                } else {
                    $i++;
                }

            }
            return $sumDays;
        }

        public function getTextPeriod($deliveryObj)
        {
            $textNearestDate = "";
            if ($deliveryObj["isSdek"]) {
                $newCurPeriod = "";
                for ($i = 0; $i < strlen($deliveryObj["currentPeriod"]); $i++) {

                    if ((int)$deliveryObj["currentPeriod"][$i] > 0 || $deliveryObj["currentPeriod"][$i] === "-") {
                        $newCurPeriod .= $deliveryObj["currentPeriod"][$i];
                    }
                }
                $deliveryObj["currentPeriod"] = $newCurPeriod;

                $period = explode("-", $deliveryObj["currentPeriod"]);
                if (count($period) <= 0) {
                    return "";
                }

                $periodStart = array_shift($period);
                $periodEnd = array_shift($period);
                $offset = 0;

                if ($periodStart) {
                    if ($this->currentDay >= (int)$deliveryObj["dates"]["start"]
                        && $this->currentDay <= $deliveryObj["dates"]["end"]) {
                        $offset = 0;
                    } else {
                        $offset = $this->calcDiffDay($this->currentDay, $deliveryObj["dates"]["start"]);
                    }

                    $textNearestDate = (string)($offset + (int)$periodStart);
                }
                if ($periodEnd) {
                    $textNearestDate .= "-" . (string)($offset + (int)$periodEnd);
                }
                if (strlen($textNearestDate) > 0) {
                    $textNearestDate .= " дня";
                }
                return $textNearestDate;
            }

            if ($this->currentDay >= $deliveryObj["dates"]["start"] && $this->currentDay <= $deliveryObj["dates"]["end"]) {
                if ($this->currentHour < $deliveryObj["time"]["end"] - 1) {
                    $textNearestDate = $deliveryObj["exist"] ?
                        "Сегодня до " . $deliveryObj["time"]["end"] . ":00" : "Сегодня";

                } else {
                    if ($this->currentDay === $deliveryObj["dates"]["end"]) {
                        $textNearestDate = "Через 2 дня";
                    } else {
                        $textNearestDate = "Завтра";
                    }
                }
            } else {
                $dayOffset = $this->calcDiffDay($this->currentDay, $deliveryObj["dates"]["start"]);

                switch ($dayOffset) {
                    case 0:
                        $textNearestDate = "Сегодня";
                        break;
                    case 1:
                        $textNearestDate = "Завтра";
                        break;
                    case 2:
                        $textNearestDate = "Послезавтра";
                        break;
                    default:
                        $textNearestDate = "Через " . $dayOffset . " дня";
                        break;
                }
            }

            return $textNearestDate;
        }
    };

    $shop = [
        "exist" => false,
        "time" => [
            "start" => 0,
            "end" => 0,
        ],
        "dates" => [
            "start" => 0,
            "end" => 0,
        ],
    ];

    $shop["exist"] = !empty($delivery["selfDeliveryPoints"]);

    if ($shop["exist"] && $delivery["id"] === 13) {
        $schedule = explode(" ", $delivery["selfDeliveryPoints"][0]["schedule"]);
        $schedule = is_array($schedule) ? $schedule : [];
        $time = array_pop($schedule);
        $time = explode("-", $time);
        $shop["time"]["start"] = (int)array_shift($time);
        $shop["time"]["end"] = (int)array_shift($time);
        $days = array_pop($schedule);
        $days = explode("-", $days);
        $shop["dates"]["start"] = array_search(array_shift($days), $week->days);
        $shop["dates"]["end"] = array_search(array_shift($days), $week->days);
    }


    if ($shop["exist"] && $delivery["id"] === 13) {
        $deliveryPeriod = $week->getTextPeriod($shop);
    } elseif ($delivery["id"] === 8) {
        $courier = [
            "time" => [
                "start" => 6,
                "end" => 18,
            ],
            "dates" => [
                "start" => 1,
                "end" => 5,
            ],
        ];
        $deliveryPeriod = $week->getTextPeriod($courier);
    } elseif (in_array($delivery["id"], [5, 6])) {
        $sdek = [
            "isSdek" => true,
            "currentPeriod" => $deliveryPeriod,
            "time" => [
                "start" => 0,
                "end" => 0,
            ],
            "dates" => [
                "start" => 1,
                "end" => 5,
            ],
        ];
        $deliveryPeriod = $week->getTextPeriod($sdek);
    }


    $delivery["price"] = (int)$delivery["price"] > 0 ? "{$delivery["price"]} ₽" : "Бесплатно";

    return ($deliveryPeriod ? $deliveryPeriod . ', ' : '') . $delivery["price"];
}