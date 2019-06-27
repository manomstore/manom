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

?>

<div class="articles__bottom">
	<div class="articles__pagination">
		<?for ($i=(int)$arResult['nStartPage']; $i <= (int)$arResult['nEndPage']; $i++) {?>
			<?if ($i == (int)$arResult['NavPageNomer']) {?>
				<div class="articles__pagination_item active"><?=$i?></div>
			<?}else{?>
				<div class="articles__pagination_item">
					<a href="<?=$arResult["sUrlPathParams"]?><?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$i?>"><?=$i?></a>
				</div>
			<?}?>
		<?}?>
	</div>
	<div class="articles__count">
		Товары <span class="articles__current"><?=$arResult["NavFirstRecordShow"]?>—<?=$arResult["NavLastRecordShow"]?></span> из <span class="articles__total"><?=$arResult["NavRecordCount"]?></span>
	</div>
</div>
