<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application,
    \Bitrix\Currency\CurrencyManager,
    \Bitrix\Sale\Order,
    \Bitrix\Sale\Basket,
    \Bitrix\Sale\Fuser,
    \Bitrix\Main\Context,
    Bitrix\Main\Loader,
    Bitrix\Main\Web\Cookie;

Loader::IncludeModule("main");
Loader::IncludeModule("iblock");
Loader::IncludeModule("form");
Loader::IncludeModule("sale");
Loader::IncludeModule("catalog");

global $USER;

// echo $USER->GetID();
// echo "string";
// changeValProp('UF_FAVORITE_ID', 1);

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$type = $request->get("type");

if ($_POST['change_favorite_list'] == "Y") {
  $retByAddFunc = false;
  if ($_POST['product_id']) {
    $retByAddFunc = changeValProp('UF_FAVORITE_ID', (int)$_POST['product_id']);
  } elseif ($_POST['clear_all']) {
    $retByAddFunc = clearProp('UF_FAVORITE_ID');
  }
  global $customFilt;
  if (!$retByAddFunc and !is_array($retByAddFunc)){
    $favList = getProdListFavoritAndCompare('UF_FAVORITE_ID');
  }else{
    $favList = $retByAddFunc;
  }
  $customFilt = array('ID' => $favList);
	$customFilt = array_merge(
		$customFilt,
		[
			">CATALOG_PRICE_1" => 0,
		]
	);
  // print_r($customFilt);
  // die();
  ?>
  <?if(!$favList):?>
    <a href="" class="top-personal__heart" id="mini_favorite_header_counter">
      <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/heart.svg" alt="Иконка избранного"
           width="17"
           height="15">
    </a>
<!--    <div class="preview-heart preview-heart--empty" id="mini_favorite_header">-->
<!--      <p class="preview-heart-not-text">Нет товара</p>-->
<!--    </div>-->
  <?else:?><?
    $APPLICATION->IncludeComponent(
      "bitrix:catalog.section",
      "favorite_mini",
      array(
        "ACTION_VARIABLE" => "action",
        "ADD_PICT_PROP" => "MORE_PHOTO",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "ADD_TO_BASKET_ACTION" => "ADD",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "BACKGROUND_IMAGE" => "-",
        "BASKET_URL" => "/personal/basket.php",
        "BRAND_PROPERTY" => "-",
        "BROWSER_TITLE" => "-",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COMPATIBLE_MODE" => "Y",
        "CONVERT_CURRENCY" => "Y",
        "CURRENCY_ID" => "RUB",
        "CUSTOM_FILTER" => "",
        "DATA_LAYER_NAME" => "dataLayer",
        "DETAIL_URL" => "",
        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "ELEMENT_SORT_FIELD" => 'ID',//'CATALOG_PRICE_1',//$arParams["ELEMENT_SORT_FIELD"],
        "ELEMENT_SORT_ORDER" => 'asc',//'DESC',//$arParams["ELEMENT_SORT_ORDER"],
        "ENLARGE_PRODUCT" => "PROP",
        "ENLARGE_PROP" => "-",
        "FILTER_NAME" => "customFilt",
        "HIDE_NOT_AVAILABLE" => "N",
        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
        "IBLOCK_ID" => "7",
        "IBLOCK_TYPE" => "catalog",
        "INCLUDE_SUBSECTIONS" => "Y",
        "LABEL_PROP" => array(
        ),
        "LABEL_PROP_MOBILE" => "",
        "LABEL_PROP_POSITION" => "top-left",
        "LAZY_LOAD" => "Y",
        "LINE_ELEMENT_COUNT" => "3",
        "LOAD_ON_SCROLL" => "N",
        "MESSAGE_404" => "",
        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
        "MESS_BTN_BUY" => "Купить",
        "MESS_BTN_DETAIL" => "Подробнее",
        "MESS_BTN_LAZY_LOAD" => "Показать ещё",
        "MESS_BTN_SUBSCRIBE" => "Подписаться",
        "MESS_NOT_AVAILABLE" => "Нет в наличии",
        "META_DESCRIPTION" => "-",
        "META_KEYWORDS" => "-",
        "OFFERS_PROPERTY_CODE" => array(
          0 => "COLOR_REF",
          1 => "SIZES_SHOES",
          2 => "SIZES_CLOTHES",
          3 => "",
          4 => "",
        ),
        "OFFERS_LIMIT" => "5",
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_ORDER2" => "desc",
        "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
        "OFFER_TREE_PROPS" => array(
          0 => "COLOR_REF",
          1 => "SIZES_SHOES",
          2 => "SIZES_CLOTHES",
        ),
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "catalog_section",
        "PAGER_TITLE" => "Товары",
        "PAGE_ELEMENT_COUNT" => 9999,//3,//$arParams["PAGE_ELEMENT_COUNT"],
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "PRICE_CODE" => array(
          0 => "Розничная",
        ),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
        "PRODUCT_DISPLAY_MODE" => "Y",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_PROPERTIES" => array(
        ),
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PRODUCT_QUANTITY_VARIABLE" => "",
        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
        "PRODUCT_SUBSCRIPTION" => "N",
        "PROPERTY_CODE" => array(
          0 => "",
          1 => "NEWPRODUCT",
          2 => "",
        ),
        "PROPERTY_CODE_MOBILE" => array(
        ),
        "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
        "RCM_TYPE" => "personal",
        "SECTION_CODE" => "",
        "SECTION_ID" => "",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "SECTION_URL" => "",
        "SECTION_USER_FIELDS" => array(
          0 => "",
          1 => "",
        ),
        "SEF_MODE" => "Y",
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SHOW_ALL_WO_SECTION" => "N",
        "SHOW_CLOSE_POPUP" => "N",
        "SHOW_DISCOUNT_PERCENT" => "N",
        "SHOW_FROM_SECTION" => "N",
        "SHOW_MAX_QUANTITY" => "N",
        "SHOW_OLD_PRICE" => "N",
        "SHOW_PRICE_COUNT" => "1",
        "SHOW_SLIDER" => "Y",
        "SLIDER_INTERVAL" => "3000",
        "SLIDER_PROGRESS" => "N",
        "TEMPLATE_THEME" => "blue",
        "USE_ENHANCED_ECOMMERCE" => "Y",
        "USE_MAIN_ELEMENT_SECTION" => "N",
        "USE_PRICE_COUNT" => "N",
        "USE_PRODUCT_QUANTITY" => "N",
        "COMPONENT_TEMPLATE" => "favorite",
        "DISPLAY_COMPARE" => "N"
      ),
      false
    );
