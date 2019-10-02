<?
// use Bitrix\Main\Application;
// use Bitrix\Main\Web\Cookie;
// use Bitrix\Main\Loader,
//       Rover\GeoIp\Location;
function getRatingAndCountReviewForList($prodIDs) {
  $rating = 0;
  $arRev = array();
  $return = array();
  $getAllReviewByProdID = CIBlockElement::GetList(
    array('ID' => 'DESC'),
    array(
      'IBLOCK_ID' => 11,
      'PROPERTY_RV_PRODCTS' => $prodIDs,
      'ACTIVE' => 'Y'
    ),
    false,
    false,
    array(
      'PROPERTY_RV_RATING',
      'PROPERTY_RV_PRODCTS',
      'ID'
    )
  );
  $sumRating = array();
  while ($resReview = $getAllReviewByProdID->Fetch()) {
    if (!$sumRating[$resReview['PROPERTY_RV_PRODCTS_VALUE']])
      $sumRating[$resReview['PROPERTY_RV_PRODCTS_VALUE']] = 0;
    $sumRating[$resReview['PROPERTY_RV_PRODCTS_VALUE']] += (int)$resReview['PROPERTY_RV_RATING_VALUE'];
    $arRev[$resReview['PROPERTY_RV_PRODCTS_VALUE']][] = $resReview;
  }
  // if ($arRev) {
    foreach ($prodIDs as $key => $value) {
      $return[$value] = array();
      $return[$value]['rating'] = 0;
      $return[$value]['count'] = 0;
      if ($sumRating[$value]>0) {
        $return[$value]['rating'] = $sumRating[$value]/count($arRev[$value]);
        $return[$value]['count'] = count($arRev[$value]);
      }
    // }
  }
  return $return;
}
if($_GET["type"] == "catalog" && $_GET["mode"] == "import"):
  AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("MyHandlerClass", "OnBeforeIBlockElementUpdateHandler"));
  AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("MyHandlerClass", "OnBeforeIBlockElementAddHandler"));
  AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", Array("MyHandlerClass", "OnAfterIBlockSectionAddHandler"));
  AddEventHandler("iblock", "OnBeforeIBlockSectionUpdate", Array("MyHandlerClass", "OnAfterIBlockSectionAddHandler"));
