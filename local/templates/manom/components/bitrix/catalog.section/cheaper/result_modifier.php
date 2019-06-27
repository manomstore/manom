<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

//получаем основную ссылку на товар
function getLinkForOffer($offer) {
  $getProd = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arParams['PROD_IBLOCK_ID'], 'ID' =>$offer['PROPERTIES']['CML2_LINK']['VALUE']), false, false, array('DETAIL_PAGE_URL'));
  if ($resProd = $getProd->GetNext()) {
    return $resProd['DETAIL_PAGE_URL'].'?offer='.$offer['ID'];
  }
}