endif;
}
elseif ($_POST['change_compare_list'] == "Y") {
  if ($_POST['product_id']) {
    $retByAddFunc = changeValProp('UF_COMPARE_ID', (int)$_POST['product_id']);
  } elseif ($_POST['clear_all']) {
    $retByAddFunc = clearProp('UF_COMPARE_ID');
  }
  global $customFilt;
  if (!$retByAddFunc and !is_array($retByAddFunc)){
    $favList = getProdListFavoritAndCompare('UF_COMPARE_ID');
  }else{
    $favList = $retByAddFunc;
  }
  $customFilt = array('ID' => $favList);
	$customFilt = array_merge(
		$customFilt,
		[
			">CATALOG_PRICE_1" => 0,
		]
	);
  ?>
  <?if(!$favList):?>
    <a href="/catalog/compare/" class="top-personal__heart" id="mini_compare_header_counter">
      <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/compare.svg" alt="Иконка сравнения" width="16" height="15">
    </a>
<!--    <div class="preview-heart preview-heart--empty" id="mini_compare_header">-->
<!--      <p class="preview-heart-not-text">Нет товара</p>-->
<!--    </div>-->
  <?else:?><?
    $APPLICATION->IncludeComponent(
      "bitrix:catalog.section",
      "compare_mini",
      array(
        "ACTION_VARIABLE" => "action",
        "ADD_PICT_PROP" => "MORE_PHOTO",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "ADD_TO_BASKET_ACTION" => "ADD",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "BACKGROUND_IMAGE" => "-",
        "BASKET_URL" => "/personal/basket.php",
        "BRAND_PROPERTY" => "-",
        "BROWSER_TITLE" => "-",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "COMPATIBLE_MODE" => "Y",
        "CONVERT_CURRENCY" => "Y",
        "CURRENCY_ID" => "RUB",
        "CUSTOM_FILTER" => "",
        "DATA_LAYER_NAME" => "dataLayer",
        "DETAIL_URL" => "",
        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
        "DISCOUNT_PERCENT_POSITION" => "bottom-right",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "ELEMENT_SORT_FIELD" => 'ID',//'CATALOG_PRICE_1',//$arParams["ELEMENT_SORT_FIELD"],
        "ELEMENT_SORT_ORDER" => 'asc',//'DESC',//$arParams["ELEMENT_SORT_ORDER"],
        "ENLARGE_PRODUCT" => "PROP",
        "ENLARGE_PROP" => "-",
        "FILTER_NAME" => "customFilt",
        "HIDE_NOT_AVAILABLE" => "N",
        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
        "IBLOCK_ID" => "7",
        "IBLOCK_TYPE" => "catalog",
        "INCLUDE_SUBSECTIONS" => "Y",
        "LABEL_PROP" => array(
        ),
        "LABEL_PROP_MOBILE" => "",
        "LABEL_PROP_POSITION" => "top-left",
        "LAZY_LOAD" => "Y",
        "LINE_ELEMENT_COUNT" => "3",
        "LOAD_ON_SCROLL" => "N",
        "MESSAGE_404" => "",
        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
        "MESS_BTN_BUY" => "Купить",
        "MESS_BTN_DETAIL" => "Подробнее",
        "MESS_BTN_LAZY_LOAD" => "Показать ещё",
        "MESS_BTN_SUBSCRIBE" => "Подписаться",
        "MESS_NOT_AVAILABLE" => "Нет в наличии",
        "META_DESCRIPTION" => "-",
        "META_KEYWORDS" => "-",
        "OFFERS_PROPERTY_CODE" => array(
          0 => "COLOR_REF",
          1 => "SIZES_SHOES",
          2 => "SIZES_CLOTHES",
          3 => "",
          4 => "",
        ),
        "OFFERS_LIMIT" => "5",
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_ORDER2" => "desc",
        "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
        "OFFER_TREE_PROPS" => array(
          0 => "COLOR_REF",
          1 => "SIZES_SHOES",
          2 => "SIZES_CLOTHES",
        ),
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "catalog_section",
        "PAGER_TITLE" => "Товары",
        "PAGE_ELEMENT_COUNT" => 9999,//3,//$arParams["PAGE_ELEMENT_COUNT"],
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "PRICE_CODE" => array(
          0 => "Розничная",
        ),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
        "PRODUCT_DISPLAY_MODE" => "Y",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_PROPERTIES" => array(
        ),
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PRODUCT_QUANTITY_VARIABLE" => "",
        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
        "PRODUCT_SUBSCRIPTION" => "N",
        "PROPERTY_CODE" => array(
          0 => "",
          1 => "NEWPRODUCT",
          2 => "",
        ),
        "PROPERTY_CODE_MOBILE" => array(
        ),
        "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
        "RCM_TYPE" => "personal",
        "SECTION_CODE" => "",
        "SECTION_ID" => "",
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "SECTION_URL" => "",
        "SECTION_USER_FIELDS" => array(
          0 => "",
          1 => "",
        ),
        "SEF_MODE" => "Y",
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SHOW_ALL_WO_SECTION" => "N",
        "SHOW_CLOSE_POPUP" => "N",
        "SHOW_DISCOUNT_PERCENT" => "N",
        "SHOW_FROM_SECTION" => "N",
        "SHOW_MAX_QUANTITY" => "N",
        "SHOW_OLD_PRICE" => "N",
        "SHOW_PRICE_COUNT" => "1",
        "SHOW_SLIDER" => "Y",
        "SLIDER_INTERVAL" => "3000",
        "SLIDER_PROGRESS" => "N",
        "TEMPLATE_THEME" => "blue",
        "USE_ENHANCED_ECOMMERCE" => "Y",
        "USE_MAIN_ELEMENT_SECTION" => "N",
        "USE_PRICE_COUNT" => "N",
        "USE_PRODUCT_QUANTITY" => "N",
        "COMPONENT_TEMPLATE" => "favorite",
        "DISPLAY_COMPARE" => "N"
      ),
      false
    );
