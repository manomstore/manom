<?php

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

use \Bitrix\Main\Application,
    \Bitrix\Currency\CurrencyManager,
    \Bitrix\Sale\Order,
    \Bitrix\Sale\Basket,
    \Bitrix\Sale\Fuser,
    \Bitrix\Main\Context,
    \Bitrix\Main\Loader,
    \Bitrix\Main\Web\Cookie;

Loader::IncludeModule('main');
Loader::IncludeModule('iblock');
Loader::IncludeModule('form');
Loader::IncludeModule('sale');
Loader::IncludeModule('catalog');

global $USER;

$request = Context::getCurrent()->getRequest();

$type = $request->get('type');

if ($_POST['change_favorite_list'] === 'Y') { ?>
    <?php
    $retByAddFunc = false;
    if ($_POST['product_id']) {
        $retByAddFunc = changeValProp('UF_FAVORITE_ID', (int)$_POST['product_id']);
    } elseif ($_POST['clear_all']) {
        $retByAddFunc = clearProp('UF_FAVORITE_ID');
    }

    if (!$retByAddFunc && !is_array($retByAddFunc)) {
        $favList = getProdListFavoritAndCompare('UF_FAVORITE_ID');
    } else {
        $favList = $retByAddFunc;
    }

    global $favoriteFilter;
    $favoriteFilter = array('ID' => $favList, '>CATALOG_PRICE_1' => 0);
    ?>
    <?php if (!$favList): ?>
        <a href="" class="top-personal__heart" id="mini_favorite_header_counter">
            <img
                    src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/heart.svg"
                    alt="Иконка избранного"
                    width="17"
                    height="15"
            >
        </a>
        <?php /*
        <div class="preview-heart preview-heart--empty" id="mini_favorite_header">
            <p class="preview-heart-not-text">Нет товара</p>
        </div>
        */ ?>
    <?php else: ?>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:catalog.section',
            'favorite_mini',
            Array(
                'ACTION_VARIABLE' => '',
                'ADD_PICT_PROP' => '',
                'ADD_PROPERTIES_TO_BASKET' => 'N',
                'ADD_SECTIONS_CHAIN' => 'N',
                'ADD_TO_BASKET_ACTION' => '',
                'AJAX_MODE' => 'N',
                'AJAX_OPTION_ADDITIONAL' => '',
                'AJAX_OPTION_HISTORY' => 'N',
                'AJAX_OPTION_JUMP' => 'N',
                'AJAX_OPTION_STYLE' => 'N',
                'BACKGROUND_IMAGE' => '',
                'BASKET_URL' => '',
                'BROWSER_TITLE' => '',
                'CACHE_FILTER' => 'N',
                'CACHE_GROUPS' => 'Y',
                'CACHE_TIME' => 36000000,
                'CACHE_TYPE' => 'A',
                'COMPATIBLE_MODE' => 'Y',
                'CONVERT_CURRENCY' => 'N',
                'CUSTOM_FILTER' => '',
                'DETAIL_URL' => '',
                'DISABLE_INIT_JS_IN_COMPONENT' => 'Y',
                'DISPLAY_BOTTOM_PAGER' => 'Y',
                'DISPLAY_COMPARE' => 'N',
                'DISPLAY_TOP_PAGER' => 'N',
                'ELEMENT_SORT_FIELD' => 'sort',
                'ELEMENT_SORT_FIELD2' => 'id',
                'ELEMENT_SORT_ORDER' => 'asc',
                'ELEMENT_SORT_ORDER2' => 'desc',
                'ENLARGE_PRODUCT' => '',
                'FILE_404' => '',
                'FILTER_NAME' => 'favoriteFilter',
                'HIDE_NOT_AVAILABLE' => 'Y',
                'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
                'IBLOCK_ID' => 6,
                'IBLOCK_TYPE' => 'catalog',
                'INCLUDE_SUBSECTIONS' => 'Y',
                'LABEL_PROP' => array(),
                'LAZY_LOAD' => 'N',
                'LINE_ELEMENT_COUNT' => 0,
                'LOAD_ON_SCROLL' => 'N',
                'MESSAGE_404' => '',
                'MESS_BTN_ADD_TO_BASKET' => '',
                'MESS_BTN_BUY' => '',
                'MESS_BTN_DETAIL' => '',
                'MESS_BTN_SUBSCRIBE' => '',
                'MESS_NOT_AVAILABLE' => '',
                'META_DESCRIPTION' => '',
                'META_KEYWORDS' => '',
                'OFFERS_CART_PROPERTIES' => array(),
                'OFFERS_FIELD_CODE' => array(),
                'OFFERS_LIMIT' => 0,
                'OFFERS_PROPERTY_CODE' => array('MORE_PHOTO'),
                'OFFERS_SORT_FIELD' => 'sort',
                'OFFERS_SORT_FIELD2' => 'id',
                'OFFERS_SORT_ORDER' => 'asc',
                'OFFERS_SORT_ORDER2' => 'desc',
                'PAGER_BASE_LINK_ENABLE' => 'Y',
                'PAGER_DESC_NUMBERING' => 'N',
                'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
                'PAGER_SHOW_ALL' => 'N',
                'PAGER_SHOW_ALWAYS' => 'N',
                'PAGER_TEMPLATE' => 'catalog_section',
                'PAGER_TITLE' => 'Товары',
                'PAGE_ELEMENT_COUNT' => 999999,
                'PARTIAL_PRODUCT_PROPERTIES' => 'N',
                'PRICE_CODE' => array('Розничная'),
                'PRICE_VAT_INCLUDE' => 'Y',
                'PRODUCT_BLOCKS_ORDER' => '',
                'PRODUCT_DISPLAY_MODE' => 'N',
                'PRODUCT_ID_VARIABLE' => '',
                'PRODUCT_PROPERTIES' => array('MORE_PHOTO'),
                'PRODUCT_PROPS_VARIABLE' => '',
                'PRODUCT_QUANTITY_VARIABLE' => '',
                'PRODUCT_ROW_VARIANTS' => '',
                'PRODUCT_SUBSCRIPTION' => 'N',
                'PROPERTY_CODE' => array('MORE_PHOTO'),
                'PROPERTY_CODE_MOBILE' => array('MORE_PHOTO'),
                'RCM_PROD_ID' => '',
                'RCM_TYPE' => '',
                'SECTION_CODE' => '',
                'SECTION_CODE_PATH' => '',
                'SECTION_ID' => '',
                'SECTION_ID_VARIABLE' => '',
                'SECTION_URL' => '',
                'SECTION_USER_FIELDS' => array(),
                'SEF_MODE' => 'Y',
                'SEF_RULE' => '',
                'SET_BROWSER_TITLE' => 'N',
                'SET_LAST_MODIFIED' => 'N',
                'SET_META_DESCRIPTION' => 'N',
                'SET_META_KEYWORDS' => 'N',
                'SET_STATUS_404' => 'N',
                'SET_TITLE' => 'N',
                'SHOW_404' => 'N',
                'SHOW_ALL_WO_SECTION' => 'Y',
                'SHOW_CLOSE_POPUP' => 'N',
                'SHOW_DISCOUNT_PERCENT' => 'N',
                'SHOW_FROM_SECTION' => 'N',
                'SHOW_MAX_QUANTITY' => 'N',
                'SHOW_OLD_PRICE' => 'N',
                'SHOW_PRICE_COUNT' => '1',
                'SHOW_SLIDER' => 'N',
                'SLIDER_INTERVAL' => '',
                'SLIDER_PROGRESS' => 'N',
                'TEMPLATE_THEME' => '',
                'USE_ENHANCED_ECOMMERCE' => 'N',
                'USE_MAIN_ELEMENT_SECTION' => 'N',
                'USE_PRICE_COUNT' => 'N',
                'USE_PRODUCT_QUANTITY' => 'N',
                'AJAX' => $_REQUEST['AJAX_MIN_FAVORITE'] === 'Y',
            )
        ); ?>
    <?php endif; ?>
