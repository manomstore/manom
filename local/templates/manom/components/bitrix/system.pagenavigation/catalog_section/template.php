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
<div class="cb-nav-bottom ajaxPageNav">
    <div class="cb-nav-pagination">
        <? if ((int)$arResult['nStartPage'] > 1): ?>
            <div
                    class="cb-nav-pagination__item"
                    data-href="<?= $arResult["sUrlPathParams"] ?><?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1"
            >
                <a href="<?= $arResult["sUrlPathParams"] ?><?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1">
                    1
                </a>
            </div>
        <? endif; ?>
        <?for ($i=(int)$arResult['nStartPage']; $i <= (int)$arResult['nEndPage']; $i++) {?>
            <?if ($i == (int)$arResult['NavPageNomer']) {?>
                <div class="cb-nav-pagination__item active" data-href="<?=$arResult["sUrlPathParams"]?><?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$i?>"><?=$i?></div>
            <?}else{?>
                <div class="cb-nav-pagination__item" data-href="<?=$arResult["sUrlPathParams"]?><?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$i?>">
                    <a href="<?=$arResult["sUrlPathParams"]?><?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$i?>"><?=$i?></a>
                </div>
            <?}?>
        <?}?>
        <? if ((int)$arResult['nEndPage'] < $arResult["NavPageCount"]): ?>
            <div
                    class="cb-nav-pagination__item"
                    data-href="<?= $arResult["sUrlPathParams"] ?><?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>"
            >
                <a href="<?= $arResult["sUrlPathParams"] ?><?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>">
                    <?= $arResult["NavPageCount"] ?>
                </a>
            </div>
        <? endif; ?>
    </div>
    <div class="cb-nav-count">
        <span class="cb-nav__text">Товары </span> <span class="articles__current"><?=$arResult["NavFirstRecordShow"]?>—<?=$arResult["NavLastRecordShow"]?></span> из <span class="articles__total"><?=$arResult["NavRecordCount"]?></span>
    </div>
</div>

<span class="catTopCountValue"><span class="cb-nav__text">Товары </span> <span class="cb-nav-count__current"><?=$arResult["NavFirstRecordShow"]?>—<?=$arResult["NavLastRecordShow"]?></span> из <span class="cb-nav-count__total"><?=$arResult["NavRecordCount"]?></span></span>
