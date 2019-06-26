<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
global $glob_allItemsByFilter;

$ids = array();
foreach ($arResult['ITEMS'] as $key => $item) {
  $this_element = $glob_allItemsByFilter['stack'][$item['PROPERTIES']['CML2_LINK']['VALUE']];
  $arResult['ITEMS'][$key]['DETAIL_PAGE_URL'] = $this_element['URL'].'?offer='.$item['ID'];
  if (!$item['PROPERTIES']['MORE_PHOTO']['VALUE']){
    if ($item['PREVIEW_PICTURE']['ID']) {
      $item['PROPERTIES']['MORE_PHOTO']['VALUE'] = array($item['PREVIEW_PICTURE']['ID']);
    } elseif ($item['DETAIL_PICTURE']['ID']) {
      $item['PROPERTIES']['MORE_PHOTO']['VALUE'] = array($item['DETAIL_PICTURE']['ID']);
    } elseif ($this_element['PREVIEW_PICTURE']) {
      $item['PROPERTIES']['MORE_PHOTO']['VALUE'] = $this_element['PIC'];
    } elseif ($this_element['PREVIEW_PICTURE']) {
      $item['PROPERTIES']['MORE_PHOTO']['VALUE'] = array($this_element['PREVIEW_PICTURE']);
    } elseif ($this_element['DETAIL_PICTURE']) {
      $item['PROPERTIES']['MORE_PHOTO']['VALUE'] = array($this_element['DETAIL_PICTURE']);
    }
  }
  $ids[] = $item['PROPERTIES']['CML2_LINK']['VALUE'];
}
$rev = getRatingAndCountReviewForList($ids);
$arResult['REVIEW'] = array();
foreach ($rev as $key => $value) {
  foreach ($arResult['ITEMS'] as $item) {
    if ($item['PROPERTIES']['CML2_LINK']['VALUE'] == $key){
      $arResult['REVIEW'][$item['ID']] = $value;
    }
  }
}
