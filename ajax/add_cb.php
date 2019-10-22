<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("main");
CModule::IncludeModule("iblock");
CModule::IncludeModule("form");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
if ($_REQUEST['name'] and $_REQUEST['phone'] and $_REQUEST['form_id']){
  if ($_REQUEST['form_id'] == '1') {
    $form_id = 1;
    $data_form = array(
      'form_text_1' => $_REQUEST['name'],
      'form_text_2' => $_REQUEST['phone'],
    );
    $form_name = 'Обрантный звнок';
    $msgData = array(
      'title' => "Заявка с формы \"".$form_name."\"",
      'form_name' => $form_name,
      'user_name' => $_REQUEST['name'],
      'user_phone' => $_REQUEST['phone'],
      'dop_info' => ''
    );

    //Roistat integration begin
      require_once $_SERVER['DOCUMENT_ROOT'].'/roistat/autoload.php';
      $roistatText = 'Страница: '.$_SERVER['HTTP_REFERER'].'. Ид продукта: '.$request->get('productId');

      $roistatData = array(
          'name'=>$_REQUEST['name'],
          'phone'=>$_REQUEST['phone'],
      );
      \Roistat\RoistatSender::processCallback($roistatData);
    //Roistat integration end
  }
  if ($_REQUEST['form_id'] == '2') {
    $form_id = 2;
    $data_form = array(
      'form_text_3' => $_REQUEST['name'],
      'form_text_4' => $_REQUEST['phone'],
      'form_text_5' => $_REQUEST['prod_name'],
      'form_text_6' => $_REQUEST['prod_id'],
      'form_text_7' => $_REQUEST['email'],
    );
    $form_name = 'Заказ товара в один клик';
    $msgData = array(
      'title' => "Заявка с формы \"".$form_name."\"",
      'form_name' => $form_name,
      'user_name' => $_REQUEST['name'],
      'user_phone' => $_REQUEST['phone'],
      'dop_info' => '<br><br>E-mail:<br><br>'.$_REQUEST['email'].'<br><br>Название товара:<br><br>'.$_REQUEST['prod_name'].'<br><br>ID товара:<br><br>'.$_REQUEST['prod_id']
    );
  }
  echo "form-1";
  if ($_REQUEST['form_id'] == '3') {
    echo "form-1";
    $productIDs = array();
    $res = CSaleBasket::GetList(array(), array(
                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
                "LID" => SITE_ID,
                "ORDER_ID" => "NULL"));
    while ($arItem = $res->Fetch()) {
      $productIDs[] = $arItem['PRODUCT_ID'];
    }
    if ($productIDs) {
      echo "form-2";
      $productObjects = array();

      $filt = array("IBLOCK_ID" => 7, "ID" => $productIDs);
      $getProds = CIBlockElement::GetList(
        array(),
        $filt,
        false,
        false,
        array('ID', 'NAME', 'DETAIL_PAGE_URL')
      );
      while ($resProds = $getProds->GetNext()) {
        $productObjects[md5($resProds['ID'])] = array(
          'name' => $resProds['NAME'],
          'id' => $resProds['ID'],
          'url' => $resProds['DETAIL_PAGE_URL'],
        );
      }
      if ($productObjects) {
        echo "form-3";
        CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
        $prodName = '';
        $prodIDs = '';
        $prodLinks = '';
        foreach ($productObjects as $k => $val) {
          if ($k == 0){
            $prodName .= $val['name'];
            $prodName .= $val['id'];
          }else{
            $prodName .= ", ".$val['name'];
            $prodIDs .= ", ".$val['id'];
          }
          $prodLinks .= '<a href="'.$val['url'].'">'.$val['name'].' ID:'.$val['id'].'</a><br><br>';
        }
        $form_id = 3;
        $data_form = array(
          'form_text_8' => $_REQUEST['name'],
          'form_text_9' => $_REQUEST['phone'],
          'form_text_10' => $_REQUEST['email'],
          'form_text_11' => $prodName,
          'form_text_12' => $prodIDs,
        );
        $form_name = 'Заказ товаров в один клик';
        $msgData = array(
          'title' => "Заявка с формы \"".$form_name."\"",
          'form_name' => $form_name,
          'user_name' => $_REQUEST['name'],
          'user_phone' => $_REQUEST['phone'],
          'dop_info' => '<br><br>E-mail:<br><br>'.$_REQUEST['email'].'<br><br>Товары:<br><br>'.$prodLinks
        );
      }
    }
  }

  if ($form_id and $data_form){
    CFormResult::Add($form_id, $data_form);
    CEvent::Send("form_msg", s1, $msgData);
  }
}
