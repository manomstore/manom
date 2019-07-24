<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main;

$prodIblockID = 6;
$offersIblockID = 7;

$prodIDs = array();
$cmlProdIDs = array();
$acessAndServIDs = array();
$rlateCmlProds = array();
foreach ($arResult['GRID']['ROWS'] as $i => $item) {
    if (!$item['PICTURE_SRC'] and !$item['DETAIL_PICTURE']) {
        $prodIDs[] = $item['PRODUCT_ID'];
    } elseif ($item['PICTURE_SRC']) {
        $arResult['GRID']['ROWS'][$i]['PIC'] = $item['PICTURE_SRC'];
    } else {
        $arResult['GRID']['ROWS'][$i]['PIC'] = $item['DETAIL_PICTURE'];
    }
    if ($item['PICTURE_SRC'] or $item['DETAIL_PICTURE']) {
        $arResult['GRID']['ROWS'][$i]['has_prod'] = true;
    }
    $arResult['GRID']['ROWS'][$i]['SUM'] = \CCurrencyLang::CurrencyFormat(
        $item['SUM_VALUE'],
        $item['CURRENCY'],
        false
    );
    $arResult['GRID']['ROWS'][$i]['PRICE_FORMATED'] = \CCurrencyLang::CurrencyFormat(
        $item['PRICE'],
        $item['CURRENCY'],
        false
    );

    if ($item["DISCOUNT_PRICE_PERCENT"] > 0) {
        $arResult['GRID']['ROWS'][$i]["SUM_FULL_PRICE_FORMATED"] = \CCurrencyLang::CurrencyFormat(
            $item['SUM_FULL_PRICE'],
            $item['CURRENCY'],
            false
        );
    }
}
$getProd = CIBlockElement::GetList(
  array(),
  array(
    'IBLOCK_ID' => $offersIblockID,
    'ID' => $prodIDs
  ),
  false,
  false,
  array('ID', 'PROPERTY_MORE_PHOTO', 'PROPERTY_CML2_LINK', 'IBLOCK_ID')
);
while ($resProd = $getProd->Fetch()) {
  $cmlProdIDs[md5($resProd['PROPERTY_CML2_LINK_VALUE'])] = $resProd['PROPERTY_CML2_LINK_VALUE'];
  if(!$rlateCmlProds[md5($resProd['PROPERTY_CML2_LINK_VALUE'])]) {
    $rlateCmlProds[md5($resProd['PROPERTY_CML2_LINK_VALUE'])] = array(
      "ID" => $resProd['PROPERTY_CML2_LINK_VALUE'],
      "OFFERS" => array(),
      "ACESS" => array(),
      "DOP_SERV" => array(),
      "ACESS_OBJ" => array(),
      "DOP_SERV_OBJ" => array()
    );
    $getRec = CIBlockElement::GetProperty(
      $prodIblockID,
      $resProd['PROPERTY_CML2_LINK_VALUE'],
      "sort", "asc",
      array(
        "CODE" => 'ACESS'
      )
    );
    while ($resRec = $getRec->Fetch()) {
      $acessAndServIDs[md5($resRec['VALUE'])] = $resRec['VALUE'];
      $rlateCmlProds[md5($resProd['PROPERTY_CML2_LINK_VALUE'])]['ACESS'][md5($resRec['VALUE'])] = $resRec['VALUE'];
    }
    $getRec = CIBlockElement::GetProperty(
      $prodIblockID,
      $resProd['PROPERTY_CML2_LINK_VALUE'],
      "sort", "asc",
      array(
        "CODE" => 'DOP_SERV'
      )
    );
    while ($resRec = $getRec->Fetch()) {
      $acessAndServIDs[md5($resRec['VALUE'])] = $resRec['VALUE'];
      $rlateCmlProds[md5($resProd['PROPERTY_CML2_LINK_VALUE'])]['DOP_SERV'][md5($resRec['VALUE'])] = $resRec['VALUE'];
    }
  }
  $rlateCmlProds[md5($resProd['PROPERTY_CML2_LINK_VALUE'])]['OFFERS'][md5($resProd['ID'])] = $resProd['ID'];
  foreach ($arResult['GRID']['ROWS'] as $i => $item) {
     if ($item['PRODUCT_ID'] == $resProd['ID']) {
       $arResult['GRID']['ROWS'][$i]['has_prod'] = true;
       $picProp = CIBlockElement::GetProperty($resProd['IBLOCK_ID'], $resProd['ID'], array("id" => "asc"), Array("CODE"=>"MORE_PHOTO"));
       if ($resPicProp = $picProp->Fetch()) {
         $arResult['GRID']['ROWS'][$i]['PIC'] = CFile::GetPath($resPicProp['VALUE']);
       }
       // if ($resProd['PROPERTY_MORE_PHOTO_VALUE']) {
       //   $arResult['GRID']['ROWS'][$i]['PIC'] = CFile::GetPath($resProd['PROPERTY_MORE_PHOTO_VALUE']);
       // }
     }
   }
}
// echo "<pre>".print_r($arResult['GRID']['ROWS'][149])."</pre>";

