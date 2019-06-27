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
// $this->setFrameMode(true);?>
<?
global $hasSubSec;
$hasSubSec = false;

if($arResult['SECTIONS']){
	$hasSubSec = true;
	$secBaner = array();
	$secBanerWithoutCategory = array();
	$getSecBaner = CIBlockElement::GetList(
		array('sort' => 'asc'),
		array('IBLOCK_ID' => 10, 'ACTIVE' => 'Y'),
		false,
		false,
		array(
			'ID',
			'PROPERTY_CB_BTN_TEXT',
			'PROPERTY_CB_BTN_LINK',
			'PROPERTY_CB_TEXT',
			'PROPERTY_CB_CATEGORY',
			'PREVIEW_PICTURE'
		)
	);
	while ($resSecBaner = $getSecBaner->Fetch()) {
		if (!$resSecBaner['PROPERTY_CB_CATEGORY_VALUE'] and !$secBanerWithoutCategory) {
			$secBanerWithoutCategory = array(
				'btn_link' => $resSecBaner['PROPERTY_CB_BTN_LINK_VALUE'],
				'btn_text' => $resSecBaner['PROPERTY_CB_BTN_TEXT_VALUE'],
				'text' => $resSecBaner['PROPERTY_CB_TEXT_VALUE'],
				'img' => CFile::GetPath($resSecBaner['PREVIEW_PICTURE']),
			);
		} elseif ($resSecBaner['PROPERTY_CB_CATEGORY_VALUE'] and $resSecBaner['PROPERTY_CB_CATEGORY_VALUE'] == $arResult['SECTION']['ID'] and !$secBaner) {
			$secBaner = array(
				'btn_link' => $resSecBaner['PROPERTY_CB_BTN_LINK_VALUE'],
				'btn_text' => $resSecBaner['PROPERTY_CB_BTN_TEXT_VALUE'],
				'text' => $resSecBaner['PROPERTY_CB_TEXT_VALUE'],
				'img' => CFile::GetPath($resSecBaner['PREVIEW_PICTURE']),
			);
		}
	}
	$baner = !$secBaner ? $secBanerWithoutCategory : $secBaner;
	?>
	<section class='sc-banner'>
		<div class="container">
			<h2 class="sc-banner__h2"><?$APPLICATION->ShowTitle()?></h2>
			<?if($baner):?>
				<div class="sc-banner__block" style="background-image: url('<?=$baner['img']?>');">
					<h1 class="sc-banner__title"><?=$baner['text']?></h1>
					<a href="<?=$baner['btn_link']?>" class="sc-banner__button"><?=$baner['btn_text']?></a>
				</div>
			<?endif;?>
		</div>
	</section>
	<section class="sc-category">
		<div class="container">
			<div class="sc-category__block">
				<?foreach ($arResult['SECTIONS'] as $key => $item) {
					if($item['ID'] == $arParams['DISCOUNTED_SECTION_ID']) continue;
                    $img = CFile::ResizeImageGet($item['PICTURE']['ID'], array('width'=>250, 'height'=>250), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					?>
					<a href="<?=$item['SECTION_PAGE_URL']?>" class="sc-category-card">
						<div class="sc-category-card__img" style="background: url('<?=$img['src']?>') center no-repeat;"></div>
						<h3 class="sc-category-card__title"><?=$item['NAME']?></h3>
					</a>
				<?}?>

			</div>
		</div>
	</section>
<?}?>
