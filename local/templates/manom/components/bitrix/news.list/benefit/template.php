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
<div class="container">
    <div class="benefit__block">
        <?foreach ($arResult['ITEMS'] as $key => $item) {?>
            <?$imagePath = CFile::GetPath($item['PROPERTIES']['BA_SVG']['VALUE']);?>
            <div class="benefit__item-wrapper">
                <div class="benefit__item" title="<?=$item['~PREVIEW_TEXT']?>">
                    <img src="<?=$imagePath?>" alt="" class="benefit__img">
                    <h3 class="benefit__title"><?=$item['~NAME']?></h3>
                </div>
            </div>
        <?}?>
    </div>
</div>