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
?>
<div class="subscription" id="subscribe-form">
<?
$frame = $this->createFrame("subscribe-form", false)->begin();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>">

	<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
		<input type="hidden" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"/>
	<?endforeach;?>
		<input type="text" name="sf_EMAIL" value="<?=$arResult["EMAIL"]?>" class="subscription__input" placeholder="Подпишись на рассылку">

		<button type="submit" class="subscription__button"style="
    background: transparent;
    border: 0;
		cursor: pointer;
">
			<img src="<?=SITE_TEMPLATE_PATH?>/assets/img/letter.svg" alt="">
		</a>
	</form>

<?
$frame->beginStub();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>">
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<input type="hidden" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>"/>
		<?endforeach;?>
			<input type="text" name="sf_EMAIL" value="" class="subscription__input" placeholder="Подпишись на рассылку">
			<button type="submit" class="subscription__button"style="
    background: transparent;
    border: 0;
		cursor: pointer;
">
				<img src="<?=SITE_TEMPLATE_PATH?>/assets/img/letter.svg" alt="">
			</a>
	</form>
<?
$frame->end();
?>
</div>
