<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;
CModule::IncludeModule("main");
CModule::IncludeModule("iblock");
CModule::IncludeModule("form");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
global $USER;


$uli = new UserLocation;
$return = array();
if ($_REQUEST['location_code']){
  switch ($_REQUEST['location_code']) {
    case 'getCityList':
      $list = [];
      if ($_REQUEST['location_search']):
        $list = $uli->getListCity($_REQUEST['location_search'], 10);
      endif;
      $return['listOfCity'] = $list;
      $return['location_search'] = $_REQUEST['location_search'];
      break;
    case 'changeCity':
      if ($_REQUEST['cityID']):
        $list = $uli->selectUserLocatonCityID($_REQUEST['cityID']);
      endif;
      break;
    case 'changeStatusSpecify':
      $list = $uli->changeStatusSpecify();
      break;

    default:
      break;
  }
}

echo json_encode($return);