endif;
}
elseif ($type === "makeOrder") {
    //Roistat integration begin
    require_once $_SERVER['DOCUMENT_ROOT'].'/roistat/autoload.php';
    $roistatText = 'Страница: '.$_SERVER['HTTP_REFERER'].'. Ид продукта: '.$request->get('productId');

    $roistatData = array(
            'name'=>$request->get('name'),
            'phone'=>$request->get('phone'),
            'email'=>$request->get('email'),
            'text'=>$roistatText,
    );
    \Roistat\RoistatSender::processQuickOrder($roistatData);
    //Roistat integration end
    $result = [
        "success" => false
    ];

    try {
        if (!$request->isPost()) {
            throw new \Exception();
        }

        if (!check_bitrix_sessid()) {
            throw new \Exception();
        }

        if (
            (int)$request->get("productId") <= 0
            || empty($request->get("name"))
            || empty($request->get("phone"))
            || (
                empty($request->get("email"))
                && !$USER->IsAuthorized()
            )
        ) {
            throw new \Exception();
        }

        $fields = [
            'PRODUCT_ID' => $request->get("productId"),
            'QUANTITY' => 1,
        ];

        $r = Bitrix\Catalog\Product\Basket::addProduct($fields);
        if (!$r->isSuccess()) {
            throw new \Exception();
        }

        $obBasket = Basket::loadItemsForFUser(
            Fuser::getId(),
            Context::getCurrent()->getSite()
        );

        $request = Context::getCurrent()->getRequest();
        $personTypeId = 1;
        $userId = null;

        $orderProp = [
            "FIO" => $request->get("name"),
            "PHONE" => $request->get("phone"),
        ];

        if ($USER->isAuthorized()) {
            $userId = $USER->GetID();
            $orderProp["EMAIL"] = $USER->GetEmail();
        } else {
            $defaultUser = \CUser::GetList($by, $order, ["=EMAIL" => "oneclick@manom.ru"])->GetNext();
            if (empty($defaultUser)) {
                throw new \Exception();
            }
            $userId = $defaultUser["ID"];
            $orderProp["EMAIL"] = $request->get("email");
        }

        $order = Order::create(
            Context::getCurrent()->getSite(),
            $userId
        );

        /** @var $obBasket Basket; */

        $order->setBasket($obBasket);

        $order->setPersonTypeId($personTypeId);
        $order->setField('CURRENCY', CurrencyManager::getBaseCurrency());

        $propertyCollection = $order->getPropertyCollection();
        foreach ($propertyCollection as $property) {
            if ((int)$property->getPersonTypeId() !== $personTypeId || $property->isUtil()) {
                continue;
            }

            if (array_key_exists($property->getField("CODE"), $orderProp)) {
                $property->setValue($orderProp[$property->getField("CODE")]);
            }
        }

        $order->doFinalAction(true);
        $orderResult = $order->save();
        $success = $orderResult->isSuccess();

        if (!$success) {
            throw new \Exception();
        }

        $result["success"] = $success;
    } catch (\Exception $e) {
    }
    die(json_encode($result));
}