<?php } elseif ($_POST['change_compare_list'] === 'Y') { ?>
    <?php
    if ($_POST['product_id']) {
        $retByAddFunc = changeValProp('UF_COMPARE_ID', (int)$_POST['product_id']);
    } elseif ($_POST['clear_all']) {
        $retByAddFunc = clearProp('UF_COMPARE_ID');
    }

    if (!$retByAddFunc && !is_array($retByAddFunc)) {
        $favList = getProdListFavoritAndCompare('UF_COMPARE_ID');
    } else {
        $favList = $retByAddFunc;
    }

    global $compareFilter;
    $compareFilter = array('ID' => $favList, '>CATALOG_PRICE_1' => 0);
    ?>
    <?php if (!$favList): ?>
        <a href="/catalog/compare/" class="top-personal__heart" id="mini_compare_header_counter">
            <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/compare.svg" alt="Иконка сравнения" width="16" height="15">
        </a>
        <?php /*
        <div class="preview-heart preview-heart--empty" id="mini_compare_header">
            <p class="preview-heart-not-text">Нет товара</p>
        </div>
        */ ?>
    <?php else: ?>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:catalog.section',
            'compare_mini',
            Array(
                'ACTION_VARIABLE' => '',
                'ADD_PICT_PROP' => '',
                'ADD_PROPERTIES_TO_BASKET' => 'N',
                'ADD_SECTIONS_CHAIN' => 'N',
                'ADD_TO_BASKET_ACTION' => '',
                'AJAX_MODE' => 'N',
                'AJAX_OPTION_ADDITIONAL' => '',
                'AJAX_OPTION_HISTORY' => 'N',
                'AJAX_OPTION_JUMP' => 'N',
                'AJAX_OPTION_STYLE' => 'N',
                'BACKGROUND_IMAGE' => '',
                'BASKET_URL' => '',
                'BROWSER_TITLE' => '',
                'CACHE_FILTER' => 'N',
                'CACHE_GROUPS' => 'Y',
                'CACHE_TIME' => 36000000,
                'CACHE_TYPE' => 'A',
                'COMPATIBLE_MODE' => 'Y',
                'CONVERT_CURRENCY' => 'N',
                'CUSTOM_FILTER' => '',
                'DETAIL_URL' => '',
                'DISABLE_INIT_JS_IN_COMPONENT' => 'Y',
                'DISPLAY_BOTTOM_PAGER' => 'Y',
                'DISPLAY_COMPARE' => 'N',
                'DISPLAY_TOP_PAGER' => 'N',
                'ELEMENT_SORT_FIELD' => 'sort',
                'ELEMENT_SORT_FIELD2' => 'id',
                'ELEMENT_SORT_ORDER' => 'asc',
                'ELEMENT_SORT_ORDER2' => 'desc',
                'ENLARGE_PRODUCT' => '',
                'FILE_404' => '',
                'FILTER_NAME' => 'compareFilter',
                'HIDE_NOT_AVAILABLE' => 'Y',
                'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
                'IBLOCK_ID' => 6,
                'IBLOCK_TYPE' => 'catalog',
                'INCLUDE_SUBSECTIONS' => 'Y',
                'LABEL_PROP' => array(),
                'LAZY_LOAD' => 'N',
                'LINE_ELEMENT_COUNT' => 0,
                'LOAD_ON_SCROLL' => 'N',
                'MESSAGE_404' => '',
                'MESS_BTN_ADD_TO_BASKET' => '',
                'MESS_BTN_BUY' => '',
                'MESS_BTN_DETAIL' => '',
                'MESS_BTN_SUBSCRIBE' => '',
                'MESS_NOT_AVAILABLE' => '',
                'META_DESCRIPTION' => '',
                'META_KEYWORDS' => '',
                'OFFERS_CART_PROPERTIES' => array(),
                'OFFERS_FIELD_CODE' => array(),
                'OFFERS_LIMIT' => 0,
                'OFFERS_PROPERTY_CODE' => array('MORE_PHOTO'),
                'OFFERS_SORT_FIELD' => 'sort',
                'OFFERS_SORT_FIELD2' => 'id',
                'OFFERS_SORT_ORDER' => 'asc',
                'OFFERS_SORT_ORDER2' => 'desc',
                'PAGER_BASE_LINK_ENABLE' => 'Y',
                'PAGER_DESC_NUMBERING' => 'N',
                'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
                'PAGER_SHOW_ALL' => 'N',
                'PAGER_SHOW_ALWAYS' => 'N',
                'PAGER_TEMPLATE' => 'catalog_section',
                'PAGER_TITLE' => 'Товары',
                'PAGE_ELEMENT_COUNT' => 999999,
                'PARTIAL_PRODUCT_PROPERTIES' => 'N',
                'PRICE_CODE' => array('Розничная'),
                'PRICE_VAT_INCLUDE' => 'Y',
                'PRODUCT_BLOCKS_ORDER' => '',
                'PRODUCT_DISPLAY_MODE' => 'N',
                'PRODUCT_ID_VARIABLE' => '',
                'PRODUCT_PROPERTIES' => array('MORE_PHOTO'),
                'PRODUCT_PROPS_VARIABLE' => '',
                'PRODUCT_QUANTITY_VARIABLE' => '',
                'PRODUCT_ROW_VARIANTS' => '',
                'PRODUCT_SUBSCRIPTION' => 'N',
                'PROPERTY_CODE' => array('MORE_PHOTO'),
                'PROPERTY_CODE_MOBILE' => array('MORE_PHOTO'),
                'RCM_PROD_ID' => '',
                'RCM_TYPE' => '',
                'SECTION_CODE' => '',
                'SECTION_CODE_PATH' => '',
                'SECTION_ID' => '',
                'SECTION_ID_VARIABLE' => '',
                'SECTION_URL' => '',
                'SECTION_USER_FIELDS' => array(),
                'SEF_MODE' => 'Y',
                'SEF_RULE' => '',
                'SET_BROWSER_TITLE' => 'N',
                'SET_LAST_MODIFIED' => 'N',
                'SET_META_DESCRIPTION' => 'N',
                'SET_META_KEYWORDS' => 'N',
                'SET_STATUS_404' => 'N',
                'SET_TITLE' => 'N',
                'SHOW_404' => 'N',
                'SHOW_ALL_WO_SECTION' => 'Y',
                'SHOW_CLOSE_POPUP' => 'N',
                'SHOW_DISCOUNT_PERCENT' => 'N',
                'SHOW_FROM_SECTION' => 'N',
                'SHOW_MAX_QUANTITY' => 'N',
                'SHOW_OLD_PRICE' => 'N',
                'SHOW_PRICE_COUNT' => '1',
                'SHOW_SLIDER' => 'N',
                'SLIDER_INTERVAL' => '',
                'SLIDER_PROGRESS' => 'N',
                'TEMPLATE_THEME' => '',
                'USE_ENHANCED_ECOMMERCE' => 'N',
                'USE_MAIN_ELEMENT_SECTION' => 'N',
                'USE_PRICE_COUNT' => 'N',
                'USE_PRODUCT_QUANTITY' => 'N',
                'AJAX' => $_REQUEST['AJAX_MIN_COMPARE'] === 'Y',
            )
        ); ?>
    <?php endif; ?>