endif;
AddEventHandler("sale", "OnBeforeEventAdd", Array("MyHandlerClass","OnBeforeEventAddHandler"));
AddEventHandler("sale", "OnSaleComponentOrderProperties", Array("MyHandlerClass","OnSaleComponentOrderPropertiesHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("MyHandlerClass","reIndexSku"));
AddEventHandler("sale", "OnSaleOrderBeforeSaved", Array("MyHandlerClass","checkTimeDelivery"));
AddEventHandler("sale", "OnSaleOrderBeforeSaved", Array("MyHandlerClass", "OnSaleOrderBeforeSavedHandler"));




// AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("MyHandlerClass", "OnBeforeIBlockElementAddHandler"));
// AddEventHandler("iblock", "OnAfterIBlockSectionAdd", Array("MyHandlerClass", "OnAfterIBlockSectionAddHandler"));
// AddEventHandler("iblock", "OnBeforeIBlockSectionAdd", Array("MyHandlerClass", "OnAfterIBlockSectionAddHandler"));

class MyHandlerClass
{
// создаем обработчик события "OnBeforeIBlockElementUpdate"
  function OnBeforeIBlockElementUpdateHandler(&$arFields) {
    unset($arFields["NAME"]);
    unset($arFields["IBLOCK_SECTION_ID"]);
    unset($arFields["IBLOCK_SECTION"]);
  }
  function OnBeforeIBlockElementAddHandler(&$arFields) {
    unset($arFields["IBLOCK_SECTION_ID"]);
    unset($arFields["SECTION_ID"]);
    unset($arFields["IBLOCK_SECTION"]);
    $arFields["IBLOCK_SECTION"] = array(204);
    $fp = fopen(__DIR__.'/filename.txt', 'w');
    fwrite($fp, print_r($arFields, TRUE));
    fclose($fp);
    return;
  }
  function OnAfterIBlockSectionAddHandler(&$arFields) {
    $arFields["IBLOCK_SECTION_ID"] = 200;
    // CIBlockSection::Delete($arFields['ID']);
    // $fp = fopen(__DIR__.'/filename.txt', 'w');
    // fwrite($fp, print_r($arFields, TRUE));
    // fclose($fp);
  }

    function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
    {
        try {
            if (!isSaleNotifyMessage($event) || empty($arFields["ORDER_ID"])) {
                throw new \Exception();
            }
            $order = \Bitrix\Sale\Order::load($arFields["ORDER_ID"]);
            $userId = $order->getUserId();
            $user = \Bitrix\Main\UserTable::getList([
                "filter" => [
                    "=ID" => $userId
                ],
                "select" => [
                    "ID",
                    "EMAIL"
                ]
            ])->fetch();
            if (!empty($user)) {
                throw new \Exception();
            }

            $oneClick = $user["EMAIL"] === "oneclick@manom.ru";

            if ($oneClick) {
                $event .= "_ONE_CLICK";
            }

        } catch (\Exception $e) {
        }
        return true;
    }


    function OnSaleComponentOrderPropertiesHandler(&$arUserResult, $request, &$arParams, &$arResult)
    {
        \Bitrix\Main\Loader::includeModule("sale");
        $registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);

        if (!method_exists($registry, "getPropertyClassName")) {
            return true;
        }

        /** @var \Bitrix\Sale\PropertyBase $propertyClassName */
        $propertyClassName = $registry->getPropertyClassName();

        if (!class_exists($propertyClassName)) {
            return true;
        }

        $properties = $propertyClassName::getList([
            'select' => ['ID', "CODE"],
            'filter' => [
                '=PERSON_TYPE_ID' => $arUserResult["PERSON_TYPE_ID"],
                'CODE' => [
                    "LOCATION",
                    "ZIP"
                ]
            ]
        ])->fetchAll();

        foreach ($properties as $property) {
            if ($property["CODE"] === "LOCATION") {
                if (!empty($arUserResult["ORDER_PROP"][$property["ID"]])) {
                    continue;
                }

                $userLocation = (new UserLocation)->getUserLocationInfo();

                if ((int)$userLocation["ID"] <= 0) {
                    continue;
                }

                $arLocation = \Bitrix\Sale\Location\LocationTable::getList([
                    'select' => ['CODE'],
                    'filter' => ['ID' => $userLocation["ID"]],
                ])->fetch();

                if (empty($arLocation)) {
                    continue;
                }

                $arUserResult["ORDER_PROP"][$property["ID"]] = $arLocation['CODE'];
            } else {
                if ($property["CODE"] === "ZIP") {
                    if ((int)$arUserResult["ORDER_PROP"][$property["ID"]] <= 0) {
                        $arUserResult["ORDER_PROP"][$property["ID"]] = "000000";
                    }
                }
            }
        }
    }

    function reIndexSku($arFields)
    {
        if ((int)$arFields["IBLOCK_ID"] !== 6) {
            return true;
        }

        $existOffers = \CCatalogSKU::getExistOffers([$arFields["ID"]])[$arFields["ID"]];

        if (!$existOffers) {
            return true;
        }

        $offersList = \CCatalogSKU::getOffersList([$arFields["ID"]]);
        $offers = [];
        if (!empty($offersList[$arFields["ID"]])) {
            $offers = $offersList[$arFields["ID"]];
        }

        if (empty($offers)) {
            return true;
        }

        $activeProduct = $arFields["ACTIVE"] === "Y";

        foreach ($offers as $offer) {
            if ($activeProduct) {
                \CAllIBlockElement::UpdateSearch($offer["ID"], true);
            } else {
                \CSearch::DeleteIndex("iblock", $offer["ID"]);
            }
        }
    }

    function checkTimeDelivery($entity) {
        //Тут ещё обрабатывается пустой почтовый индекс

        $orderFields = $entity->getFieldValues();

        $dateDelivery = $timeDelivery = null;
        $dateDeliveryExist = false;
        foreach ($entity->getPropertyCollection() as $property) {
            $propertyValue = $property->getFieldValues();

            if ($propertyValue["CODE"] === "DATE_DELIVERY") {
                $dateDelivery = $property->getValue();
                $dateDeliveryExist = true;
            }

            if ($propertyValue["CODE"] === "TIME_DELIVERY") {
                $timeDelivery = $property->getValue();
                if (is_array($timeDelivery)) {
                    $timeDelivery = $timeDelivery[0];
                }
                $dateDeliveryExist = true;
            }

            if ($propertyValue["CODE"] === "ZIP") {
                $zip = $property->getValue();
                if ((int)$zip <= 0) {
                    $property->setValue("000000");
                }
            }
        }

        if (!$dateDeliveryExist){
            return;
        }

        $timeRanges = [
            1 => 6,
            2 => 9,
            3 => 12,
            4 => 15,
            5 => 18,
        ];

        $currentHour = (int)date("G");
        $isPast = false;

        if ($dateDelivery){
            $isPast = strtotime($dateDelivery)===false;
            if (!$isPast){
                $isPast = strtotime($dateDelivery) < strtotime(date('d.m.Y'));
            }
        }

        if (!$isPast && $dateDelivery === date('d.m.Y')) {
            foreach ($timeRanges as $key => $range) {
                if ($currentHour >= $range && $key === (int)$timeDelivery) {
                    $isPast = true;
                }
            }
        }

        if ($isPast) {
            return new Bitrix\Main\EventResult(
                Bitrix\Main\EventResult::ERROR,
                new Bitrix\Sale\ResultError("Дата или время доставки указаны некорректно"),
                'sale'
            );
        } elseif (!$dateDelivery && $timeDelivery) {
            foreach ($entity->getPropertyCollection() as $property) {
                $propertyValue = $property->getFieldValues();

                if ($propertyValue["CODE"] === "TIME_DELIVERY") {
                    $property->setValue("");
                }
            }
        }
    }

    function OnSaleOrderBeforeSavedHandler($arFields)
    {
        \Bitrix\Main\Loader::includeModule("sale");
        $registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);

        if (!method_exists($registry, "getPropertyClassName")) {
            return true;
        }

        /** @var \Bitrix\Sale\PropertyBase $propertyClassName */
        $propertyClassName = $registry->getPropertyClassName();

        if (!class_exists($propertyClassName)) {
            return true;
        }

        $orderProperties = $arFields->getPropertyCollection()->getArray()["properties"];

        $properties = $propertyClassName::getList([
            'select' => ['ID', "CODE"],
            'filter' => [
                '=PERSON_TYPE_ID' => $arFields->getPersonTypeId(),
                'CODE' => [
                    "COMMENT"
                ]
            ]
        ])->fetchAll();

        foreach ($properties as $property) {
            if ($property["CODE"] === "COMMENT") {
                foreach ($orderProperties as $orderProperty) {
                    if ($orderProperty["ID"] === $property["ID"]) {
                        $arFields->setField("USER_DESCRIPTION", $orderProperty["VALUE"][0]);
                        break;
                    }
                }
                break;
            }
        }
    }
}
AddEventHandler("main", "OnBeforeUserLogin", Array("CUserEx", "OnBeforeUserLogin"));
AddEventHandler("main", "OnBeforeUserRegister", Array("CUserEx", "OnBeforeUserRegister"));
AddEventHandler("main", "OnBeforeUserRegister", Array("CUserEx", "OnBeforeUserUpdate"));
AddEventHandler("main", "OnAfterUserAdd", Array("CUserEx", "OnAfterUserAddHandler"));
AddEventHandler("main", "OnSendUserInfo", Array("CUserEx", "OnSendUserInfoHandler"));