function changeValProp($code, $prod_id) {
  global $USER;
  global $APPLICATION;
  $return = false;

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
      $hasProd = false;

      $newList = array();
      foreach ($favoriteList as $i => $fav) {
        if ($fav == $prod_id) {
          $hasProd = true;
        } else {
          $newList[] = $fav;
        }
      }
      if (!$hasProd) {
        $newList[] = $prod_id;
      }
      $user = new CUser;
      $fields = array();
      $fields[$code] = json_encode($newList);

      $user->Update($USER->GetID(), $fields);
    }
  }else{
    $listID = $APPLICATION->get_cookie($code);
    if (!$listID) {
      $listID = json_encode(array());
    }
    $favoriteList = json_decode($listID);
    $hasProd = false;

    $newList = array();
    foreach ($favoriteList as $i => $fav) {
      if ($fav == $prod_id) {
        $hasProd = true;
      } else {
        $newList[] = $fav;
      }
    }
    if (!$hasProd) {
      $newList[] = $prod_id;
    }
    $return = $newList;
    // $APPLICATION->set_cookie($code, json_encode($newList), time()+60*60*24*30*12*2);
    addCookie($code, json_encode($newList));
  }
  return $return;
}
function clearProp($code) {
  global $USER;
  global $APPLICATION;
  $return = false;

  if ($USER->IsAuthorized()){
    $user = new CUser;
    $fields = array();
    $fields[$code] = json_encode(array());
    $user->Update($USER->GetID(), $fields);
  }else{
    $return = json_encode(array());
    // $APPLICATION->set_cookie($code, json_encode(array()), time()+60*60*24*30*12*2);
    addCookie($code, json_encode(array()));
  }
  return $return;
}

function addCookie($code, $val) {
  $application = Application::getInstance();
  $context = $application->getContext();
  $cookie = new Cookie($code, $val, time()+60*60*24*30*12*2); $cookie->setDomain($context->getServer()->getHttpHost());
  $cookie->setHttpOnly(false);  $context->getResponse()->addCookie($cookie);
  $context->getResponse()->flush("");
}
// require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
