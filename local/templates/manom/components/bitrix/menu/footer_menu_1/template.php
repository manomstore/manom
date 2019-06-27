<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

CUtil::InitJSCore();

?>
<?foreach ($arResult as $key => $items) {
    ?>
    <a href="<?=$items['LINK']?>" class="footer-nav__item"><?=$items['TEXT']?></a>
    <?
}?>