class CUserEx
{
    public static $newUserPass;

    function OnBeforeUserLogin($arFields)
    {
        $filter = Array("EMAIL" => $arFields["LOGIN"]);
        $rsUsers = CUser::GetList(($by = "LAST_NAME"), ($order = "asc"), $filter);
        if ($user = $rsUsers->GetNext()) {
            $arFields["LOGIN"] = $user["LOGIN"];
        }
        /*else $arFields["LOGIN"] = "";*/
    }

    function OnBeforeUserRegister($arFields)
    {
        $arFields["LOGIN"] = $arFields["EMAIL"];
    }

    function OnAfterUserAddHandler($arFields)
    {
        self::$newUserPass = $arFields["CONFIRM_PASSWORD"];
    }

    function OnSendUserInfoHandler($arFields)
    {
        $arFields["FIELDS"]["PASSWORD"] = "Пароль: " . self::$newUserPass . "\n";
        self::$newUserPass = null;
    }
}

function checkProdInFavoriteAndCompareList($prodID, $code) {
  global $USER;
  global $APPLICATION;

  $hasProdInList = false;
  if ($USER->IsAuthorized()){
    $rsUsers = CUser::GetList(
      ($by="personal_country"),
      ($order="desc"),
      array('ID' => $USER->GetID()),
      array(
        'SELECT' => array($code)
      )
    );
    if ($resUsers = $rsUsers->Fetch()) {
      if (!$resUsers[$code]) {
        $resUsers[$code] = json_encode(array());
      }
      $favoriteList = json_decode($resUsers[$code]);

      $newList = array();
      foreach ($favoriteList as $i => $fav) {
        if ($fav == $prodID) {
          $hasProdInList = true;
        }
      }
    }
  }else{
    $listID = $APPLICATION->get_cookie($code);
    // print_r($listID);
    if (!$listID) {
      $listID = json_encode(array());
    }
    $favoriteList = json_decode($listID);

    $newList = array();
    foreach ($favoriteList as $i => $fav) {
      if ($fav == $prodID) {
        $hasProdInList = true;
      }
    }
  }
  return $hasProdInList;
}

