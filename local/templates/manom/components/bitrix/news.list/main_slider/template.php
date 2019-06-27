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

<div class="first-scr__left">
    <div class="top-slider">
        <?foreach ($arResult['ITEMS'] as $key => $item){?>
            <div class="top-slider__item top-slide1" style="background: url('<?=$item['PREVIEW_PICTURE']['SRC']?>') center no-repeat;" onclick="location.href = '<?=$item['PROPERTIES']['MB_BTN_LINK']['VALUE']?>'">
                <h1 style="<?if (!empty($item['PROPERTIES']['MB_TEXT_FONT_SIZE']['VALUE'])){?>font-size:<?=$item['PROPERTIES']['MB_TEXT_FONT_SIZE']['VALUE']?>;<?}else{?>font-size: 36px;<?}?> color: <?=$item['PROPERTIES']['MB_TEXT_COLOR']['VALUE']?>; <?if ($item['PROPERTIES']['MB_CONTENT_POSITION']['VALUE'] == 'Справа'){?>right: 75px;text-align: right;<?}else{?>left: 75px<?}?>" class="top-slide1__title"><?=$item['PROPERTIES']['MB_HEADER']['VALUE']?></h1>
                <?if ($item['PROPERTIES']['MB_BTN_DISABLE']['VALUE'] != 'Да') {?>
                    <a style="<?if (!empty($item['PROPERTIES']['MB_BTN_TEXT_FONT_SIZE']['VALUE'])){?>font-size:<?=$item['PROPERTIES']['MB_BTN_TEXT_FONT_SIZE']['VALUE']?>;<?}else{?>font-size: 18px;<?}?> color:<?=$item['PROPERTIES']['MB_BTN_TEXT_COLOR']['VALUE']?>; background-color: <?=$item['PROPERTIES']['MB_BTN_COLOR']['VALUE']?>; <?if ($item['PROPERTIES']['MB_CONTENT_POSITION']['VALUE'] == 'Справа'){?>right: 75px;<?}else{?>left: 75px<?}?>" href="<?=$item['PROPERTIES']['MB_BTN_LINK']['VALUE']?>" class="top-slide1__button"><?=$item['PROPERTIES']['MB_BTN_TEXT']['VALUE']?></a>
                <?}?>
            </div>
        <?}?>
    </div>
</div>
