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

<div class="top-menu2">
    <?$start = 0;?>
    <?$arIdList = array();?>
    <?foreach ($arResult['SECTIONS'] as $key => $arLink){
        $arIdList[] = $arLink['ID'];
        if($arLink['DEPTH_LEVEL'] == 1 ) {?>
            <?$start++?>
            <a href="<?=$arLink['SECTION_PAGE_URL']?>" class="top-menu2__item" data-id="ts2-items1-<?=$arLink['ID']?>">
                <div class="top-menu2__item-img">
                    <img src="<?=$arLink['PICTURE']['SRC']?>" alt="">
                </div>
                <p><?=$arLink['NAME']?></p>
            </a>
            <?if($start == 5){?>
                <?break;?>
            <?}?>
        <?}
    }?>

    <div class="top-submenu2__block">
        <?foreach ($arIdList as $k => $itemId){?>
            <div id="ts2-items1-<?=$itemId?>" class="top-submenu2__items">
                <?foreach ($arResult['SECTIONS'] as $tg => $arItem) {?>
                    <?if ($arItem['DEPTH_LEVEL'] == 2 and $arItem['IBLOCK_SECTION_ID'] == $itemId) {?>
                        <a href="<?=$arItem['SECTION_PAGE_URL']?>" class="top-submenu2__item">
                            <div class="top-submenu2__img">
                            <?if ($arItem['PICTURE']) {
                                $file = CFile::ResizeImageGet($arItem['PICTURE']['ID'], array('width'=>250, 'height'=>250), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                ?>
                                <img src="<?=$file['src']?>" alt="">
                                <?
                            }?>
                            </div>
                            <h3 class="top-submenu2__title"><?=$arItem['NAME']?></h3>
                        </a>
                        <div class="vertical-line"></div>
                    <?}?>
                <?}?>
            </div>
        <?}?>
    <!--        --><?//
    //        $page = $APPLICATION->GetCurPage();
    //        if ($USER->IsAdmin() and $page == '/'){
    //            $GLOBALS["MY_DEBUG"] = $arResult;
    //        }
    //        ?>
    </div>
</div>