<?php } elseif ($type === "makeOrder") {
    require_once $_SERVER['DOCUMENT_ROOT'].'/roistat/autoload.php';
    $roistatText = 'Страница: '.$_SERVER['HTTP_REFERER'].'. Ид продукта: '.$request->get('productId');

    $roistatData = array(
        'name' => $request->get('name'),
        'phone' => $request->get('phone'),
        'email' => $request->get('email'),
        'text' => $roistatText,
    );
    \Roistat\RoistatSender::processQuickOrder($roistatData);
    $result = [
        "success" => false,
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


        $result["transaction"] = json_encode(GTM::getTransaction($orderResult->getId()));
        $result["success"] = $success;
    } catch (\Exception $e) {
    }

    die(json_encode($result));
}

function changeValProp($code, $prod_id)
{
    global $USER;
    global $APPLICATION;
    $return = false;

    if ($USER->IsAuthorized()) {
        $rsUsers = CUser::GetList(
            ($by = "personal_country"),
            ($order = "desc"),
            array('ID' => $USER->GetID()),
            array(
                'SELECT' => array($code),
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
    } else {
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

function clearProp($code)
{
    global $USER;
    global $APPLICATION;
    $return = false;

    if ($USER->IsAuthorized()) {
        $user = new CUser;
        $fields = array();
        $fields[$code] = json_encode(array());
        $user->Update($USER->GetID(), $fields);
    } else {
        $return = json_encode(array());
        // $APPLICATION->set_cookie($code, json_encode(array()), time()+60*60*24*30*12*2);
        addCookie($code, json_encode(array()));
    }
    return $return;
}

function addCookie($code, $val)
{
    $application = Application::getInstance();
    $context = $application->getContext();
    $cookie = new Cookie($code, $val, time() + 60 * 60 * 24 * 30 * 12 * 2);
    $cookie->setDomain($context->getServer()->getHttpHost());
    $cookie->setHttpOnly(false);
    $context->getResponse()->addCookie($cookie);
    $context->getResponse()->flush("");
}
