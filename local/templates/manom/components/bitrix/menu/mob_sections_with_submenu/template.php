<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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

$this->setFrameMode(true); ?>

<? if (!empty($arResult)): ?>
    <ul class="top-nav__list">
        <? foreach ($arResult as $arItem): ?>
            <li>
                <a class="top-nav__link top-nav__link--arrow js-open-submenu"
                   data-item-menu-id="<?= $arItem["ITEM_MENU_ID"] ?>"
                   href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
            </li>
        <? endforeach; ?>
    </ul>
<? endif; ?>


