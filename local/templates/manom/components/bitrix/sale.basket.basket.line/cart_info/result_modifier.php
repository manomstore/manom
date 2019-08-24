<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$prodIDs = array();
foreach ($arResult['CATEGORIES'] as $key => $cat) {
  foreach ($cat as $i => $item) {
    if (!$item['PICTURE_SRC'] and !$item['DETAIL_PICTURE'])
      $prodIDs[] = $item['PRODUCT_ID'];
    elseif($item['PICTURE_SRC'])
      $arResult['CATEGORIES'][$key][$i]['PIC'] = $item['PICTURE_SRC'];
    else
      $arResult['CATEGORIES'][$key][$i]['PIC'] = $item['DETAIL_PICTURE'];
    if ($item['PICTURE_SRC'] or $item['DETAIL_PICTURE'])
      $arResult['CATEGORIES'][$key][$i]['has_prod'] = true;

      $arResult['CATEGORIES'][$key][$i]['SUM'] = \CCurrencyLang::CurrencyFormat(
          $item['SUM_VALUE'],
          $item['CURRENCY'],
          false
      );

      if ($item["DISCOUNT_PRICE_PERCENT"] > 0) {
          $arResult['CATEGORIES'][$key][$i]["SUM_FULL_PRICE_FORMATTED"] = \CCurrencyLang::CurrencyFormat(
              $item['BASE_PRICE'] * $item['QUANTITY'],
              $item['CURRENCY'],
              false
          );
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
