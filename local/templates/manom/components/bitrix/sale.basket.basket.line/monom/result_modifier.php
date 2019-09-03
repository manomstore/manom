<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$prodIDs = array();
foreach ($arResult['CATEGORIES'] as $key => $cat) {
  foreach ($cat as $i => $item) {
      if (!$item['PICTURE_SRC'] and !$item['DETAIL_PICTURE']) {
          $prodIDs[] = $item['PRODUCT_ID'];
      } elseif ($item['PICTURE_SRC']) {
          $arResult['CATEGORIES'][$key][$i]['PIC'] = $item['PICTURE_SRC'];
      } else {
          $arResult['CATEGORIES'][$key][$i]['PIC'] = $item['DETAIL_PICTURE'];
      }
      if ($item['PICTURE_SRC'] or $item['DETAIL_PICTURE']) {
          $arResult['CATEGORIES'][$key][$i]['has_prod'] = true;
      }

      if ((int)$arResult['CATEGORIES'][$key][$i]['DISCOUNT_PRICE_PERCENT'] > 0) {
          $arResult['CATEGORIES'][$key][$i]["OLD_SUM_VALUE"] = $arResult['CATEGORIES'][$key][$i]["BASE_PRICE"] *
              $arResult['CATEGORIES'][$key][$i]["QUANTITY"];
          $arResult['CATEGORIES'][$key][$i]["EXIST_DISCOUNT"] = true;
      } else {
          $arResult['CATEGORIES'][$key][$i]["EXIST_DISCOUNT"] = false;
      }
  }
}
$getProd = CIBlockElement::GetList(
  array(),
  array(
    'IBLOCK_ID' => 7,
    'ID' => $prodIDs
  ),
  false,
  false,
  array('ID', 'PROPERTY_MORE_PHOTO', 'IBLOCK_ID')
);
while ($resProd = $getProd->Fetch()) {
  foreach ($arResult['CATEGORIES'] as $key => $cat) {
     foreach ($cat as $i => $item) {
       if ($item['PRODUCT_ID'] == $resProd['ID']) {
         $arResult['CATEGORIES'][$key][$i]['has_prod'] = true;
         $picProp = CIBlockElement::GetProperty($resProd['IBLOCK_ID'], $resProd['ID'], array("id" => "asc"), Array("CODE"=>"MORE_PHOTO"));
         if ($resPicProp = $picProp->Fetch()) {
           $arResult['CATEGORIES'][$key][$i]['PIC'] = CFile::GetPath($resPicProp['VALUE']);
         }
         // if ($resProd['PROPERTY_MORE_PHOTO_VALUE']) {
         //   $arResult['CATEGORIES'][$key][$i]['PIC'] = CFile::GetPath($resProd['PROPERTY_MORE_PHOTO_VALUE']);
         // }
       }
     }
  }
}

foreach ($arResult['CATEGORIES'] as $key => $cat) {
  foreach ($cat as $i => $item) {
    if(!$item['has_prod']) {
      echo $item['ID'];
      if (CSaleBasket::Delete($item['ID'])) {
        unset($arResult['CATEGORIES'][$key][$i]);
      }
    }
  }
}
