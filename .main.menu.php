<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;
$aMenuLinksExt = array();

if(CModule::IncludeModule('iblock'))
{
    $arFilter = array(
        "TYPE" => "catalog",
        "SITE_ID" => SITE_ID,
    );

    $dbIBlock = CIBlock::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), $arFilter);
    $dbIBlock = new CIBlockResult($dbIBlock);

    if ($arIBlock = $dbIBlock->GetNext())
    {
        if(defined("BX_COMP_MANAGED_CACHE"))
            $GLOBALS["CACHE_MANAGER"]->RegisterTag("iblock_id_".$arIBlock["ID"]);

        if($arIBlock["ACTIVE"] == "Y")
        {
            $aMenuLinksExt = $APPLICATION->IncludeComponent("custom:menu.sections", "main", array(
                "IS_SEF" => "N",
                "SEF_BASE_URL" => "",
                "SECTION_PAGE_URL" => $arIBlock['SECTION_PAGE_URL'],
                "DETAIL_PAGE_URL" => $arIBlock['DETAIL_PAGE_URL'],
                "IBLOCK_TYPE" => $arIBlock['IBLOCK_TYPE_ID'],
                "IBLOCK_ID" => $arIBlock['ID'],
                "DEPTH_LEVEL" => "3",
                "CACHE_TYPE" => "N",
            ), false, Array('HIDE_ICONS' => ''));
        }
    }

    if(defined("BX_COMP_MANAGED_CACHE"))
        $GLOBALS["CACHE_MANAGER"]->RegisterTag("iblock_id_new");
}

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>
<div class="top-menu2">
    <?$start = 0;?>
    <?foreach ($aMenuLinks as $key => $arLink){
        if($arLink[3]['DEPTH_LEVEL'] == 1 ) {?>
            <?$start++?>
            <a href="#" class="top-menu2__item" data-id="ts2-items1"><?=$arLink[0]?></a>
            <!-- <div class="top-menu2__vl"></div> -->
            <?if($start == 5){?>
                <?break;?>
            <?}?>
        <?}
    }?>
</div>
<div class="top-submenu2__block">
    <?foreach ($aMenuLinks as $key => $arLink) {?>
        <?if ($arLink[3]['DEPTH_LEVEL'] == 2 ) {?>
            <?$file = CFile::ResizeImageGet($arLink[3]['PICTURE'], array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
            <div id="ts2-items1" class="top-submenu2__items">
                <a href="" class="top-submenu2__item">
                    <div class="top-submenu2__img">
                        <img src="<?echo $file['src']?>" alt="">
                    </div>
                    <h3 class="top-submenu2__title"><?=$arLink[0]?></h3>
                </a>
                <div class="vertical-line"></div>
            </div>
        <?}?>
    <?}?>
</div>