function getProdListFavoritAndCompare($code) {
  global $USER;
  global $APPLICATION;

  $returnList = array();
  if ($USER->IsAuthorized()){
    $rsUsers = CUser::GetList(
      ($by="personal_country"),
      ($order="desc"),
      array('ID' => $USER->GetID()),
      array(
        'SELECT' => array($code)
      )
    );
    if ($resUsers = $rsUsers->Fetch()) {
      if (!$resUsers[$code]) {
        $resUsers[$code] = json_encode(array());
      }
      $favoriteList = json_decode($resUsers[$code]);

      foreach ($favoriteList as $i => $fav) {
        $returnList[] = $fav;
      }
    }
  }else{
    $listID = $APPLICATION->get_cookie($code);
    // print_r($listID);
    if (!$listID) {
      $listID = json_encode(array());
    }
    $favoriteList = json_decode($listID);

    foreach ($favoriteList as $i => $fav) {
      $returnList[] = $fav;
    }
  }
  return $returnList;
}

function getAllProdsWithoutReviewFromOrders() {
  global $USER;
  global $APPLICATION;

  $return = array();
  if ($USER->IsAuthorized()){
    $orderIDs = array();
    $arFilter = array('PAYED' => 'Y', "USER_ID" => $USER->GetID());
    $rsOrders = CSaleOrder::GetList(array('ID' => 'DESC'), $arFilter, false, false, array('ID'));
    while ($ar_sales = $rsOrders->Fetch())
    {
       $orderIDs[] = $ar_sales['ID'];
    }

    $return = array();
    if ($orderIDs) {
      $productIDs = array();
      $res = CSaleBasket::GetList(array(), array("ORDER_ID" => $ar_sales['ID']));
      while ($arItem = $res->Fetch()) {
        $productIDs[] = $arItem['PRODUCT_ID'];
      }
      if ($productIDs) {
        $alreadyHasReview = getAllProdByReview();
        $filt = array("IBLOCK_ID" => 7, "ID" => $productIDs);
        if ($alreadyHasReview) {
          $filt["!PROPERTY_CML2_LINK"] = $alreadyHasReview;
        }
        $getProds = CIBlockElement::GetList(
          array(),
          $filt,
          false,
          false,
          array('ID', 'PROPERTY_CML2_LINK')
        );
        while ($resProds = $getProds->Fetch()) {
          $return[md5($resProds['PROPERTY_CML2_LINK_VALUE'])] = $resProds['PROPERTY_CML2_LINK_VALUE'];
        }
      }
    }
  }
  return $return;
}

