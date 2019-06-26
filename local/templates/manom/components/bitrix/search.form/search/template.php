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
$this->setFrameMode(true);?>

    <div class="top-nav1__search coll-5">
        <form action="<?=$arResult["FORM_ACTION"]?>" is-search-form>
            <?$APPLICATION->IncludeComponent(
                "bitrix:search.suggest.input",
                "header-search",
                array(
                    "NAME" => "q",
                    "VALUE" => "",
                    "INPUT_SIZE" => 15,
                    "DROPDOWN_SIZE" => 10,
                ),
                $component, array("HIDE_ICONS" => "Y")
            );?>
            <a href="" class="top-nav1__search-button">
                <input name="s" type="submit" value="" style="background: url('<?=SITE_TEMPLATE_PATH?>/assets/img/top-search.svg') no-repeat;width: 13px;border: none;"/>
            </a>
        </form>
    </div>
