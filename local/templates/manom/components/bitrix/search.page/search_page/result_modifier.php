<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult["TAGS_CHAIN"] = array();
if($arResult["REQUEST"]["~TAGS"])
{
	$res = array_unique(explode(",", $arResult["REQUEST"]["~TAGS"]));
	$url = array();
	foreach ($res as $key => $tags)
	{
		$tags = trim($tags);
		if(!empty($tags))
		{
			$url_without = $res;
			unset($url_without[$key]);
			$url[$tags] = $tags;
			$result = array(
				"TAG_NAME" => htmlspecialcharsex($tags),
				"TAG_PATH" => $APPLICATION->GetCurPageParam("tags=".urlencode(implode(",", $url)), array("tags")),
				"TAG_WITHOUT" => $APPLICATION->GetCurPageParam((count($url_without) > 0 ? "tags=".urlencode(implode(",", $url_without)) : ""), array("tags")),
			);
			$arResult["TAGS_CHAIN"][] = $result;
		}
	}
}
$ids = array();
foreach ($arResult['SEARCH'] as $key => $item) {
	$getProp = CIBlockElement::GetProperty(7, $item['ITEM_ID'], "sort", "asc", array("CODE" => "CML2_LINK"));
	if ($resProp = $getProp->Fetch()) {
		$arResult['SEARCH'][$key]['PROPERTIES'] = array('CML2_LINK' => array('VALUE' => $resProp['VALUE']));
		$ids[] = $resProp['VALUE'];
	}
}

$rev = getRatingAndCountReviewForList($ids);
$arResult['REVIEW'] = array();
foreach ($rev as $key => $value) {
  foreach ($arResult['SEARCH'] as $item) {
    if ($item['PROPERTIES']['CML2_LINK']['VALUE'] == $key){
      $arResult['REVIEW'][$item['ITEM_ID']] = $value;
    }
  }
}
?>
