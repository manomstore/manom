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
<main class="articles container">
	<h1 class="articles__title">Статьи</h1>

	<div class="articles__block">
		<?foreach ($arResult['ITEMS'] as $key => $item) {?>
			<div class="article">
                <?$img = CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], array('width'=>370, 'height'=>250), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
				<a href="<?=$item['DETAIL_PAGE_URL']?>">
					<div class="article__img" style="background: url('<?=$img['src']?>') no-repeat, center;"></div>
				</a>
				<?
					$dateArticles = explode(' ', $item['TIMESTAMP_X']);
				?>
				<p class="article__date"><?=$dateArticles[0]?></p>
				<a href="<?=$item['DETAIL_PAGE_URL']?>">
					<h2 class="article__title"><?=$item['NAME']?></h2>
				</a>
				<p class="article__text"><?=$item['PREVIEW_TEXT']?>
				</p>
				<a href="<?=$item['DETAIL_PAGE_URL']?>" class="article__read">Читать далее...</a>
			</div>
		<?}?>

	</div>

	<!-- Пагинация -->
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<br /><?=$arResult["NAV_STRING"]?>
	<?endif;?>

</main>
<script>
    $(function () {
        window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("content")?>);
    });
</script>