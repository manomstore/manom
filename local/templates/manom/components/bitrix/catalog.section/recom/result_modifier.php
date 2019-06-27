<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$ids = array();
foreach ($arResult['ITEMS'] as $key => $value) {
  $ids[] = $value['ID'];
}
$arResult['REVIEW'] = getRatingAndCountReviewForList($ids);
