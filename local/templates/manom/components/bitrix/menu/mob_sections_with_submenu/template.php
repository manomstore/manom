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
            <li class="top-nav__list-item">
                <a class="top-nav__link <?= $arItem["disabled"] ? "disabled" : "" ?> <?= $arItem["notLink"] ? "not-link" : "" ?>"
                   href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
                <? if ($arItem["hasChildren"]): ?>
                    <span class="top-nav__link-arrow js-open-submenu" data-item-menu-id="<?= $arItem["itemId"] ?>">

                    </span>
                <? endif; ?>
            </li>
        <? endforeach; ?>
    </ul>
<? endif; ?>