foreach ($arResult['CATEGORIES'] as $key => $cat) {
  foreach ($cat as $i => $item) {
    if(!$item['has_prod']) {
      echo $item['ID'];
      if (CSaleBasket::Delete($item['ID'])) {
        unset($arResult['GRID']['ROWS'][$i]);
      }
    }
  }
}

if ($acessAndServIDs):
  $getEl = CIBlockElement::GetList(
    array(),
    array(
      'IBLOCK_ID' => $offersIblockID,
      'ACTIVE' => 'Y',
      'PROPERTY_CML2_LINK' => $acessAndServIDs
    ),
    false,
    false,
    array('ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE',
    'PROPERTY_MORE_PHOTO', 'NAME', 'DETAIL_PAGE_URL', 'PROPERTY_CML2_LINK',
    'CATALOG_QUANTITY')
  );
  $offersList = array();
  while ($resEl = $getEl->GetNext()) {
    if ($offersList[md5($resEl['PROPERTY_CML2_LINK_VALUE'])]) continue;
    $ar_res = CPrice::GetBasePrice($resEl['ID']);
    if ($ar_res){
      if ($ar_res['PRICE'] > 0 and $resEl['CATALOG_QUANTITY'] > 0){
        $imgSrc = '';
        if ($resEl['PREVIEW_PICTURE']) {
          $imgSrc = CFile::GetPath($resEl['PREVIEW_PICTURE']);
        } elseif ($resEl['DETAIL_PICTURE']) {
          $imgSrc = CFile::GetPath($resEl['DETAIL_PICTURE']);
        } elseif ($resEl['PROPERTY_MORE_PHOTO_VALUE']) {
          $imgSrc = CFile::GetPath($resEl['PROPERTY_MORE_PHOTO_VALUE']);
        }
        $offerElem = array(
          'id' => $resEl['ID'],
          'name' => $resEl['NAME'],
          'img' => $imgSrc,
          'price' => $ar_res['PRICE'],
          'url' => $resEl['DETAIL_PAGE_URL'],
          'prod_id' => $resEl['PROPERTY_CML2_LINK_VALUE'],
        );
        $offersList[md5($resEl['PROPERTY_CML2_LINK_VALUE'])] = $offerElem;
        if (!$rlateCmlProds[md5($resEl['PROPERTY_CML2_LINK_VALUE'])]['OFFERS'][md5($resEl['ID'])]) {
          foreach ($rlateCmlProds as $i => $prod) {
            if ($prod['ACESS'][md5($resEl['PROPERTY_CML2_LINK_VALUE'])]) {
              $rlateCmlProds[$i]['ACESS_OBJ'][] = $offerElem;
            }
            if ($prod['DOP_SERV'][md5($resEl['PROPERTY_CML2_LINK_VALUE'])]) {
              $rlateCmlProds[$i]['DOP_SERV_OBJ'][] = $offerElem;
            }
          }
        }
      }
    }
  }
endif;

$arResult['CML_PROD'] = $rlateCmlProds;