function getAllProdByReview() {
  global $USER;
  global $APPLICATION;

  $return = array();
  if ($USER->IsAuthorized()){
    $getReview = CIBlockElement::GetList(
      array(),
      array("IBLOCK_ID" => 11, "PROPERTY_RV_USER" => $USER->GetID()),
      false,
      false,
      array('ID', 'PROPERTY_RV_PRODCTS')
    );
    while ($resReview = $getReview->Fetch()) {
      if ($resReview['PROPERTY_RV_PRODCTS_VALUE']) {
        $return[] = $resReview['PROPERTY_RV_PRODCTS_VALUE'];
      }
    }
  }
  return $return;
}

function getUserReviewForProd($prodID) {
  global $USER;

  $return = false;
  if ($USER->IsAuthorized()){
    $getReview = CIBlockElement::GetList(
      array(),
      array("IBLOCK_ID" => 11, "PROPERTY_RV_USER" => $USER->GetID(), "PROPERTY_RV_PRODCTS" => $prodID),
      false,
      false,
      array('ID', 'PROPERTY_RV_MERITS', 'PROPERTY_RV_DISADVANTAGES', 'PROPERTY_RV_RATING', 'PREVIEW_TEXT', "DATE_CREATE")
    );
    if ($resReview = $getReview->Fetch()) {
      $return = array();
      $return['rating'] = $resReview['PROPERTY_RV_RATING_VALUE'];
      $return['comment'] = $resReview['PREVIEW_TEXT'];
      $return['merits'] = $resReview['PROPERTY_RV_MERITS_VALUE']['TEXT'];
      $return['disadvantages'] = $resReview['PROPERTY_RV_DISADVANTAGES_VALUE']['TEXT'];
      $return['date'] = $resReview['DATE_CREATE'];
    }
  }

  return $return;
}

function isSaleNotifyMessage($event) {
    return in_array($event, [
        "SALE_NEW_ORDER",
        "SALE_ORDER_CANCEL",
        "SALE_ORDER_DELIVERY",
        "SALE_ORDER_PAID",
        "SALE_ORDER_SHIPMENT_STATUS_CHANGED",
        "SALE_ORDER_TRACKING_NUMBER",
        "SALE_STATUS_CHANGED_F",
        "SALE_STATUS_CHANGED_N",
    ]);
}

function cssAutoVersion($file)
{
    if (strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
        return $file;
    }

    $modifyTime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);

    return $file . "?m={$modifyTime}";
}

AddEventHandler("main", "OnAfterUserRegister", array('UserHandler', 'afterRegister'));
class UserHandler
{
  function afterRegister(&$arFields) {
    $new_password = randString(10);
    $user = new CUser;
    $fields = Array(
      "PASSWORD"          => $new_password,
      "CONFIRM_PASSWORD"  => $new_password,
      );
    $user->Update($arFields['ID'], $fields);
    $msgData = array(
      'EMAIL' => $arFields['EMAIL'],
      'PASS' => $new_password,
    );
    CEvent::Send("USER_INFO", s1, $msgData, 'Y', 2);
  }
}

use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;
use Bitrix\Main\Loader;
use Rover\GeoIp\Location;
Loader::includeModule('rover.geoip');
CModule::IncludeModule('sale');
class UserLocation
{
  public function selectUserLocatonCityID($cityID) {
    $return = $this->getCityInfoByID($cityID);
    if ($return) {
      $this->setUserLocationCityID($return['ID']);
      $this->setUserSpecify(true);
    }
  }
  public function getUserLocationInfo() {
    $return = false;
    $cityID = $this->getUserLocationCityID();
    if ($cityID) {
      $return = $this->getCityInfoByID($cityID);
    }
    if (!$return) {
      $return = $this->getUserLocationByGeo();
      if (!$return) {
        $return = $this->getDefaultValue();
      }
    }
    return $return;
  }
  public function getListCity($name, $count=10){
    $return = array();
    $db_vars = CSaleLocation::GetList(
      array(
        "SORT" => "ASC",
        "COUNTRY_NAME_LANG" => "ASC",
        "CITY_NAME_LANG" => "ASC"
      ),
      array(
        "LID" => LANGUAGE_ID,
        "%CITY_NAME" => (string)$name
      ),
      false,
      array('nTopCount' => $count),
      array()
    );
    while ($vars = $db_vars->Fetch()) {
      $return[] = array(
        "title" => $vars['CITY_NAME'],
        "id" => $vars['ID'],
      );
    }
    return $return;
  }
  public function getUserSpecifyStatus() {
    $status = (bool)$this->getUserSpecify();
    return $status;
  }
  public function changeStatusSpecify() {
    $this->setUserSpecify(true);
  }
  public function getDefaultListOfCity(){
    $return = [];
    $db_vars = CSaleLocation::GetList(
      array(
        "SORT" => "ASC",
        "COUNTRY_NAME_LANG" => "ASC",
        "CITY_NAME_LANG" => "ASC"
      ),
      array(
        "LID" => LANGUAGE_ID,
        "CITY_NAME" => array('Москва', 'Санкт-Петербург', 'Екатеринбург', 'Нижний Новгород', 'Новосибирск', 'Казань')
      ),
      false,
      false,
      array()
    );
    while ($vars = $db_vars->Fetch()) {
      $return[] = array(
        "title" => $vars['CITY_NAME'],
        "id" => $vars['ID'],
      );
    }
    return $return;
  }

