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
<div class="p-article__content">
	<h1 class="p-article__title"><?=$arResult['NAME']?></h1>
	<?=$arResult['DETAIL_TEXT']?>
</div>
<script>
    $(function () {
        window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("content")?>);
    });
</script>