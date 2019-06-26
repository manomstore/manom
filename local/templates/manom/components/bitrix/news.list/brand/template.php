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
    <h2 class="brands-h2">Бренды</h2>
    <div class="row brands__block">
        <?foreach ($arResult['ITEMS'] as $key => $item) {?>
            <div class="col-2">
                <div class="brands__img">
                    <a href="/catalog/?brand=<?=$item['ID']?>"><img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="<?=$item['PREVIEW_PICTURE']['ALT']?>"></a>
                </div>
            </div>
        <?}?>
    </div>
</div>