  private function getUserSpecify() {
    global $APPLICATION;
    $status = $APPLICATION->get_cookie("USER_LOCATION_SPECIFY_STATUS");
    return $status;
  }
  private function setUserSpecify($status) {
    $this->addCookie('USER_LOCATION_SPECIFY_STATUS', $status);
  }
  private function getDefaultValue(){
    $return = false;
    $db_vars = CSaleLocation::GetList(
      array(
        "SORT" => "ASC",
        "COUNTRY_NAME_LANG" => "ASC",
        "CITY_NAME_LANG" => "ASC"
      ),
      array(
        "LID" => LANGUAGE_ID,
        "CITY_NAME" => 'Москва'
      ),
      false,
      false,
      array()
    );
    if ($vars = $db_vars->Fetch()) {
      $return = $vars;
      $this->setUserLocationCityID($return['ID']);
    }
    $this->setUserSpecify(false);
    return $return;
  }
  private function getUserLocationByGeo(){
    $return = false;
    $application = Application::getInstance();
    $location = Location::getInstance(Location::getCurIp());
    if($location->getCityName()) {
      $cityInfo = $this->getCityInfoByName($location->getCityName());
      if ($cityInfo) {
        $return = $cityInfo;
        $this->setUserLocationCityID($cityInfo['ID']);
      }
    }
    $this->setUserSpecify(false);
    return $return;
  }
  private function getUserLocationCityID() {
    global $APPLICATION;
    $cityID = $APPLICATION->get_cookie("USER_LOCATION_CITY_ID");
    return $cityID;
  }
  private function setUserLocationCityID($cityID) {
    if ($cityID && $this->getCityInfoByID($cityID)){
      $this->addCookie('USER_LOCATION_CITY_ID', $cityID);
    }
  }
  private function getCityInfoByID($cityID) {
    $return = false;
    $db_vars = CSaleLocation::GetList(
      array(
        "SORT" => "ASC",
        "COUNTRY_NAME_LANG" => "ASC",
        "CITY_NAME_LANG" => "ASC"
      ),
      array(
        "LID" => LANGUAGE_ID,
        "ID" => $cityID
      ),
      false,
      false,
      array()
    );
    if ($vars = $db_vars->Fetch()) {
      $return = $vars;
    }
    return $return;
  }
  private function getCityInfoByName($cityName) {
    $return = false;
    $db_vars = CSaleLocation::GetList(
      array(
        "SORT" => "ASC",
        "COUNTRY_NAME_LANG" => "ASC",
        "CITY_NAME_LANG" => "ASC"
      ),
      array(
        "LID" => LANGUAGE_ID,
        "CITY_NAME" => $cityName
      ),
      false,
      false,
      array()
    );
    if ($vars = $db_vars->Fetch()) {
      $return = $vars;
    }
    return $return;
  }
  private function addCookie($code, $val) {
    $application = Application::getInstance();
    $context = $application->getContext();
    $cookie = new Cookie($code, $val, time()+60*60*24*30*12*2); $cookie->setDomain($context->getServer()->getHttpHost());
    $cookie->setHttpOnly(false);  $context->getResponse()->addCookie($cookie);
    $context->getResponse()->flush("");
  }
}
