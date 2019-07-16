<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

// echo "<pre style='text-align: left;'>";print_r($arResult);echo "</pre>";
// echo "<pre style='text-align: left;'>";print_r($arParams);echo "</pre>";
?>
<?
$hasFilterElement = false;
foreach($arResult["ITEMS"] as $key=>$arItem)//prices
{
	if(isset($arItem["PRICE"])):
		if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
			continue;
		else
			$hasFilterElement = true;
	endif;
	if(isset($arItem["PRICE"]) or !$arItem['DISPLAY_TYPE'] or !$arItem['VALUES'])
		continue;
	else
		$hasFilterElement = true;
}
?>
<?if($hasFilterElement):?>
<aside class="catalog-filter" data-action="<?echo $arResult["FORM_ACTION"]?>">
	<input type="hidden" name="set_filter" value="Y">
	<?foreach($arResult["HIDDEN"] as $arItem):?>
	<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
	<?endforeach;?>
    <div class="catalog-filter__close"></div>
	<ul class="catalog-filter__ul">
		<?foreach($arResult["ITEMS"] as $key=>$arItem)//prices
		{
			$key = $arItem["ENCODED_ID"];
			if(isset($arItem["PRICE"])):
				if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
					continue;
				$precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
				?>
				<li class="catalog-filter__li">
					<input type="checkbox" class="checkbox-1">
					<i></i>
					<h3>Стоимость</h3>
					<p class="price-slider">
						<label>
							<input
							class="catalog-filter__checkbox catalogPrice"
							type="checkbox"
							data-name-min="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
							data-name-max="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
							data-title="Стоимость: "
							name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?><?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
							>
							<span class="catalog-filter__item"> ₽ </span>
						</label>
						<?
						$minVal = $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"];
						$maxVal = $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"];
						$minVal = $minVal > 0 ? $minVal : 1;
						$maxVal = $maxVal > 0 ? $maxVal : 1;
						?>
						<input
						class="form-control"
						type="number"
						step="100"
						min="<?echo number_format($minVal, $precision, ".", "")?>"
						max="<?echo number_format($maxVal, $precision, ".", "")?>"
						data-name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?><?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
						name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
						id="price-start-alt"> &mdash;
						<input
						class="form-control"
						type="number"
						step="100"
						min="<?echo number_format($minVal, $precision, ".", "")?>"
						max="<?echo number_format($maxVal, $precision, ".", "")?>"
						data-name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?><?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
						name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
						id="price-end-alt"> ₽
						<span id="slider-range-alt"></span>
					</p>
				</li>
			<?endif;
		}?>
		<?foreach($arResult["ITEMS"] as $key=>$arItem)//prices
		{
			if(isset($arItem["PRICE"]) or !$arItem['DISPLAY_TYPE'] or !$arItem['VALUES'])
				continue;
		?>
			<li class="catalog-filter__li">
				<input type="checkbox" class="checkbox-1">
				<i></i>
				<h3><?=$arItem['NAME']?></h3>
				<?switch ($arItem["DISPLAY_TYPE"]) {
					case 'H':
						foreach ($arItem['VALUES'] as $c => $ar) {
						?>
							<p>
								<label>
									<input class="catalog-filter__checkbox" type="checkbox"
									name="<?=$ar["CONTROL_NAME"]?>"
									id="<?=$ar["CONTROL_ID"]?>"
									value="<?=$ar["HTML_VALUE"]?>"
									data-title="<?=$arItem['NAME']?>: "
									data-value="<?=$ar['VALUE']?>"
									<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>>
									<span class="catalog-filter__item"><?=$ar["VALUE"];?></span>
								</label>
							</p>
						<?
						}
						break;

					default:
						foreach ($arItem['VALUES'] as $c => $ar) {
						?>
							<p>
								<label>
									<input class="catalog-filter__checkbox" type="checkbox"
									name="<?=$ar["CONTROL_NAME"]?>"
									id="<?=$ar["CONTROL_ID"]?>"
									value="<?=$ar["HTML_VALUE"]?>"
									data-title="<?=$arItem['NAME']?>: "
									data-value="<?=$ar['VALUE']?>"
									<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>>
									<span class="catalog-filter__item"><?=$ar["VALUE"];?></span>
								</label>
							</p>
						<?
						}
						break;
				}?>

			</li>
		<?}?>
	</ul>
</aside>
<?endif;?>
