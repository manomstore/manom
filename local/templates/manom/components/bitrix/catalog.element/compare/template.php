<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

// unset($arResult['OFFERS']);
// echo "<pre style='text-align:left;'>";print_r($arResult['DISPLAY_PROPERTIES']);echo "</pre>";
foreach ($arResult['DISPLAY_PROPERTIES'] as $key => $prop) {?>
	<p>
		<span class="p-cart-properties__name"><?=$prop['NAME']?></span>
		<span class="p-cart-properties__value bgreen"><?=is_array($prop['DISPLAY_VALUE']) ? implode(",", $prop['DISPLAY_VALUE']) : $prop['DISPLAY_VALUE'];?></span>
	</p>
<?}?>
